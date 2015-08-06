<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db35482_chefs');

/** MySQL database username */
define('DB_USER', 'db35482_newsite');

/** MySQL database password */
define('DB_PASSWORD', 'think@TKf3');

/** MySQL hostname */
define('DB_HOST', 'internal-db.s35482.gridserver.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'BTcwz{Q,WT%lakD%*Ifv>VaP1^YdGi$tV}+J%i}K~nw+k<H&a/wf1WcB|rO.8Usy');
define('SECURE_AUTH_KEY',  'q]@wd.]clGX[[!~Eu(L+nr.:&attue^[O*[M_a}fN<u~ytOCn7oA.ML1m)4x9c^A');
define('LOGGED_IN_KEY',    '^N]Mh|}[|Bo%+GZ!|7hVZrb4xm80!4e3xdC?>>_-M=N#`uSz/|B$P4=^M~HS ;M?');
define('NONCE_KEY',        'Ko4<Y1|dA#;!Kd?SZf4ujo1 S6iT{*HHf{tRGc+##+{/N_zaw|6Q9`Ud`y@#ZCXW');
define('AUTH_SALT',        '1mp3(Tf3Wc$^8(*vK),oAtaBP4?m|=P}bNJBu^->YB;^mR=Ma:E`bXxox9lG[+!~');
define('SECURE_AUTH_SALT', 'oWpoSY#!H4&:Z*c:tA_0IIv+~sW)>^F-oD{sA[^_I5F|8jMdcKgr+p-ILmn%G_/W');
define('LOGGED_IN_SALT',   'X|Smy,r7-0(Kjk=Q}=E;:S$if/#mgOFZDbUaamD;Do3VK-~XKYht=,6*&c+[87)^');
define('NONCE_SALT',       'mvArZXs{zD:Ox[vRiqn{eAPpSVSLJ2o5Kh5lVBW1_2/1Ra!-ta8h65Q-V1G?)9&K');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
