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
         		  	 <?php the_field('field_57573db70d652', get_the_id()); ?>
         		  </td>
         		  <td>
         		   <?php the_field('field_57573dc70d653', get_the_id()); ?>
         		  </td>
         		  <td>
           		   <a href="<?php the_permalink(); ?>">
    					     <?php the_title(); ?>
    				     </a>
         		  </td>
         		  <td>
         		   <?php the_field('field_57573e110d656', get_the_id()); ?>
         		  </td>
         		   <td>
  	       		   <a href="<?php the_permalink(); ?>" class="btn"> Buy</a>
         		  </td>
         		</tr>
  	    <?php endwhile; ?>
     </tbody>
  </table>
<?php else: ?>
 <h4 class="no-results-found">No results found.</h4>
<?php endif; ?>

<?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>