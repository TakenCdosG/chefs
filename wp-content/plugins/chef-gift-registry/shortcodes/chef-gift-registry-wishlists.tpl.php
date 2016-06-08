<br/>
<br/>
<?php if( $wishlists->have_posts() ): ?>
<table class="table table-bordered table-striped wishlists-result">
   <thead>
      <tr>
         <th>No.</th>
         <th>Registrant Name</th>
         <th>Co-Registrant Name</th>
         <th>Event Name / (Type)</th>
         <th>Registry # / Date</th>
         <th>Action</th>         
      </tr>
   </thead>
   <tbody>
		<?php while ( $wishlists->have_posts() ) : $wishlists->the_post(); ?>
			<?php $post = get_post(); ?>
		    <tr>
       		  <th scope="row">
       		  		<?php echo $post->ID; ?>
       		  </th>
       		  <td>
       		  	 <?php echo get_field('registrant_name', $post->ID); ?>
       		  </td>
       		  <td>
       		   <?php echo get_field('co-registrant_name', $post->ID); ?>
       		  </td>
       		   <td>
       		   <a href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
				</a>
       		  </td>
       		   <td>
       		   <?php echo get_field('event_date', $post->ID); ?>
       		  </td>
       		   <td>
	       		   <a href="<?php the_permalink(); ?>" class="btn">
						Buy
					</a>
       		  </td>
       		</tr>
	    <?php endwhile; ?>
   </tbody>
</table>
<?php endif; ?>

<?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>