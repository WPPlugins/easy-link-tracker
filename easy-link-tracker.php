<?php
/**
 * Plugin Name: Easy Link Tracker
 * Plugin URI: 	http://www.rvamedia.com/wp-plugins/easy-link-tracker
 * Description: Easy Link Tracker is a link management tool that gives you the ability to create, manage and track internal & external links on your site by using custom post types, 301 redirects and Event Tracking using Google Analytics. 		
 * Author: 		RVA Media, LLC
 * Author URI: 	http://www.rvamedia.com
 * Version: 	1.0.1
 * Text Domain: elt
 * Domain Path: languages
 *
 * Easy Link Tracker is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Easy Link Tracker is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Easy Link Tracker. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ELT
 * @category Core
 * @author Rick R. Duncan
 * @version 1.0.1
 */
 
 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Easy_Link_Tracker' ) ) :

/**
 * Main Easy_Link_Tracker Class
 *
 * @since 1.0.0
 */
final class Easy_Link_Tracker {
	/** Singleton *************************************************************/
	
	/**
   	 * @var Easy_Link_Tracker The one true Easy_Link_Tracker
   	 * @since 1.0.0
   	 */
	private static $instance;
  		
  		
  	/**
  	 * Main Easy_Link_Tracker Instance
	 *
	 * Insures that only one instance of Easy_Link_Tracker exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0
	 * @static
	 * @staticvar array $instance
	 * @uses Easy_Link_Tracker::setup_globals() Setup the globals needed
	 * @uses Easy_Link_Tracker::includes() Include the required files
	 * @uses Easy_Link_Tracker::setup_actions() Setup the hooks and actions
	 * @see ELT()
	 * @return The one true Easy_Link_Tracker
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Easy_Link_Tracker ) ) {
			self::$instance = new Easy_Link_Tracker;
			self::$instance->setup_constants();
			self::$instance->includes();
		}
		return self::$instance;
	}
	
	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'elt' ), '1.6' );
	}
	
	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'elt' ), '1.6' );
	}
	
	/**
   	 * Setup plugin constants
     *
     * @access private
     * @since 1.0.0
     * @return void
     */
  	private function setup_constants() {
    	// Plugin version
    	if ( ! defined( 'ELT_VERSION' ) )
      		define( 'ELT_VERSION', '1.0.0 ' );

    	// Plugin Folder Path
    	if ( ! defined( 'ELT_PLUGIN_DIR' ) )
      		define( 'ELT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

    	// Plugin Folder URL
    	if ( ! defined( 'ELT_PLUGIN_URL' ) )
      		define( 'ELT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    	// Plugin Root File
    	if ( ! defined( 'ELT_PLUGIN_FILE' ) )
      		define( 'ELT_PLUGIN_FILE', __FILE__ );
  	}
  	
  	/**
   	 * Include required files
   	 *
   	 * @access private
   	 * @since 1.0.0
   	 * @return void
   	 */
  	private function includes() {

		require_once ELT_PLUGIN_DIR . 'includes/install.php';
		require_once ELT_PLUGIN_DIR . 'includes/shortcodes.php';
    	require_once ELT_PLUGIN_DIR . 'includes/ezurl-post-type.php';
    
    	/*if( is_admin() ) {
      		include( ELT_PLUGIN_DIR . 'includes/settings-page.php' );
    	} 
      	else {
    	}*/
  	}
	
}

endif; // End if class_exists check

/**
 * The main function responsible for returning the one true Easy_Link_Tracker
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $elt = ELT(); ?>
 *
 * @since 1.0
 * @return object The one true Easy_Link_Tracker Instance
 */
function ELT() {
	return Easy_Link_Tracker::instance();
}

// Get ELT Running
ELT();