<?php
/**
 * Plugin Name: Bean Testimonials
 * Plugin URI: http://themebeans.com
 * Description: Enables a testimonial post type.
 * Version: 1.0
 * Author: ThemeBeans
 *
 *
 * @package Bean Plugins
 * @subpackage BeanTestimonials
 * @author ThemeBeans
 * @since BeanTestimonials 1.0
 */


/*===================================================================*/
/* PLUGIN CLASS
/*===================================================================*/
if ( ! class_exists( 'Bean_Testimonial_Post_Type' ) ) :
class Bean_Testimonial_Post_Type
{
	function __construct()
	{
		// PLUGIN ACTIVATION
		register_activation_hook( __FILE__, array( &$this, 'plugin_activation' ) );

		// TRANSLATION
		load_plugin_textdomain( 'bean', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		add_action( 'init', array( &$this, 'theme_init' ) );

		add_action( 'admin_head', array( &$this, 'custom_post_type_icon' ) );
	}


	/*===================================================================*/
	/* FLUSH REWRITE RULE
	/*===================================================================*/
	function plugin_activation()
	{
		$this->theme_init();
		flush_rewrite_rules();
	}


	/*===================================================================*/
	/* REGISTER POST TYPE
	/*===================================================================*/
	function theme_init()
	{
		// REGISTER THE POST TYPE
		$labels = array(
			'name' 			 => __( 'Testimonials', 'bean' ),
			'singular_name' 	 => __( 'Testimonial', 'bean' ),
			'add_new' 		 => __( 'Add New', 'bean' ),
			'add_new_item'		 => __( 'Add New Testimonial', 'bean' ),
			'edit_item' 		 => __( 'Edit Testimonial', 'bean' ),
			'new_item' 		 => __( 'Add New', 'bean' ),
			'view_item' 		 => __( 'View Testimonial', 'bean' ),
			'search_items' 	 => __( 'Search Testimonials', 'bean' ),
			'not_found' 		 => __( 'No items found', 'bean' ),
			'not_found_in_trash' => __( 'No items found in trash', 'bean' )
		);

		$args = array(
	    		'labels' 			=> $labels,
	    		'public' 			=> true,
			'supports' 		=> array( 'title', 'editor', 'thumbnail'),
			'capability_type' 	=> 'post',
			'rewrite' 		=> array("slug" => "testimonial"),
			'menu_position' 	=> 20,
			'has_archive' 		=> false,
			'hierarchical' 	=> false,
			'menu_icon'	     => '',
			'show_in_nav_menus' => false,
		);

		$args = apply_filters('bean_args', $args);

		register_post_type( 'testimonial', $args );


		// REGISTER TAGS
		$taxonomy_theme_tag_labels = array(
			'name' 						=> __( 'Testimonial Tags', 'bean' ),
			'singular_name' 				=> __( 'Testimonial Tag', 'bean' ),
			'search_items' 				=> __( 'Search Testimonial Tags', 'bean' ),
			'popular_items' 				=> __( 'Popular Testimonial Tags', 'bean' ),
			'all_items' 					=> __( 'All Testimonial Tags', 'bean' ),
			'parent_item' 					=> __( 'Parent Testimonial Tag', 'bean' ),
			'parent_item_colon' 			=> __( 'Parent Testimonial Tag:', 'bean' ),
			'edit_item' 					=> __( 'Edit Testimonial Tag', 'bean' ),
			'update_item' 					=> __( 'Update Testimonial Tag', 'bean' ),
			'add_new_item' 				=> __( 'Add New Testimonial Tag', 'bean' ),
			'new_item_name' 				=> __( 'New Testimonial Tag Name', 'bean' ),
			'separate_items_with_commas' 		=> __( 'Separate tags with commas', 'bean' ),
			'add_or_remove_items' 			=> __( 'Add or remove tags', 'bean' ),
			'choose_from_most_used' 			=> __( 'Choose from the most used tags', 'bean' ),
			'menu_name' 					=> __( 'Tags', 'bean' )
		);

	}


	/*===================================================================*/
	/* ADD TAXONOMY FILTERS TO THE ADMIN PAGE
	/*===================================================================*/
	function add_taxonomy_filters()
	{
		global $typenow;

		// USE TAXONOMY NAME OR SLUG
		$taxonomies = array( 'testimonial_category', 'testimonial_tag' );

	 	// POST TYPE FOR THE FILTER
		if ( $typenow == 'testimonial' )
		{

			foreach ( $taxonomies as $tax_slug ) {
				$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if ( count( $terms ) > 0) {
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>$tax_name</option>";
					foreach ( $terms as $term ) {
						echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}
					echo "</select>";
				}
			}
		}
	}


	/*===================================================================*/
	/* CUSTOM ICON FOR POST TYPE
	/*===================================================================*/
	function custom_post_type_icon()
	{ ?>
		<style type="text/css" media="screen">
			#adminmenu #menu-posts-testimonial div.wp-menu-image:before { content: "\f205"; }
		</style>
	<?php
	}
} //END class Bean_Testimonial_Post_Type

new Bean_Testimonial_Post_Type;

endif;