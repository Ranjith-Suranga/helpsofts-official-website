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
define('DB_NAME', 'wp-main');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/* 
 * Use only when developing
 * Comment this line after the importing is finished.
 */
define( 'WP_MEMORY_LIMIT', '256M' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'h%|1-Qj~L@x>+G/gBK&*u-v$]Td=3p8VM*p/!=pQx(CE4f!jx~wS%iO~qyR}H_o>');
define('SECURE_AUTH_KEY',  'gVwAJ=|#b/C# ;l^ag7S+/K8}h~`Ai3&V]Unk2gaaka:?,gi38u6}3WHw_dQiuFh');
define('LOGGED_IN_KEY',    '$oSHa:m!-|6kZD*S[S|VH-@J5Qz?cTY9!55Y[Nf_XH`#ce9[[.o%?$&aei,}jk?|');
define('NONCE_KEY',        'Biid;!|$+S[JsO6L<4S0m58m0c`-@]z_&QRt$7}xK3?%S14k$}](p%PW>ekp[7q4');
define('AUTH_SALT',        '/[(`C=~|Izz~2p1k<B+qmp={>dir;N,WW|a$1VeD%2!Lh awhtzBXM5hX{77i}kB');
define('SECURE_AUTH_SALT', 'iR!+HXFMupOe+SJQA*+D24PTku.TsiQc%e9Xmni|$E-OS9^6Kx0W3)ck-h $#)v8');
define('LOGGED_IN_SALT',   'NtT>W`3Abhr<>(gw^$E>v+qx|^UR,P/K4:[0%Z5++JR6W- 4+~+/P:HxHc|B-LZ?');
define('NONCE_SALT',       'c{|kZ)-9SMcHRcT~@K0p~%^-sA UKz&i(->*qPl$vwiu,BI]}%a0p$8flQavU/i]');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
