<?php
// include bLoyal PHP API
require_once '.config.inc.php';
require_once 'curl.php';
require_once 'helper.php';

global $LoginDomain, $accessKey, $apiKey, $storeCode;

$LoginDomain = BLOYAL_CLIENT_LOGIN_DOMAIN;
$accessKey = BLOYAL_ACCESS_KEY;
$apiKey = BLOYAL_API_KEY;
$connectorKey = BLOYAL_CONNECTOR_KEY;
$storeCode = BLOYAL_STORECODE;
$InventoryTransaction = BLOYAL_INVENTORY_TRANSACTION;

// Generating an Access Key

$params = array(
	'LoginDomain' => $LoginDomain,
	'connectorKey' => $connectorKey,
	'apiKey' => $apiKey,
	'InventoryTransaction' => $InventoryTransaction
);

// POST api/v4/{accessKey}/InventoryTransactions/Changes
$url = "https://{$LoginDomain}-grid.bloyal.com/api/v4/{$accessKey}/InventoryTransactions/Changes";
$InventoryTransactions = Curl::post($url, $params)->call();
var_dump( $InventoryTransactions->result );
var_dump( $InventoryTransactions->info );
var_dump( $InventoryTransactions->error );
var_dump( $InventoryTransactions->error_code );
// $url = "https://{$LoginDomain}-grid.bloyal.com/api/v4/KeyDispenser";
// $AccessKeyRequest = Curl::post($url, $params)->call();

// sync Inventory
// Inventory Resources
// Step 1 : StartIntegrationBatch() - Start a replication 'batch'
// $log_startIntegrationBatch = StartIntegrationBatch();
// echo $log_startIntegrationBatch;
// Step 2 : Get AvailableInventory/Changes
//-$log_getAvailableInventoryChanges = GetAvailableInventoryChanges();
//-echo $log_getAvailableInventoryChanges;
// Step 3 : Post AvailableInventory/Changes/Acks
//-$log_postAvailableInventoryChangesAcks = PostAvailableInventoryChangesAcks();
//-echo $log_postAvailableInventoryChangesAcks;
// Step 4: getInventoryLocations
//-$log_getInventoryLocations = getInventoryLocations();
//-echo $log_getInventoryLocations;
// Step 5 : CloseIntegrationBatch() - Call at the end of your replication loop.
// $log_closeIntegrationBatch = CloseIntegrationBatch();
// echo $log_closeIntegrationBatch;


