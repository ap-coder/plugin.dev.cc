<?php
/**
 * Loads the plugin files
 *
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

// Load basic setup. Plugin list links, text domain, footer links etc. 
require_once( IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO_DIR . '/admin/iaffpro_auto-image-attributes-pro-basic-setup.php');

// Load admin setup. Register menus and settings
require_once( IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO_DIR . '/admin/iaffpro_auto-image-attributes-pro-admin-setup.php');

// Render Admin UI
require_once( IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO_DIR . '/admin/iaffpro_auto-image-attributes-pro-admin-ui-render.php');

// Do plugin operations
require_once( IAFFPRO_AUTO_IMAGE_ATTRIBUTES_PRO_DIR . '/admin/iaffpro_auto-image-attributes-pro-do.php');