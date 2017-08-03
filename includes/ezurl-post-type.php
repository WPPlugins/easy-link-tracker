<?php
/**
 * Custom Post Types
 *
 * @package     ELT
 * @subpackage  Post Types
 * @copyright   Copyright (c) 2013, RVA Media
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Registers and sets up the 'ezurl' custom post type
 *
 * @since 1.0
 * @return void
 */
function elt_setup_elt_post_types() {

		register_post_type( 'ezurl',
			array(
				'labels' => array(
					'name' => __( 'Easy Link' ),
					'all_items' => __( 'View All Links' ),
					'singular_name' => __( 'Link' ),
					'add_new' => __( 'Add New Link' ),
					'add_new_item' => __( 'Add New Easy Link' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Link' ),
					'new_item' => __( 'New Link' ),
					'view' => __( 'View Links' ),
					'view_item' => __( 'View Link' ),
					'search_items' => __( 'Search Links' ),
					'not_found' => __( 'No Easy Links found' ),
					'not_found_in_trash' => __( 'No Easy Links found in Trash' )
				),
				'public' => true,
				'query_var' => true,
				'menu_position' => 6,
				'exclude_from_search' => true,
				'supports' => array( 'title' ),
				'rewrite' => array( 'slug' => 'go', 'with_front' => false )
			)
		);

}
add_action( 'init', 'elt_setup_elt_post_types', 1 );


/**
 * Change default "Enter title here" input
 *
 * @since 1.0.0
 * @param string $title Default title placeholder text
 * @return string $title New placeholder text
 */
function elt_change_default_title( $title ) {
     $screen = get_current_screen();

     if  ( 'ezurl' == $screen->post_type ) {
        $title = __( 'Enter a Title for Your Hyperlink', 'elt');
     }

     return $title;
}
add_filter( 'enter_title_here', 'elt_change_default_title' );