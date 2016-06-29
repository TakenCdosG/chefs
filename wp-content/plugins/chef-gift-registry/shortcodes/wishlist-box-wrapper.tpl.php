<form id="wishslist_entry_form" action="<?php admin_url( 'admin-ajax.php' ) ?>" method="post" class="woocommerce">
	<input type="hidden" name="action" value="chef_gift_registry_add_action">
	<input type="hidden" class="user" name="u" value="<?php echo absint( $_POST['user'] )?>">
	<ul>
		<?php wp_nonce_field( 'add_to_wishlist' ); ?>
		<li id="wishlist_new_li">
			<h3 class="wishlist_h3_title"> <?php _e( 'Add New Registry', 'ignitewoo-wishlists-pro' ) ?></h3>
			<div class="wishlist_new_wrap" style="display:block">
				<?php
				    $field_wishlist_type_key = "field_575726e432f3c";
				    $field_wishlist_type = get_field_object($field_wishlist_type_key);
				?>
				<div class="col-md-12">   
				   <div class="form-group form-group-event-type"">
				   	 <?php if( $field_wishlist_type ): ?>
				   		<label for="event-type">Event Type</label>
						<select name="event-type" id="event-type"s class="event-type">
							<option value="">- Select a value -</option>
						    <?php foreach( $field_wishlist_type['choices'] as $k => $v ): ?>
						    	<option value="<?php echo $k; ?>" <?php if($event_type == $k):?> selected <?php endif; ?> ><?php echo $v; ?></option>
						    <?php endforeach; ?>
						</select>
					<?php endif; ?>
				   </div>
					<!-- /input-group -->
				</div>

				<div class="col-md-12">   
				   <div class="form-group">
				     <label for="event-date">Event Date</label>
				     <input type="text" class="form-control" name="event-date" id="event-date-inside-modal" placeholder=""  value="">
				   </div>
					<!-- /input-group -->
				</div>

				<div class="col-md-12">   
				   <div class="form-group">
						<label class="wishlist_field_label"><?php _e( 'Wishlist Title', 'ignitewoo-wishlists-pro' ) ?></label>
						<input id="wishlist_title_field" type="text" name="wishlist_title" value="" size="45"> 
				   </div>
					<!-- /input-group -->
				</div>

				<div class="col-md-12">   
				   <div class="form-group" style="margin-top: 10px;">
				     <label for="co-registrant-name">Co-Registrant Name</label>
				     <input type="text" class="form-control" style="margin: 0 0 0px" name="co-registrant-name" id="co-registrant-name" placeholder="" value="">
				   </div>
					<!-- /input-group -->
				</div>

				<div class="col-md-12">   
				   <div class="form-group" style="margin-bottom: 2px; margin-top: 10px;">
				     <label for="co-registrant-email">Co-Registrant Email</label>
				     <input type="email" style="margin-bottom: 2px; " class="form-control" name="co-registrant-email" id="co-registrant-email" placeholder="" value="">
				   </div>
					<!-- /input-group -->
				</div>

				<div class="col-md-12">   
				   <div class="form-group" style="margin-top: 14px;"> 
						<div class="wishlist_type_label">
							<?php _e( 'Registry Type:', 'ignitewoo-wishlists-pro' ) ?></br>
						</div>
						<?php $i = 0; ?>

						<?php foreach ( $wishlist_types as $w ) { ?>
								<?php 
								if ( !is_user_logged_in() && 'private' == strtolower( $w->name ) ) 
									continue;
								?>	
							<?php $i++ ?>

							<label class="wishlist_field_label wishlist_type_btn"><input type="radio" class="wishlist_radio_btn" name="wishlist_num" value="<?php echo $w->term_id ?>" <?php if ( 3== $i ) echo 'checked="checked"';?> > <?php echo $w->name ?> (<em><?php echo str_replace("wishlist", "registry", $w->description); ?></em>)</label>

						<?php } ?>
					</div>
					<!-- /input-group -->
				</div>
			</div>
			<button id="wishlist_add_button" class="button" type="button"><?php _e( 'Submit', 'ignitewoo-wishlists-pro' )?></button>
		</li>
	</ul>
</form>