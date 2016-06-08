<div class="chef-gift-registry" id="chef-gift-registry-wrapper">
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
			     <label for="event-type">Event Type</label>
				<select name="event-type" id="event-type" class="event-type">
				    <option value="baby_registry" <?php if($event_type == "baby_registry"):?> selected <?php endif; ?> >Baby Registry</option>
				    <option value="graduate_registry"  <?php if($event_type == "graduate_registry"):?> selected <?php endif; ?> >Graduate Registry</option>
				    <option value="wedding_registry"  <?php if($event_type == "wedding_registry"):?> selected <?php endif; ?> >Wedding Registry</option>
				    <option value="birthday_registry" <?php if($event_type == "birthday_registry"):?> selected <?php endif; ?> >Birthday Registry</option>
				    <option value="other_registry"  <?php if($event_type == "other_registry"):?> selected <?php endif; ?> >Other Registry</option>
				</select>
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
			     <input type="text" class="form-control" name="event-date" id="event-date" placeholder=""  value="<?php echo $event_date ; ?>">
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