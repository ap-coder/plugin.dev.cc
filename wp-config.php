<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */


// ** MySQL settings - You can get this info from your web host ** //

/** The name of the database for WordPress */
define( 'DB_NAME', 'pluginDBfw039');
/** MySQL database username */
define( 'DB_USER', 'pluginDBfw039');
/** MySQL database password */
define( 'DB_PASSWORD', 'Zhntvy369n');
/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1');
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );


/**#@+

 * Authentication Unique Keys and Salts.

 *

 * Change these to different unique phrases!

 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}

 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.

 *

 * @since 2.6.0

 */

define( 'AUTH_KEY', 'w|JNRZcg![}04CAEIPTXb+.<{;6Xaeiqux2AEHLTtx+.<];TWaemq];6ADHmptx*.');
define( 'SECURE_AUTH_KEY', 't+.#;PTaeim];29DHLptx+_#HLSWai_#]259Dhlpt+~9HLOSW-~_];15aehltx19D');
define( 'LOGGED_IN_KEY', '{3AEjmqu$AEIPTXb*.<{36Xbimqy26AELPTy+*.{PTbeim{;2AEILqux+.<HPTXa');
define( 'NONCE_KEY', 'Iry$^,fimuy$*EILTXb+.<{26Xeimqy26AILPTx+*.{;PXaeiq];2AEHLqtx*.<HP');
define( 'AUTH_SALT', 'Qu$^>{UXbfnr{37AEMnqu$^,<MQTbfjt-~_#;OSWehp]:19DHKptw-_#GOSWah_#');
define( 'SECURE_AUTH_SALT', 'eq]26EHLmtx+*<HLTWae.#]26ADempt+*9DLPTW+*_#;26WeiltxOSadhp[:59DGl');
define( 'LOGGED_IN_SALT', 'Xemqx26EHLPtx+*<];TXaemq];6ADHmptx*.#LPTWei_#;269eiltx+*DHLPWa+~#');
define( 'NONCE_SALT', 'Ajnqy$AEIMTXb^.<{37Xbjmqy36AIMPTy$*.{PTbfim{26EILmuy+*<IPTXbi.<');


/**#@-*/


/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = 'wp_';


/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 *

 * For information on other constants that can be used for debugging,

 * visit the Codex.

 *

 * @link https://codex.wordpress.org/Debugging_in_WordPress

 */

define( 'WP_DEBUG', true );

define('FS_METHOD','direct');

// define('FS_CHMOD_DIR',0755);

// define('FS_CHMOD_FILE',0644);


define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');


define( 'QM_ENABLE_CAPS_PANEL', true );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );


define( 'SCRIPT_DEBUG', true );
define( 'SAVEQUERIES', true );
define( 'WP_LOCAL_DEV',      true );
/* AutoSave Interval. */
define( 'AUTOSAVE_INTERVAL', '300' );
/* Disable Post Revisions. */
define( 'WP_POST_REVISIONS', false );
define( 'WP_MEMORY_LIMIT', '1024M' );
define( 'WP_MAX_MEMORY_LIMIT', '1024M' );

 
define( 'WP_SENTRY_DSN', 'https://b58b5b49b1fa4fbcb795f5e992376dc1@o376166.ingest.sentry.io/5196638' );
define( 'WP_SENTRY_PUBLIC_DSN', 'https://b58b5b49b1fa4fbcb795f5e992376dc1@o376166.ingest.sentry.io/5196638' );
// define( 'WP_SENTRY_ERROR_TYPES', E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_USER_DEPRECATED );
// define( 'WP_SENTRY_SEND_DEFAULT_PII', true );

// define( 'WP_SENTRY_PUBLIC_DSN', 'JS_DSN' );

define( 'WP_SENTRY_VERSION', 'v3.4.6' );
define( 'WP_SENTRY_ENV', 'development' );

//Don't show errors to site visitors.

@ini_set('display_errors', 'On');


@ini_set('log_errors', 'On');

@ini_set('error_log', 'C:/Users/phillip.madsen/Sites/plugin.dev.cc/wp-content/phillips-errors.log');



// if ( !defined('WP_DEBUG_DISPLAY') ) {

// 	define('WP_DEBUG_DISPLAY', false);

// }




define( 'WP_DISABLE_FATAL_ERROR_HANDLER', true );
/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

}


/** Sets up WordPress vars and included files. */

require_once( ABSPATH . 'wp-settings.php' );

