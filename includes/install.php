<?php
/**
 * Install Functions
 *
 * @package     ELT
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2013, RVA Media, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install
 *
 * Runs on plugin install by setting up the post type, custom taxonomies,
 * flushing rewrite rules to initiate the new 'go' slug.
 *
 * @since 1.0
 * @return void
 */
 
	function elt_install() {
 
 		// Setup the ezurl Custom Post Type
		elt_setup_elt_post_types();
	
 		// Clear the permalinks
		flush_rewrite_rules();
	
	}
	register_activation_hook( ELT_PLUGIN_FILE, 'elt_install' ); 
 
 	
 	add_action( 'manage_posts_custom_column', 'columns_data'  );
	add_filter( 'manage_edit-ezurl_columns', 'columns_filter'  );
	add_action( 'admin_menu', 'create_meta_box' );
	add_action( 'save_post', 'meta_box_save', 1, 2 );
	add_action( 'template_redirect', 'count_and_redirect' );
		
	// Create menu item for instructional use 
	add_action( 'admin_menu' , 'instructions_register' );
		
	// Give the ezurl menu item a unique icon
	add_action( 'admin_head', 'ezurl_icon' );
  
	function columns_data( $column ) {
		
		global $post;
		
		$url = get_post_meta($post->ID, '_ezurl_redirect', true);
		$count = get_post_meta($post->ID, '_ezurl_count', true);
		
		if ( $column == 'url' ) {
			echo make_clickable( esc_url( $url ? $url : '' ) );
		}
		elseif ( $column == 'permalink' ) {
			echo make_clickable( get_permalink() );
		}
		elseif ( $column == 'clicks' ) {
			echo esc_html( $count ? $count : 0 );
		}
		
	}

	function columns_filter( $columns ) {
		
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title'),
			'url' => __('Redirect to'),
			'permalink' => __('Permalink'),
			'clicks' => __('Clicks')
		);
		
		return $columns;
		
	}
	
	function create_meta_box() {
		add_meta_box('ezurl', __('URL Information', 'ezurl'), 'meta_box', 'ezurl', 'normal', 'high');
	}
	
	function meta_box() {
		global $post;
		
		printf( '<input type="hidden" name="_ezurl_nonce" value="%s" />', wp_create_nonce( plugin_basename(__FILE__) ) );
		
		printf( '<p><label for="%s">%s</label></p>', '_ezurl_redirect', __('Redirect to URL', 'ezurl') );
		printf( '<p><input style="%s" type="text" name="%s" id="%s" value="%s" /></p>', 'width: 99%;', '_ezurl_redirect', '_ezurl_redirect', esc_attr( get_post_meta( $post->ID, '_ezurl_redirect', true ) ) );
		printf( '<p><strong>Remember:</strong> The link you enter can point to your own website (e.g., PDF, ZIP) or it can link off-site to an affiliate or partner website.</p>');
		$count = isset( $post->ID ) ? get_post_meta($post->ID, '_ezurl_count', true) : 0;
		printf( '<p>This URL has been accessed <b>%d</b> times.', esc_attr( $count ) );
		
	}
	
	function meta_box_save( $post_id, $post ) {
		
		$key = '_ezurl_redirect';
		
		//	verify the nonce
		if ( !isset($_POST['_ezurl_nonce']) || !wp_verify_nonce( $_POST['_ezurl_nonce'], plugin_basename(__FILE__) ) )
			return;
			
		//	don't try to save the data under autosave, ajax, or future post.
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
		if ( defined('DOING_AJAX') && DOING_AJAX ) return;
		if ( defined('DOING_CRON') && DOING_CRON ) return;

		//	is the user allowed to edit the URL?
		if ( ! current_user_can( 'edit_posts' ) || $post->post_type != 'ezurl' )
			return;
			
		$value = isset( $_POST[$key] ) ? $_POST[$key] : '';
		
		if ( $value ) {
			//	save/update
			update_post_meta($post->ID, $key, $value);
		} else {
			//	delete if blank
			delete_post_meta($post->ID, $key);
		}
		
	}

	
	function count_and_redirect() {
		
		if ( !is_singular('ezurl') )
			return;

		global $wp_query;
		
		// Update the count
		$count = isset( $wp_query->post->ID ) ? get_post_meta($wp_query->post->ID, '_ezurl_count', true) : 0;
		update_post_meta( $wp_query->post->ID, '_ezurl_count', $count + 1 );

		// Handle the redirect
		$redirect = isset( $wp_query->post->ID ) ? get_post_meta($wp_query->post->ID, '_ezurl_redirect', true) : '';

		if ( !empty( $redirect ) ) {
			wp_redirect( esc_url_raw( $redirect ), 301);
			exit;
		}
		else {
			wp_redirect( home_url(), 302 );
			exit;
		}
		
	}
	
	function instructions_register() {
		add_submenu_page(
    	'edit.php?post_type=ezurl',
    	'Instructions',     	// page title
		'Instructions',     	// menu title
    	'manage_options',   	// capability
    	'instructions.txt', 	// menu slug
    	'instructions_render' 	// callback function
    	);
	}

	
	function instructions_render() {
    	print '<div class="wrap">';
    	$file = ELT_PLUGIN_DIR . "instructions.txt";	
    	if ( file_exists( $file ) )
    	   	require $file;	
    	print '</div>';
	}
	
		// Displays the custom post type icon in the dashboard
		function ezurl_icon() { ?>
			<style type="text/css" media="screen">
			#menu-posts-ezurl .wp-menu-image {
			background: url(<?php echo ELT_PLUGIN_URL ?>images/ezurl-icon.png) no-repeat 6px 6px !important;
		}
		

		#icon-edit.icon32-posts-ezurl {background: url(<?php echo ELT_PLUGIN_URL ?>images/ezurl-dashboard.png) no-repeat;}
		</style>
		<?php }

