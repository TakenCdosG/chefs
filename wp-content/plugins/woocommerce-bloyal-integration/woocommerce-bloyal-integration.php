<?php
/**
 * Plugin Name: WooCommerce bLoyal sync
 * Plugin URI: https://github.com/adrian-morelos/woocommerce-bloyal-integration
 * Description: This plugin syncs product inventories between Woocommerce and bLoyal
 * Version: 1.0.1
 * Author: Adrian A. Morelos Henriquez
 * Author URI: https://github.com/adrian-morelos
 * License: GPLv3
 */

// Include bLoyal PHP Constants
require_once '.config.inc.php';
global $LoginDomain, $accessKey, $apiKey, $storeCode, $connectorKey, $InventoryTransaction;

$LoginDomain = BLOYAL_CLIENT_LOGIN_DOMAIN;
$accessKey = BLOYAL_ACCESS_KEY;
$apiKey = BLOYAL_API_KEY;
$connectorKey = BLOYAL_CONNECTOR_KEY;
$storeCode = BLOYAL_STORECODE;
$InventoryTransaction = BLOYAL_INVENTORY_TRANSACTION;

/**
 * Action Reduce stock levels for all line items in the order.
 * Runs if stock management is enabled, but can be disabled on per-order basis by extensions @since 2.4.0 via woocommerce_can_reduce_order_stock hook.
 */
add_action('woocommerce_reduce_order_stock', 'woo_bloyal_reduce_order_stock');

/*
* Increase order item stock.
*/
add_action('woocommerce_restore_order_stock', 'woo_bloyal_restore_order_stock');

/*
 * Schedule the sync action to be done every 15 mins via WP-Cron
 */
add_action('init', 'woo_bloyal_setup_schedule');
function woo_bloyal_setup_schedule()
{
  //wp_clear_scheduled_hook ( 'woo_bloyal_sync_data' );
  if (defined('BLOYAL_ACCESS_KEY') && !wp_next_scheduled('woo_bloyal_sync_data')) {
    // schedule sync for every 15 minutes
    wp_schedule_event(time(), '*/15', 'woo_bloyal_sync_data');
    // also set up scheduled inventory reports on bloyal
    // woo_bloyal_schedule_reports();
  }
}

/*
 * Define Once 15 mins interval
 */
add_filter('cron_schedules', 'new_interval');
function new_interval($interval)
{
  $interval['*/15'] = array('interval' => 15 * 60, 'display' => 'Once 15 minutes');
  return $interval;
}

add_action('woo_bloyal_sync_data', 'woo_bloyal_do_data_sync');

function woo_bloyal_do_data_sync()
{
  global $LoginDomain, $accessKey, $apiKey, $storeCode, $connectorKey, $InventoryTransaction;
  ini_set('memory_limit', '-1');
  set_time_limit(0); // Limits the maximum execution time
  $log = "";

  // Sync Inventory
  // Inventory Resources

  /*
  * Step 0 : StartIntegrationBatch() - Start a replication 'batch'
  * POST api/v4/{accessKey}/IntegrationBatches
  */
  $startIntegrationBatch = FALSE;
  $params = array(
    'LoginDomain' => $LoginDomain,
    'connectorKey' => $connectorKey,
    'apiKey' => $apiKey,
  );

  $url = "https://{$LoginDomain}-grid.bloyal.com/api/v4/{$accessKey}/IntegrationBatches";
  $integrationBatches = curl_post($params, $url);
  $response = (array)json_decode($integrationBatches);

  if ($response["status"] == "success") {
    $startIntegrationBatch = TRUE;
    $batchTitle = "Batch - " . date("Y-m-d H:i:s");
    $batchProfile = $response["data"];
    $ConnectorSettings = $batchProfile->ConnectorSettings;
    $EntitySyncProfiles = $batchProfile->EntitySyncProfiles;
    $log .= "New Integration Batch Created.<br><b>Batch Title:</b> " . $batchTitle . "<br/>";
    $log .= "<b>Connector Settings:</b><br/>";
    foreach ($ConnectorSettings as $key => $val) {
      $log .= "Setting Name: {$key} | Value: {$val} <br>";
    }
    $log .= "<b>Entities to Replicate:</b> <br>";
    foreach ($EntitySyncProfiles as $key => $syncProfile) {
      $log .= "<b>Entity:</b> " . $syncProfile->EntityName . " | <b>Sync Direction:</b> " . $syncProfile->Direction . " <br>";
    }
  } else {
    $log .= "<b>Error: </b> Start Integration Batch could not be completed.<br/>";
  }

  /*
  * Step 1 : InventoryTransactionsChanges - Updating bLoyal inventory with current Woocommerce state...
  * POST api/v4/{accessKey}/InventoryTransactions/Changes
  */
  $sent_push_noticication_email = TRUE;
  $pending_to_push_inventory_changes = _get_pending_to_push_inventory_changes();
  $count_pending_to_push_inventory_changes = count($pending_to_push_inventory_changes);
  $log .= "<b>Notice:</b> Amount of Product Inventory Changes that will be pushed to bLoyal since the last sync: $count_pending_to_push_inventory_changes <br/>";
  if (count($count_pending_to_push_inventory_changes) > 0) {
    $url = "https://{$LoginDomain}-grid.bloyal.com/api/v4/{$accessKey}/InventoryTransactions/Changes";
    foreach ($pending_to_push_inventory_changes as $key => $EntityChange) {
      if (isset($EntityChange->EntityUid) && isset($EntityChange->Entity->ProductUid) && isset($EntityChange->Entity->ProductCode)) {
        $sku = $EntityChange->Entity->ProductCode;
        $option_name = $EntityChange->option_name;
        $note = $EntityChange->Note;

        unset($EntityChange->Note);
        unset($EntityChange->option_name);

        $EntityChange->Entity->ProductUid = NULL;
        $EntityChange->EntityUid = NULL;
        $EntityChange->Entity->Uid = NULL;
        $EntityChange->Entity->OrderUid = NULL;

        $params = array($EntityChange);
        $pushedChange = curl_post($params, $url);
        $response = json_decode($pushedChange);

        if ($response->status == "success") {
          $log .= $note . "<br/>";
          delete_option($option_name);
        } else {
          $log .= "<b>Error: </b> Push change could not be completed for the sku: $sku.<br/>";
          /*
          die(var_dump(array(
              'params' => $params,
              'response' => $response,
          )));
          */
        }

        // Send log mail
        if ($sent_push_noticication_email) {
          $msg = "<pre>" . json_encode($params) . "</pre><br/><br/>";
          $msg .= "<pre>" . json_encode($response) . "</pre><br/>";
          $headers = array('Content-Type: text/html; charset=UTF-8');
          // , 'melanie@thinkcreativegroup.com'
          wp_mail(array('adrian.morelos@akendos.com'), 'WooCommerce - Push inventory changes Debug', $msg, $headers);
        }

      } else {

        unset($EntityChange->Note);
        $log .= "<b>Error: </b> The following Entity Change does not have the correct format for the push to bloyal:<br/>";
        $log .= "<small><b>" . json_encode($EntityChange) . "</b></small><br/>";

      }
    }
  }

  /*
  * Step 2 : Get AvailableInventory/Changes
  * GET api/v4/{accessKey}/AvailableInventory/{storeCode}/Changes
  */
  $availableInventoryChangesToAcks = array();
  if ($startIntegrationBatch) {
    $url = "https://{$LoginDomain}-grid.bloyal.com/api/v4/{$accessKey}/AvailableInventory/{$storeCode}/Changes";
    $availableInventoryChanges = curl_get($url);
    $response_json_decode = json_decode($availableInventoryChanges);
    $response = (array) $response_json_decode;
    // $log .= "AvailableInventory: <br/><pre>" . $availableInventoryChanges . "</pre><br/>";
    if ($response["status"] == "success") {
      $count = count($response["data"]);
      $log .= "<b>Notice:</b> Amount of Product Inventory that has been changed on bLoyal since the last sync: $count <br/>";
      if ($count > 0) {
        $newbLoyalInventoryEntityProducts = array();
        $normalizebloyalinventory = normalizeBloyalInventory($response["data"]);
        $newbLoyalInventoryEntityProducts = $normalizebloyalinventory["entity_format"];
        $newbLoyalInventoryProducts = $normalizebloyalinventory["quantity_format"];
        $oldbLoyalInventoryProducts = get_option('bloyal_inventory');
        $oldbLoyalInventoryProductsEntities = get_option('bloyal_inventory_entities');

        if (!is_array($oldbLoyalInventoryProductsEntities)) {
          $oldbLoyalInventoryProductsEntities = array();
        }

        /*
        die(var_dump(array(
            "oldbLoyalInventoryProducts" => $oldbLoyalInventoryProducts,
            "oldbLoyalInventoryProductsEntities" => $oldbLoyalInventoryProductsEntities,
        )));
        */

        // Get woocommerce inventory for corresponding skus
        $result = _get_woocommerce_inventory($newbLoyalInventoryProducts);
        $woocommerce_inventory = $result["woocommerce_inventory"];
        $log .= $result["log"];
        // Not handle products on woocommerce of bloyal
        $notHandlebLoyalInventoryProducts = array_diff_key($newbLoyalInventoryProducts, $woocommerce_inventory);

        // Only handle the products found in woocommerce
        $newbLoyalInventoryProducts = array_intersect_key($newbLoyalInventoryProducts, $woocommerce_inventory);

        $log .= "<b>Amount of products sync between bLoyal - WooCommerce: </b>" . count($newbLoyalInventoryProducts) . "<br/>";
        $log .= "<b>Amount of products found in bLoyal but not in WooCommerce: </b>" . count($notHandlebLoyalInventoryProducts) . "<br/>";
        foreach ($notHandlebLoyalInventoryProducts as $key => $item) {
          $log .= "<b> -- Product found in bLoyal but not in WooCommerce: </b>" . $key . "<br/>";
        }

        // Update woocommerce inventory with the diff from two previous woo inventories
        foreach ($newbLoyalInventoryProducts as $sku => $quantity) {
          $new_quantity = intval($quantity);
          $old_quantity = 0;
          if (isset($oldbLoyalInventoryProducts[$sku])) {
            $old_quantity = intval($oldbLoyalInventoryProducts[$sku]);
          }
          // We never add stock through WooCommerce
          // How much the inventory has changed
          $change = $new_quantity - $old_quantity;
          if ($change != 0) {
            $product = _woocommerce_get_product_by_sku($sku);
            if ($product != null) {
              // Quantity has changed for this item
              if ($change > 0) {
                // Positive changes because bloyal add new stock.
                // update value
                //update_post_meta($product->id, '_stock_status', 'instock');
                update_post_meta($product->id, '_manage_stock', 'yes');
                $product->set_stock(intval($woocommerce_inventory[$sku] + $change));
                //$product->check_stock_status();
                $log .= "Increased local stock for product $sku by " . $change . "<br/>";
              } else if (0 > $change) {
                // Negative change for purchases that come from bloyal
                // update value
                //update_post_meta($product->id, '_stock_status', 'instock');
                update_post_meta($product->id, '_manage_stock', 'yes');
                $product->set_stock(intval($woocommerce_inventory[$sku] + $change));
                //$product->check_stock_status();
                $log .= "Decreased local stock for product $sku by " . (-$change) . "<br/>";
              }
              if ($sku == 79781727800) {
                $log .= "<b>" . $sku . " - New_quantity: " . $new_quantity . ". Old_quantity: " . $old_quantity . ". Change: " . $change . "<b/><br/>";
              }
            }
          } else {
            $log .= "No change detected for product $sku. WooCommerce Stock: " . intval($woocommerce_inventory[$sku]) . " | bLoyal Stock: " . $new_quantity . "<br/>";
          }
          // Add the Change to Ack
          if (isset($newbLoyalInventoryEntityProducts[$sku]->EntityUid)) {
            $entity = new stdClass;
            $entity->EntityUid = $newbLoyalInventoryEntityProducts[$sku]->EntityUid;
            $entity->Status = 1; // (Pending, Success, or Failure)
            $entity->ExternalId = null;
            $entity->Error = null;
            $availableInventoryChangesToAcks[] = $entity;
          }

          // Add the change to the Format entity for store
          $oldbLoyalInventoryProductsEntities[$sku] = $newbLoyalInventoryEntityProducts[$sku];
        }

        // Not handle products on woocommerce of bloyal for Ack changes(Do not acknowledgment this products just skip).
        $goAcknowledgeChangesInNotFoundProducs = TRUE;
        if($goAcknowledgeChangesInNotFoundProducs){
          foreach ($notHandlebLoyalInventoryProducts as $sku => $quantity) {
            // Add the Change to Ack
            if (isset($newbLoyalInventoryEntityProducts[$sku]->EntityUid)) {
              $entity = new stdClass;
              $entity->EntityUid = $newbLoyalInventoryEntityProducts[$sku]->EntityUid;
              $entity->Status = 1; // (Pending, Success, or Failure)
              $entity->ExternalId = null;
              $entity->Error = null;
              $availableInventoryChangesToAcks[] = $entity;
            }
          }
        }

        $all_sync_inventory = array_replace($newbLoyalInventoryProducts, $oldbLoyalInventoryProducts);
        $result = _get_woocommerce_inventory($all_sync_inventory);
        $newbLoyalInventoryProducts = $result["woocommerce_inventory"];
        $log .= $result["log"];

        // Save latest inventory to DB for comparison
        update_option('bloyal_inventory', $newbLoyalInventoryProducts);
        update_option('bloyal_inventory_entities', $oldbLoyalInventoryProductsEntities);

      }
    } else {
      $log .= "<b>Error: </b> Get AvailableInventory/Changes could not be completed.<br/>";
    }
  }

  /*
  * Step 3 : Post AvailableInventory/Changes/Acks -  Acknowledge the changes
  * POST api/v4/{accessKey}/AvailableInventory/{storeCode}/Changes/Acks
  */
  $goAcknowledgeChanges = TRUE;
  if ($goAcknowledgeChanges && count($availableInventoryChangesToAcks) > 0) {
    $url = "https://{$LoginDomain}-grid.bloyal.com/api/v4/{$accessKey}/AvailableInventory/{$storeCode}/Changes/Acks";
    $params = $availableInventoryChangesToAcks;
    $availableInventoryChangesAcks = curl_post($params, $url);
    $response = json_decode($availableInventoryChangesAcks);
    if ($response->status == "success") {
      $log .= "<b>Success Acknowledge the changes.</b><br/>";
    } else {
      $log .= "<b>Error: </b> Acknowledge the changes could not be completed.<br/>";
    }
  }

  /*
  * Step 4 : CloseIntegrationBatch() - Call at the end of your replication loop.
  * POST api/v4/{accessKey}/IntegrationBatches/Active
  */
  $batchMessage = "My custom integration batch complete message.";
  $customStateData = "You can log whatever state date you need for next batch iteration.";
  $line2 = "You'll get the custom data data back on the StartIntegrationBatch call.";
  $params = array(
    'LoginDomain' => $LoginDomain,
    'connectorKey' => $connectorKey,
    'apiKey' => $apiKey,
    'BatchMessage' => $batchMessage,
    'CustomStateData' => $customStateData
  );
  $url = "https://{$LoginDomain}-grid.bloyal.com/api/v4/{$accessKey}/IntegrationBatches/Active";
  $closeIntegrationBatch = curl_post($params, $url);
  $response = (array)json_decode($closeIntegrationBatch);
  if ($response["status"] == "success") {
    $result = $response["data"];
    $log .= "Integration Batch Closed.<br>";
    $log .= "<b>Title: </b> " . strval($result->Title) . "<br>";
    $log .= "<b>Status: </b> " . strval($result->Status) . "<br>";
    $log .= "<b>Message: </b> " . strval($result->Message) . "<br>";
    $log .= "<b>EntitySyncEvents: </b> " . strval($result->EntitySyncEvents) . "<br>";
  } else {
    $log .= "<b>Error: </b> Close Integration Batch could not be completed.<br/>";
  }

  // Send log mail
  if (!empty($log)) {
    $log = "<pre>" . $log . "</pre>";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    // , 'melanie@thinkcreativegroup.com'
    wp_mail(array('adrian.morelos@akendos.com'), 'WooCommerce - bLoyal Inventory Integration Debug', $log, $headers);
  }

  // Kill execution if called from ?do_sync
  if (isset($_GET['bloyal_do_sync'])) {
    echo "<h1>WooCommerce - bLoyal Inventory Integration Debug:</h1><pre>$log</pre>";
    die();
  }

}

/*
 * Run sync via GET parameters
 */
if (isset($_GET['bloyal_do_sync'])) {
  add_action('init', 'woo_bloyal_do_data_sync');
}


/*
* =============================
* Action Hook Functions
* =============================
*/

/**
 * Action Reduce stock levels for all line items in the order.
 * Runs if stock management is enabled, but can be disabled on per-order basis by extensions @since 2.4.0 via woocommerce_can_reduce_order_stock hook.
 */
function woo_bloyal_reduce_order_stock($order)
{
  global $LoginDomain, $accessKey, $apiKey, $storeCode, $connectorKey, $InventoryTransaction;
  $currentbLoyalInventoryProducts = get_option('bloyal_inventory_entities');
  foreach ($order->get_items() as $item) {
    if ($item['product_id'] > 0) {
      $_product = $order->get_product_from_item($item);
      if ($_product && $_product->exists() && $_product->managing_stock()) {

        $time = time();
        $qty = apply_filters('woocommerce_order_item_quantity', $item['qty'], $order, $item);
        $new_stock = $_product->get_stock_quantity();
        $sku = $_product->get_sku() ? $_product->get_sku() : $item['product_id'];

        $EntityChange = new stdClass;
        $EntityChange->EntityUid = NULL;
        $EntityChange->ChangeType = "Modified";
        $Entity = new stdClass;
        $Entity->MovementType = "SalesTransaction";
        $Entity->ProductUid = NULL;
        $Entity->ProductCode = $sku;
        $Entity->InventoryLocationCode = $InventoryTransaction;
        $Entity->Uid = NULL;
        $Entity->Quantity = -1 * intval($qty);
        $Entity->OrderUid = $order->id;

        if (isset($currentbLoyalInventoryProducts[$sku])) {
          if (isset($currentbLoyalInventoryProducts[$sku]->EntityUid)) {
            $EntityChange->EntityUid = $currentbLoyalInventoryProducts[$sku]->EntityUid;
          }
          if (isset($currentbLoyalInventoryProducts[$sku]->Entity->ProductUid)) {
            $Entity->ProductUid = $currentbLoyalInventoryProducts[$sku]->Entity->ProductUid;
          }
          if (isset($currentbLoyalInventoryProducts[$sku]->Entity->ProductCode)) {
            $Entity->ProductCode = $currentbLoyalInventoryProducts[$sku]->Entity->ProductCode;
          }
          if (isset($currentbLoyalInventoryProducts[$sku]->Entity->Uid)) {
            $Entity->Uid = $currentbLoyalInventoryProducts[$sku]->Entity->Uid;
          }
        }

        $EntityChange->Entity = $Entity;
        $note = sprintf(__('This Stock Change has been pushed to be bLoyal: Item %s stock reduced from %s to %s.', 'woocommerce'), $sku, $new_stock + $qty, $new_stock);
        $EntityChange->Note = $note;
        // Save Push Change for the Next Sync cron process
        update_option('bloyal_inventory_transactions_changes_' . $time . '_' . $sku, array($EntityChange));
        $order->add_order_note($note);

      }
    }
  }
}

/**
 * Action Increase order item stock.
 */
function woo_bloyal_restore_order_stock($order)
{
  global $LoginDomain, $accessKey, $apiKey, $storeCode, $connectorKey, $InventoryTransaction;
  $currentbLoyalInventoryProducts = get_option('bloyal_inventory_entities');
  $order_items = $order->get_items();
  $order_item_ids = isset($_POST['order_item_ids']) ? $_POST['order_item_ids'] : array();
  $order_item_qty = isset($_POST['order_item_qty']) ? $_POST['order_item_qty'] : array();
  foreach ($order_items as $item_id => $order_item) {
    // Only Increase checked items
    if (!in_array($item_id, $order_item_ids)) {
      continue;
    }
    $_product = $order->get_product_from_item($order_item);
    if ($_product->exists() && $_product->managing_stock() && isset($order_item_qty[$item_id]) && $order_item_qty[$item_id] > 0) {
      $time = time();
      $qty = $order_item_qty[$item_id];
      $new_quantity = $_product->get_stock_quantity();
      $old_stock = intval($new_quantity) - intval($qty);
      $sku = $_product->get_sku() ? $_product->get_sku() : $order_item['product_id'];
      $note = sprintf(__('This Stock Change has been pushed to be on bLoyal: Item %s stock increased from %s to %s.', 'woocommerce'), $sku, $old_stock, $new_quantity);

      $EntityChange = new stdClass;
      $EntityChange->EntityUid = NULL;
      $EntityChange->ChangeType = "Modified";
      $Entity = new stdClass;
      $Entity->MovementType = "SalesTransaction";
      $Entity->ProductUid = NULL;
      $Entity->ProductCode = $sku;
      $Entity->InventoryLocationCode = $InventoryTransaction;
      $Entity->Uid = NULL;
      $Entity->Quantity = intval($qty);
      $Entity->OrderUid = $order->id;

      if (isset($currentbLoyalInventoryProducts[$sku])) {
        if (isset($currentbLoyalInventoryProducts[$sku]->EntityUid)) {
          $EntityChange->EntityUid = $currentbLoyalInventoryProducts[$sku]->EntityUid;
        }
        if (isset($currentbLoyalInventoryProducts[$sku]->Entity->ProductUid)) {
          $Entity->ProductUid = $currentbLoyalInventoryProducts[$sku]->Entity->ProductUid;
        }
        if (isset($currentbLoyalInventoryProducts[$sku]->Entity->ProductCode)) {
          $Entity->ProductCode = $currentbLoyalInventoryProducts[$sku]->Entity->ProductCode;
        }
        if (isset($currentbLoyalInventoryProducts[$sku]->Entity->Uid)) {
          $Entity->Uid = $currentbLoyalInventoryProducts[$sku]->Entity->Uid;
        }
      }

      $EntityChange->Entity = $Entity;
      $EntityChange->Note = $note;
      // Save Push Change for the Next Sync cron process
      update_option('bloyal_inventory_transactions_changes_' . $time . '_' . $sku, array($EntityChange));
      $order->add_order_note($note);

    }
  }
}

/*
* Helper Functions
*/

/*
* POST Request with JSON data to API using CURL
*/
function curl_post($data, $url)
{
  $data_string = json_encode($data);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
  $result = curl_exec($ch);
  return $result;
}

/*
* GET Request to API using CURL
*/

function curl_get($url)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  return $result;
}

/*
* Normalize bLoyal Inventory to the right format
*/

function normalizeBloyalInventory($inventoryProducts)
{
  $bloyal_inventory = array();
  $by_quantity = array();
  $by_entity = array();
  foreach ($inventoryProducts as $key => $entityProduct) {
    if (isset($entityProduct->Entity->ProductCode)) {
      $entity = $entityProduct->Entity;
      $sku = $entity->ProductCode;
      $inventory = 0;
      if (isset($entityProduct->Entity->Available)) {
        $inventory = intval($entityProduct->Entity->Available);
      }
      $by_quantity[$sku] = $inventory;
      $by_entity[$sku] = $entityProduct;
    }
  }
  return array('quantity_format' => $by_quantity, 'entity_format' => $by_entity);
}

/*
*  Get woocommerce inventory for corresponding skus
*  Add Product on Woo if do not already exist and come from bLoyal
*/
function _get_woocommerce_inventory($skus)
{
  $log = "";
  // get woocommerce inventory for the corresponding items
  $woocommerce_inventory = array();
  foreach ($skus as $sku => $quantity) {
    $product = _woocommerce_get_product_by_sku($sku);
    if ($product) {
      $sku = strval($sku);
      $inventory = intval($product->get_stock_quantity());
      $woocommerce_inventory[$sku] = $inventory;
    } else {
      if ($quantity > 0) {
        $log .= "<b>Notice:</b> Product $sku found in bLoyal but not in Woocommerce - bLoyal quantity $quantity\n";
      }
    }
  }
  return array("woocommerce_inventory" => $woocommerce_inventory, "log" => $log);
}

/*
* Get all the current available Push changes
*  SELECT option_name FROM wp_options WHERE option_name  LIKE 'bloyal_inventory_transactions_changes%' 
*/
function _get_pending_to_push_inventory_changes()
{
  global $wpdb;
  $results = $wpdb->get_results("SELECT * FROM wp_options WHERE option_name LIKE 'bloyal_inventory_transactions_changes%'", OBJECT);
  $currentInventoryChanges = array();
  if (count($results) > 0) {
    foreach ($results as $key => $result) {
      $format_result = maybe_unserialize($result->option_value);
      if (isset($format_result[0])) {
        $format_result[0]->option_name = $result->option_name;
        $currentInventoryChanges[] = $format_result[0];
      }
    }
  }
  return $currentInventoryChanges;
}

/*
 * This returns WC_Product via sku
 */
function _woocommerce_get_product_by_sku($sku)
{
  global $wpdb;
  $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));
  if ($product_id) return new WC_Product($product_id);
  return null;
}

/*
*  Clean Duplicated products.
*/
function woo_clean_duplicated_products()
{
  $unique_sku = array();
  $duplicated_product_list = array();
  $full_product_list = array();
  $loop = new WP_Query(array('post_type' => array('product', 'product_variation'), 'posts_per_page' => -1));
  while ($loop->have_posts()) : $loop->the_post();
    $theid = get_the_ID();
    $product = new WC_Product($theid);
    if (get_post_type() == 'product_variation') {
      // its a simple product

    } else {
      $sku = get_post_meta($theid, '_sku', true);
      $thetitle = get_the_title();
    }
    // add product to array but don't add the parent of product variations
    if (!empty($sku)) {
      // Producto duplicado
      if (isset($duplicated_product_list[$sku])) {
        $tmp = $duplicated_product_list[$sku];
        $tmp[] = $theid;
        sort($tmp);
        $duplicated_product_list[$sku] = $tmp;
      } else {
        $duplicated_product_list[$sku] = array($theid);
      }
      $full_product_list[] = array($thetitle, $sku, $theid);
    }
  endwhile;
  wp_reset_query();

  $to_remove = array();

  foreach ($duplicated_product_list as $sku => $item) {
    if (count($item) > 1) {
      $most_updated = array_pop($item);
      $to_remove = array_merge($to_remove, $item);
    } else {
      unset($duplicated_product_list[$sku]);
    }
  }
  dpm($full_product_list);
  // Show Duplicated:
  dpm($duplicated_product_list);
  dpm($to_remove);

  // Deleting
  foreach ($to_remove as $key => $id) {
    # code...
    //wp_delete_post( $id, $force_delete = true );
  }
}


/*
 * Run sync via GET parameters
 */
if (isset($_GET['clean_duplicated_products'])) {
  add_action('init', 'woo_clean_duplicated_products');
}

