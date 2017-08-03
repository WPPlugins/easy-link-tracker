<?php
/**
 * Shortcodes
 *
 * @package     ELT
 * @subpackage  Shortcodes
 * @copyright   Copyright (c) 2013, RVA Media, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Easy Link Shortcode
 *
 * Creates a hyperlink for Google Analytics Event tracking and also marked as rel=nofollow.
 * Useage: [ezLink url="genesis-framework" category="affiliate|download"]Genesis Framework[/ezLink]
 *
 * @since 1.0
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string Fully formatted hyperlink
 */
function ezlink_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
    'url' => 'url',
    'category' => 'category'), 
    $atts ) );
 
 	// Locate '/go/' in the URL and then assign it to the 'Action' argument in the _trackEvent() method
 	$myStrPos = strpos(esc_attr($url), '/go/');
  	$ga_action = substr(esc_attr($url), $myStrPos); 
 
 	return '<a href="' . esc_attr($url) . '" rel="nofollow" onClick="_gaq.push([\'_trackEvent\', \'' . esc_attr($category) . '\', \''.$ga_action.'\']);">' . $content . '</a>';
 		
}
add_shortcode( 'ezLink', 'ezlink_shortcode' );