<?php
global $woocom_wishlist, $woocommerce, $wishlist_items, $wishlist_posts, $post, $user_ID, $wpdb;
$wishlist_id = $post->ID; // ID of the wishlist post
$wishlist_owner = $post->post_author;
// Set The Current User
if (!$user_ID) {
  $uid = null;
} else {
  $uid = $user_ID;
}
?>
<?php if (isset($_GET['add-to-cart']) && !empty($_GET['add-to-cart'])): ?>
  <div class="wishlist_added_to_cart">
    <?php _e('The item was added to your cart. Be sure to check the cart quantity before checkout.', 'ignitewoo-wishlists-pro') ?>
  </div>
<?php endif; ?>

<?php if (isset($_POST['update_wishlist']) && isset($_POST['_wpnonce']) && isset($_POST['wishlist_qty'])): ?>
  <?php if (wp_verify_nonce($_POST['_wpnonce'], 'update_wishlist') && count($_POST['wishlist_qty']) > 0): ?>
    <?php
    $items = $_POST['wishlist_qty'];
    foreach ($items as $key => $qty) {
      $pids = explode('_', $key);
      if (count($pids) > 0) {
        for ($x = 0; $x < count($wishlist_items); $x++) {
          if ($wishlist_items[$x]['id'] == $pids[0] && $wishlist_items[$x] ['vid'] == $pids[1]) {
            $wishlist_items[$x] ['qty'] = $qty;
          }
        }
      }
    }
    update_post_meta($post->ID, 'wishlist_products', $wishlist_items);
    ?>
    <div class="woocommerce-message"><?php echo __('Wishlist updated', 'ignitewoo-wishlists-pro'); ?></div>
  <?php endif; ?>
<?php endif; ?>

<?php if ('private' == get_post_meta($post->ID, 'wishlist_type', false) && $uid != $post->post_author): ?>
  <!-- If the wishlist is private don't show any content -->
  <p class="wishlist_private_no_access"><?php _e('This wishlist is private.', 'ignitewoo-wishlists-pro'); ?></p>
<?php else: ?>
  <?php /* Wishlist is not private, so show the content*/ ?>
  <?php if (count($wishlist_items) > 0): ?>
    <?php if ($wishlist_owner == $uid): ?>
      <form action="" method="post">
      <?php wp_nonce_field('update_wishlist'); ?>
    <?php endif; ?>
    <p><input type="hidden" name="update_wishlist" value="1"></p>
    <table id="wishlist_table">
      <thead>
      <tr>
        <th></th>
        <th align="center"><?php echo __('Name', 'ignitewoo-wishlists-pro'); ?></th>
        <th align="center"><?php echo __('Price', 'ignitewoo-wishlists-pro'); ?></th>
        <th align="center"><?php echo __('Qty', 'ignitewoo-wishlists-pro'); ?></th>
        <th align="center"><?php echo __('Options', 'ignitewoo-wishlists-pro'); ?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($wishlist_items as $key => $wli): ?>
        <?php
        if (!empty($wli['vid'])) {
          $_product = $woocom_wishlist->get_product($wli['vid']);
        } else {
          $_product = $woocom_wishlist->get_product($wli['id']);
        }
        if (empty($_product)) {
          continue;
        }

        $post = get_post($wli['id']);
        $title = $post->post_title;
        $link = get_permalink($wli['id']);
        $purchased_by = false;
        if ($wli['id'] == $post->ID && '1' == $wli['purchased'] && $wli['purchased_by'] != '') {
          $purchased_by = $wli['purchased_by'];
        }

        if ($wli['id'] == $post->ID && '1' == $wli['purchased'] && '1' == $wli['show_purchased_by_name']) {
          $show_purchased_by = true;
        } else {
          $show_purchased_by = false;
        }

        if (isset($wli['qty'])) {
          $qty = $wli['qty'];
        } else {
          $qty = 1;
        }

        if (isset($wli['purchased_by']) && count($wli['purchased_by']) > 0) {
          $qty_purchased = 0;
          if (!empty($wli['purchased_by']))
            foreach ($wli['purchased_by'] as $pb) {
              $qty_purchased = absint($pb['qty']) + $qty_purchased;
            }
        } else {
          $qty_purchased = 0;
        }

        $qty_needed = ($qty - $qty_purchased);
        $price = $_product->get_price();
        $type = '';
        $value = '';
        $variation_info = '';
        $variation_list = array();

        if (is_array($_product->variation_data) && count($_product->variation_data) > 0) {
          foreach ($_product->variation_data as $aname => $value) {
            if (empty($value)) {
              continue;
            }

            // If this is a term slug, get the term's nice name
            if (taxonomy_exists(esc_attr(str_replace('attribute_', '', $aname)))) {
              $term = get_term_by('slug', $value, esc_attr(str_replace('attribute_', '', $aname)));
              if (!is_wp_error($term) && $term->name)
                $value = $term->name;
            } else {
              $value = ucfirst($value);
            }

            if (function_exists('wc_attribute_label')) {
              $variation_list[] = '<br/>' . strtoupper(wc_attribute_label(str_replace('attribute_', '', $aname))) . ': ' . $value;
            } else {
              $variation_list[] = '<br/>' . strtoupper($woocommerce->attribute_label(str_replace('attribute_', '', $aname))) . ': ' . $value;
            }

          }
        }

        if (count($variation_list) > 0) {
          $variation_info = implode('', $variation_list);
        }

        // don't show products that admins have been marked as hidden
        if ('visible' != $_product->visibility) {
          continue;
        }

        $tag = $wli['id'];
        if (!empty($wli['vid'])) {
          $tag .= '_' . $wli['vid'];
          $item_id = $wli['vid'];
        } else {
          $tag .= '_0';
          $item_id = $wli['id'];
        }
        ?>
        <tr id="wishlist_product_<?php echo $tag ?>" class="wishlist_list_item">

          <td class="image_thumb">
            <div class="images">
              <a itemprop="image" href="<?php echo wp_get_attachment_url(get_post_thumbnail_id($item_id)); ?>"
                 class="wishlist_thumb zoom" rel="thumbnails"
                 title="<?php echo get_the_title(get_post_thumbnail_id($item_id)); ?>">
                <?php
                $thumb = get_the_post_thumbnail($item_id, 'shop_thumbnail');
                // Might be a variation, so if there is no thumb try the main product
                if (empty($thumb)) {
                  $thumb = get_the_post_thumbnail($wli['id'], 'shop_thumbnail');
                }
                echo $thumb;
                ?>
              </a>
            </div>
          </td>
          <td class="name_type">
            <?php
            if (!$_product->is_visible() || ($_product instanceof WC_Product_Variation && !$_product->parent_is_visible())) {
              echo $_product->get_title();
            } else {
              printf('<a href="%s">%s</a>', esc_url(get_permalink(apply_filters('woocommerce_in_cart_product_id', $wli['id']))), $_product->get_title());
            }
            ?>
            <?php if (!empty($variation_info)) echo $variation_info ?>
          </td>
          <td class="product_price"><?php echo woocommerce_price($price); ?></td>
          <?php
          if ($wishlist_owner == $uid) {
            $class = "quantity buttons added";
          } else {
            $class = "";
          }
          ?>

          <td class="<?php echo $class ?> product_qty">
            <?php if ($qty_needed > 0): ?>
              <?php if ($wishlist_owner == $uid): ?>
                <input name="wishlist_qty[<?php echo $tag ?>]" type="number" class="qty"
                       value="<?php echo $qty_needed; ?>">
              <?php else: ?>
                <?php echo $qty_needed; ?>
              <?php endif; ?>
            <?php endif; ?>
          </td>
          <?php if (null !== $uid): ?>
            <?php if ($wishlist_owner == $uid && $purchased_by): ?>
              <?php if ($show_purchased_by): ?>
                <td class="purchaser">
                  <?php _e('Purchased by: ', 'ignitewoo-wishlists-pro') ?>
                  <?php foreach ($purchased_by as $pb): ?>
                    <?php
                    $name = $pb['qty'] . ' ' . __('by', 'ignitewoo-wishlists-pro') . ' ';
                    $name .= get_user_meta($pb['user'], 'billing_first_name', true);
                    $name .= ' ' . get_user_meta($pb['user'], 'billing_last_name', true);
                    ?>
                    <p class="wishlist_purchaser"><?php echo $name; ?></p>
                  <?php endforeach; ?>
                  <?php
                  if (empty($wli['vid'])) {
                    echo do_shortcode('[add_to_cart id="' . $wli['id'] . '"]');
                  } else {
                    echo do_shortcode('[add_to_cart id="' . $wli['vid'] . '"]');
                  }
                  ?>
                </td>
              <?php else: ?>
                <td class="purchaser">
                  <?php
                  foreach ($purchased_by as $pb) {
                    $name = $pb['qty'] . ' ' . __('by Anonymous', 'ignitewoo-wishlists-pro') . ' ';
                  }
                  ?>
                  <?php
                  if (empty($wli['vid'])) {
                    echo do_shortcode('[add_to_cart id="' . $wli['id'] . '"]');
                  } else {
                    echo do_shortcode('[add_to_cart id="' . $wli['vid'] . '"]');
                  }
                  ?>
                </td>
              <?php endif; ?>
            <?php elseif ($wishlist_owner == $uid): ?>
              <td class="remove">
                <div class="registry-options">
                  <?php
                  if (empty($wli['vid'])) {
                    echo do_shortcode('[add_to_cart id="' . $wli['id'] . '"]');
                  } else {
                    echo do_shortcode('[add_to_cart id="' . $wli['vid'] . '"]');
                  }
                  ?>
                  <a class="wishlist_remove"
                     onclick="return maybe_remove_wishlist_item(<?php echo $_product->id . ',\'' . $_product->variation_id . '\',' . $wishlist_id ?>)"
                     href="#"
                     title=" <?php _e('Remove item from wishlist', 'ignitewoo-wishlists-pro') ?> "><?php _e('Remove', 'ignitewoo-wishlists-pro') ?>
                  </a>
                  <?php if (!$_product->is_in_stock()): ?>
                    <span class="span-info"><?php _e('Currently Out of Stock.', 'ignitewoo-wishlists-pro') ?></span>
                  <?php endif; ?>
                </div>
              </td>
            <?php else: ?>
              <?php if ($qty_needed > 0): ?>
                <?php
                $u = get_userdata($wishlist_owner);
                $name = get_user_meta($wishlist_owner, 'billing_first_name', true);
                ?>
                <?php if ($_product->is_in_stock()): ?>
                  <td class="purchase">
                    <a class="wishlist_buy_item"
                       onclick="return maybe_buy_item_not_logged(<?php echo $_product->id . ',\'' . $_product->variation_id . '\',' . $wishlist_id . ',' . $u->ID . ',\'' . $u->user_login ?>')"
                       href="#"
                       title=" <?php _e('Buy this item for the wishlist owner.', 'ignitewoo-wishlists-pro') ?> "><?php _e('Add to Cart', 'ignitewoo-wishlists-pro') ?>
                    </a>
                  </td>
                <?php else: ?>
                  <td
                    class="purchase">
                    <span><?php _e('Currently Out of Stock.', 'ignitewoo-wishlists-pro') ?></span></td>
                <?php endif; ?>
              <?php else: ?>
                <td class="purchased">
                  <?php if ($qty_needed <= 0): ?>
                    <?php _e('Already purchased', 'ignitewoo-wishlists-pro') ?>
                  <?php endif; ?>
                </td>
              <?php endif; ?>
            <?php endif; ?>
          <?php else: ?>
            <!-- Allow to not logued users to add to the Cart -->
            <?php if ($qty_needed > 0): ?>
              <?php
              $u = get_userdata($wishlist_owner);
              $name = get_user_meta($wishlist_owner, 'billing_first_name', true);
              ?>
              <?php if ($_product->is_in_stock()): ?>
                <td class="purchase-not-logged">
                  <a class="wishlist_buy_item"
                     onclick="return maybe_buy_item_not_logged(<?php echo $_product->id . ',\'' . $_product->variation_id . '\',' . $wishlist_id . ',' . $u->ID . ',\'' . $u->user_login ?>')"
                     href="#"
                     title=" <?php _e('Buy this item for the wishlist owner.', 'ignitewoo-wishlists-pro') ?> "><?php _e('Add to Cart', 'ignitewoo-wishlists-pro') ?>
                  </a>
                </td>
              <?php else: ?>
                <td
                  class="purchase-not-logged">
                  <span><?php _e('Currently Out of Stock.', 'ignitewoo-wishlists-pro') ?></span></td>
              <?php endif; ?>
            <?php else: ?>
              <td class="purchased">
                <?php if ($qty_needed <= 0): ?>
                  <?php _e('Already purchased!', 'ignitewoo-wishlists-pro') ?>
                <?php endif; ?>
              </td>
            <?php endif; ?>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
      <?php if ($wishlist_owner == $uid): ?>
        <tr>
          <td class="wishlist_update_td" colspan="5" align="right">
            <input class="button update_wishlist_button" type="submit" name="submit_wishlist"
                   value="<?php echo __('Update Wishlist', 'ignitewoo-wishlists-pro'); ?>">
          </td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
    <?php if ($wishlist_owner == $uid): ?>
      </form>
    <?php endif; ?>
    <div id="wishlist_social">
      <span><?php _e('Share this wishlist', 'ignitewoo-wishlists-pro') ?> : </span>
      <span class='st_sharethis'></span>
      <span class='st_facebook'></span>
      <span class='st_twitter'></span>
      <span class='st_linkedin'></span>
      <span class='st_googleplus'></span>
      <span class='st_pinterest'></span>
      <span class='st_email'></span>
      <script type="text/javascript">var switchTo5x = false;</script>
      <script type="text/javascript" src="//w.sharethis.com/button/buttons.js"></script>
      <script type="text/javascript">stLight.options({publisher: "b3c86b57-318e-4923-873d-4aead6aadc2e"}); </script>
    </div>
    <?php wp_reset_postdata(); ?>
  <?php else: ?>
    <p><?php _e('There are no items in this wishlist.', 'ignitewoo-wishlists-pro') ?></p>
  <?php endif; ?>
<?php endif; ?>

<div id="dialog-confirm" title="Buy this item for the registry owner." style="display:none;">
  <p>Are you sure you want to buy this item ?</p>
</div>

<div id="info-result" title="Buy this item for the registry owner." style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Are you sure you want to
    buy this item ?</p>
</div>


