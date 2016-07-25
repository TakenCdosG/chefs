<script type='text/javascript'>
  function maybe_buy_item_not_logged(id, vid, wlid, u, ul) {
    jQuery('#dialog-confirm span.name').html(ul);
    jQuery("#dialog-confirm").dialog({
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      position: { my: "center", at: "top+150" },
      buttons: {
        "Buy Now!": {
          click: function (event) {
            event.stopPropagation();
            var l = Ladda.create(document.querySelector('.buy-now-class'));
            <?php echo 'nonce = "' . wp_create_nonce  ( 'wishlist_nonce' ) . '";' ?>
            jQuery.ajax({
              type: "POST",
              dataType: 'json',
              cache: false,
              url: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
              data: {
                nonce: nonce,
                user: u,
                wlid: wlid,
                prod_id: id,
                var_id: vid,
                action: 'buy_wishlist_item_not_logged'
              },
              beforeSend: function () {
                l.start();
              },
              complete: function () {
                l.stop();
              },
              success: function (data) {
                console.log(data);
                if (data.success) {
                  //jQuery("#dialog-confirm").dialog("close");
                  window.location = data.url;
                } else {
                }
              }
            });
          },
          text: 'Buy Now!',
          class: 'buy-now-class'
        },
        Cancel: function () {
          jQuery(this).dialog("close");
        }
      },
      create: function(event, ui) {

      },
      beforeClose: function(event, ui) {
        jQuery("body").css({ overflow: 'inherit' });
      },
      open: function (event, ui) {
        jQuery("body").css({ overflow: 'hidden' });
        var l = Ladda.create(document.querySelector('.buy-now-class'));
        jQuery("button.buy-now-class").attr("data-color", "red").attr("data-style", "expand-right").attr("data-size", "xs");
      }
    });
    return false;
  }
</script>