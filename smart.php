<?php 
/*
 * Plugin Name: Smart plugin
 * Description: this is custom data
 * version: 1.0
 * Author: Tushar
 * Plugin URI: http://localhost/wp-tus
 * Author URI: http://localhost/wp-tus
 * */
 
 
 function wp_custom_style_adding(){
		wp_enqueue_style('custom',get_stylesheet_directory_uri() . '/cs/custom-style.css',array(),'2.0','all');
		wp_enqueue_script('custom-js',get_stylesheet_directory_uri() .'/js/custom.js',array('jquery'),'1.0','all');
	}
add_action( 'wp_enqueue_scripts', 'wp_custom_style_adding' );
 
 function crunchify_deals_custom_post_type() {
	$labels = array(
		'name'                => __( 'Deals' ),
		'singular_name'       => __( 'Deal'),
		'menu_name'           => __( 'Deals'),
		'parent_item_colon'   => __( 'Parent Deal'),
		'all_items'           => __( 'All Deals'),
		'view_item'           => __( 'View Deal'),
		'add_new_item'        => __( 'Add New Deal'),
		'add_new'             => __( 'Add New'),
		'edit_item'           => __( 'Edit Deal'),
		'update_item'         => __( 'Update Deal'),
		'search_items'        => __( 'Search Deal'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash')
	);
	$args = array(
		'label'               => __( 'deals'),
		'description'         => __( 'Best Crunchify Deals'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields'),
		'public'              => true,
		'hierarchical'        => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'has_archive'         => true,
		'can_export'          => true,
		'exclude_from_search' => false,
	        'yarpp_support'       => true,
		'taxonomies' 	      => array('post_tag'),
		'publicly_queryable'  => true,
		'capability_type'     => 'page'
);
	register_post_type( 'deals', $args );
	 register_taxonomy( 'categories', array('deals'), array(
        'hierarchical' => true, 
        'label' => 'Categories', 
        'singular_label' => 'Category', 
        'rewrite' => array( 'slug' => 'categories', 'with_front'=> false )
        )
    );

    register_taxonomy_for_object_type( 'categories', 'deals' );
}
add_action( 'init', 'crunchify_deals_custom_post_type', 0 );


// Let us create Taxonomy for Custom Post Type
add_action( 'init', 'crunchify_create_deals_custom_taxonomy', 0 );
 
//create a custom taxonomy name it "type" for your posts
function crunchify_create_deals_custom_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Types', 'taxonomy general name' ),
    'singular_name' => _x( 'Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Types' ),
    'all_items' => __( 'All Types' ),
    'parent_item' => __( 'Parent Type' ),
    'parent_item_colon' => __( 'Parent Type:' ),
    'edit_item' => __( 'Edit Type' ), 
    'update_item' => __( 'Update Type' ),
    'add_new_item' => __( 'Add New Type' ),
    'new_item_name' => __( 'New Type Name' ),
    'menu_name' => __( 'Types' ),
  ); 	
 
  register_taxonomy('types',array('deals'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'type' ),
  ));
}

add_shortcode('testing','view_postss');
function view_postss(){
	
  $taxonomy = 'types';
  $tax_terms = get_terms($taxonomy);
  
?>
<ul>
  <?php
  foreach ($tax_terms as $tax_terming){
	  //~ echo '<pre>';
		//~ print_r($tax_terming);
	  //~ echo '</pre>';
	 ?>
	 <li id='cat-<?php echo $tax_terming->term_id; ?>'>
	 <a href="#<?php //echo esc_attr(get_term_link($tax_term, $taxonomy)); ?>" class="<?php echo $tax_terming->slug; ?> ajax" onclick="cat_ajax_get('<?php echo $tax_terming->term_id; ?>');" title="<?php echo $tax_termimg->name;?>">
		<?php echo $tax_terming->name; ?></li>
	</a>
	 <?php
   } ?>
</ul>

<script>
function cat_ajax_get(catID) {
    jQuery("a.ajax").removeClass("current");
    jQuery("a.ajax").addClass("current"); //adds class current to the category menu item being displayed so you can style it with css
    jQuery("#loading-animation-2").show();
    var ajaxurl = '/wp-admin/admin-ajax.php';
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "load-filter", cat: catID },
        success: function(response) {
            jQuery("#category-post-content").html(response);
            jQuery("#loading-animation").hide();
            return false;
        }
    });
}
</script>
<?php
}

 add_action( 'wp_ajax_nopriv_load-filter', 'prefix_load_cat_posts' );
add_action( 'wp_ajax_load-filter', 'prefix_load_cat_posts' );
function prefix_load_cat_posts () {
	$cat_id = $_POST[ 'category' ];
   $args = array (
    'tax_query' => array(
         array(
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => array( $cat_id )
         )
    ),
    'post_type' => 'deals', // <== this was missing
    'posts_per_page' => 10,
    'order' => 'DESC'
);
    $posts = get_posts( $args );

    ob_start ();

    foreach ( $posts as $post ) { ?>

    <div id="post-<?php echo $post->ID; ?>">
        <h1 class="posttitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

        <div id="post-content">
        <?php the_excerpt(); ?>

        </div>
   </div> 


   <?php } wp_reset_postdata();

   $response = ob_get_contents();
   ob_end_clean();

   echo $response;
   die(1);
}

//~ function menu_function($atts, $content = null) {
	//~ extract(
		//~ shortcode_atts(array( 'name' => null, ),$atts)
	//~ );
	//~ return wp_nav_menu(
		//~ array(
		//~ 'menu' => 'my-menu',
		//~ 'echo' => false
		//~ )
	//~ );
//~ }
//~ add_shortcode('menu', 'menu_function');

	
?>




