<?php
/*
 * Template name: postdesign-template
 * */
 
 get_header();
 
 ?>
 <div class='container'>
	 <ul class='category-filters'>
		<?php 

		wp_list_categories ( [ 'taxonomy' => 'types' ] );
		wp_list_categories ( [ 
		'show_option_all' => 'All',
		'taxonomy' => 'categories' ,
		'exclude' => '1',
		'title_li' => __('')
							
		] );


		?>
	 </ul>
 </div>
 <?php
 //~ $condition = array(
	//~ 'post_type' => 'deals',
	//~ 'post_status' => 'publish'
 //~ );	//condition parameter
 
 $my_query = new WP_Query(array(
	'post_type' => 'deals',
	'post_status' => 'publish',
	'tax_query' => array(
		array(
			'taxonomy' => 'categories',
			'field' => 'term_id',
			'terms' => array(8),
			'include_children' => true,
			'operator' => 'IN'
			
		)
	)
 ));	//creating wp_query instance
 
 if($my_query -> have_posts()){	//checking if post there or not
		
		while($my_query -> have_posts()){	//loop through all posts
			$my_query -> the_post();	//increment the loop
			//$term = get_term_by( 'term_id', get_query_var( 'types' ) ); 
			echo '<div id="inside" class="the-news main-content" style="text-align:center;">';
			echo '<h3>'; the_title();
			echo '</h3>';
			echo '<p>';
				the_content();
			echo '</p>';
			
			echo '<strong> Category : </strong>';
				$terms = get_the_terms( $post->ID , 'types' );

				foreach ( $terms as $term ) {

				echo $term->name;

				}
				the_category();
			echo '</div>';
			echo '<hr>';
			
		}
}
 
 get_footer();
 
 
?>
