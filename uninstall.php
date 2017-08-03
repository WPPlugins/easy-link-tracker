<?php
/**
 * Uninstall Easy Link Tracker
 *
 * @package     ELT
 * @subpackage  Uninstall
 * @copyright   Copyright (c) 2013, RVA Media, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if ( !is_user_logged_in() ) {
  	wp_die( 'You must be logged in to run this script.' );
}

if ( !current_user_can( 'install_plugins' ) ) {
	wp_die( 'You do not have permission to run this script.' );
}

// Delete All the Custom Post Types
$elt_post_types = array( 'ezurl' );
foreach ( $elt_post_types as $post_type ) {

	$items = get_posts( array( 'post_type' => $post_type, 'numberposts' => -1, 'fields' => 'ids' ) );

	if ( $items ) {
		foreach ( $items as $item ) {
			// delete all, bypass trash and force deletion 
			wp_delete_post( $item, true);
		}
	}
}