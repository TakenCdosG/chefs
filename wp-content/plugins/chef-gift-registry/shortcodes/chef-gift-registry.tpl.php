<?php $is_user_logged_in = is_user_logged_in(); ?>
<div class="chef-gift-registry" id="chef-gift-registry-wrapper">
	<div class="options-link">
		<button type="submit" class="btn default <?php if($is_user_logged_in): ?>new-gift-registry<?php endif; ?>" <?php if(!$is_user_logged_in): ?> id="show_login" <?php endif; ?>>New Gift Registry</button>
		<a id="wishlist_add_hidden_link" href="#wishlist_box_wrapper" data-rel="prettyPhoto" style="display:none"></a>
		<div id="wishlist_box_wrapper" style="display:none"></div>
	</div>
	<form class="form" method="post">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
			   		<label for="registrant-name">Registrant Name</label>
			    	<input type="text" class="form-control" name="registrant-name" id="registrant-name" placeholder="" value="<?php echo $registrant_name ; ?>">
		  		</div>
				<!-- /input-group -->
			</div>
			<!-- /.col-md-6 -->
			<div class="col-md-6">   
			   <div class="form-group">
			     <label for="co-registrant-name">Co-Registrant Name</label>
			     <input type="text" class="form-control" name="co-registrant-name" id="co-registrant-name" placeholder="" value="<?php echo $co_registrant_name ; ?>">
			   </div>
				<!-- /input-group -->
			</div>
			<!-- /.col-md-6 -->
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
			   		<label for="registrant-email">Registrant Email</label>
			    	<input type="email" class="form-control" name="registrant-email" id="registrant-email" placeholder=""  value="<?php echo $registrant_email ; ?>">
		  		</div>
				<!-- /input-group -->
			</div>
			<!-- /.col-md-6 -->
			<div class="col-md-6">   
			   <div class="form-group">
			     <label for="co-registrant-email">Co-Registrant Email</label>
			     <input type="email" class="form-control" name="co-registrant-email" id="co-registrant-email" placeholder="" value="<?php echo $co_registrant_email ; ?>">
			   </div>
				<!-- /input-group -->
			</div>
			<!-- /.col-md-6 -->
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
			   		<label for="event-name">Event Name</label>
			    	<input type="text" class="form-control" id="event-name" name="event-name" placeholder=""  value="<?php echo $event_name ; ?>">
		  		</div>
				<!-- /input-group -->
			</div>
			<!-- /.col-md-6 -->
			<div class="col-md-6">   
			   <div class="form-group">
			   	 <?php if( $field_wishlist_type ): ?>
			   		<label for="event-type">Event Type</label>
					<select name="event-type" id="event-type"s class="event-type">
						<option value="_none">- Select a value -</option>
					    <?php foreach( $field_wishlist_type['choices'] as $k => $v ): ?>
					    	<option value="<?php echo $k; ?>" <?php if($event_type == $k):?> selected <?php endif; ?> ><?php echo $v; ?></option>
					    <?php endforeach; ?>
					</select>
				<?php endif; ?>
			   </div>
				<!-- /input-group -->
			</div>
			<!-- /.col-md-6 -->
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
			   		<label for="registry-no">Registry no.</label>
			    	<input type="text" class="form-control" name="registry-no" id="registry-no" placeholder=""  value="<?php echo $registry_no ; ?>">
		  		</div>
				<!-- /input-group -->
			</div>
			<!-- /.col-md-6 -->
			<div class="col-md-6">   
			   <div class="form-group">
			     <label for="event-date">Event Date</label>
			     <input type="text" class="form-control" name="event-date" id="event-date-inside-modal" placeholder=""  value="<?php echo $event_date ; ?>">
			   </div>
				<!-- /input-group -->
			</div>
			<!-- /.col-md-6 -->
		</div>

		<div class="form-actions">
			<button type="submit" class="btn default">Filter All Registries</button>
		</div>

	</form>
</div>