<?php
/**
 * Plugin Name: Auto Image Attributes Pro
 * Plugin URI: https://imageattributespro.com
 * Description: Pro add-on of the popular Auto Image Attributes From Filename With Bulk Updater WordPress plugin.
 * Author: Arun Basil Lal
 * Author URI: https://millionclues.com
 * Version: 1.3
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: abl_iaffpro_td
 */
 
/**
 * This plugin was developed using the WordPress starter plugin template by Arun Basil Lal <arunbasillal@gmail.com>
 * Please leave this credit and the directory structure intact for future developers who might read the code. 
 * @Github		https://github.com/arunbasillal/WordPress-Starter-Plugin
 */

/**
 * ~ Directory Structure ~
 *
 * /admin/ 						- Plugin backend stuff.
 * /includes/					- External third party classes and libraries.
 * /public/						- Front end files go here.
 * index.php					- Dummy file.
 * license.txt					- GPL v2
 * iaffpro_auto-image-attributes-pro.php	- File containing plugin name and other version info for WordPress.
 * readme.txt					- Readme for WordPress plugin repository.
 * uninstall.php				- Fired when the plugin is uninstalled. 
 */
 
/**
 * ~ TODO ~
 * - Update IAFFPRO_VERSION_NUM 		in iaffpro_auto-image-attributes-pro.php (keep this line for future updates)
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Plugin name and directory constants
 *
 * @since 	1.0
 * @constant 	IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO		The name of the plugin - 'auto-image-attributes-pro'
 * @constant 	IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO_DIR	The absolute path to the plugin directory without the trailing slash - C:\xampp\htdocs\wp/wp-content/plugins/auto-image-attributes-pro
 */
if ( ! defined('IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO') )
    define('IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO', trim(dirname(plugin_basename(__FILE__)), '/'));

if ( ! defined('IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO_DIR') )
    define('IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO_DIR', WP_PLUGIN_DIR . '/' . IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO);


/**
 * Add plugin version to database
 *
 * @since 		1.0
 * @constant 	IAFFPRO_VERSION_NUM		the version number of the current version
 * @refer		https://codex.wordpress.org/Creating_Tables_with_Plugins#Adding_an_Upgrade_Function
 */
if ( ! defined('IAFFPRO_VERSION_NUM') )
    define('IAFFPRO_VERSION_NUM', '1.3');
update_option('abl_iaffpro_version', IAFFPRO_VERSION_NUM);	// Change this to add_option if a release needs to check installed version. Refer the @refer url.


// Load everything
require_once( IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO_DIR . '/admin/iaffpro_auto-image-attributes-pro-loader.php');

// Register activation hook (this has to be in the main plugin file.)
register_activation_hook( __FILE__, 'iaffpro_activate_plugin' );

/**
 * Update checker class
 *
 * @since	1.0
 */
require( IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO_DIR . '/includes/plugin-update-checker/plugin-update-checker.php');
$MyUpdateChecker = Puc_v4p3_Factory::buildUpdateChecker (
	'https://millionclues.org/superdome/?action=get_metadata&slug=auto-image-attributes-pro', // Metadata URL.
	__FILE__, 						// Full path to the main plugin file.
	'auto-image-attributes-pro', 	// Plugin slug. Usually it's the same as the name of the directory.
	72								// How often to check for updates (in hours)
);

/**
 * Add the license key and other details to query arguments.
 * 
 * Editing this does not work. I checked.
 * @since	1.0
 */
function iaffpro_license_key_filter($queryArgs) {
	
	// Get plugin Settings
	$settings = iaffpro_get_settings();
	
    if ( ! empty( $settings['registered_email'] ) && ! empty( $settings['license_key'] ) ) {
		
        $queryArgs['registered_email'] 	= $settings['registered_email'];
		$queryArgs['license_key'] 		= $settings['license_key'];
		$queryArgs['product'] 			= 'IAP';
		$queryArgs['installed_version'] = IAFFPRO_VERSION_NUM;
    }
	
    return $queryArgs;
}
$MyUpdateChecker->addQueryArgFilter( 'iaffpro_license_key_filter' );

/**
 * Show a custom upgrade notice in the plugins list
 *
 * @since	1.02
 */
function iaffpro_display_upgrade_notice() {
	
	// Return on all pages but the plugins.php admin page
	$screen = get_current_screen();
	if ( $screen->id !== 'plugins' ) {
		return;
	}
	
	// Get all the meta data
	global $MyUpdateChecker;
	$meta = $MyUpdateChecker->requestInfo();
	
	// Return if no custom upgrade notice is set. i.e. the user has a valid license
	if ( empty( $meta->upgrade_notice ) ) {
		return;
	}
	
	// Print custom upgrade notice.
	echo '<br><span class="dashicons dashicons-no" style="color:#DC3232; margin-right: 6px;"></span>' . $meta->upgrade_notice;
}
add_action( 'in_plugin_update_message-auto-image-attributes-pro/iaffpro_auto-image-attributes-pro.php', 'iaffpro_display_upgrade_notice' );