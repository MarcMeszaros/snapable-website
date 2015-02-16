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
function env_str($name, $default='') {
    $val = getenv($name);
    return ($val) ? $val : $default;
}

function env_bool($name, $default=false) {
    $val = getenv($name);
    if ($val && strlen($val) > 0) {
        $val_sanitized = strtolower($val.trim());
        $char = substr($val_sanitized, 0, 1);
        if (array_intersect(array($char), array('1', 't', 'y'))) {
            return true;
        } else {
            return false;
        }
    } else {
        return $default;
    }
}

// force SSL login and SSL for admin console
define('FORCE_SSL_ADMIN', true);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'blog');

/** MySQL database username */
define('DB_USER', 'blog_usr');

/** MySQL database password */
define('DB_PASSWORD', 'vj5pVr8Lg0B6C8Hb');

/** MySQL hostname */
define('DB_HOST', env_str('DB_HOST', 'localhost'));

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
define('AUTH_KEY',         'a})4_(<i/_v37 o%muIJC04*u*A|E>ehQ+^E?+B@w%~-qj0oh=A4+:};bBfzF~9=');
define('SECURE_AUTH_KEY',  'zf+@}A[}L`{m$EMNmekyw|g|io5t2,md1Ri-0@@.KEISvXy3|{%+5g_{iz^.vjH}');
define('LOGGED_IN_KEY',    'Ev -jy.%$Avs!MWRpYpq623DT;.}n9u(O%ZKtvc`D!2GBa:pEKel*jyn3-Uw32<j');
define('NONCE_KEY',        'M&+R_h(`Lks3~J0h:y WN9-!/../[#6iX*>Lbsyl+Zfsx|U5|^I>jH6F1;WQv--)');
define('AUTH_SALT',        'CSxaL3r:ic+)*E;>xw+4u&51.x_4ubCK#R_~p*0W$lG}?=4(i7-RfZVQQ>&QJm$ ');
define('SECURE_AUTH_SALT', 'qcP00SJqHVRW!@<Z^_V^jTyG_j}dn&P3||i[z27h]|k@l^XhJU]m]r&<k1sQ:O3&');
define('LOGGED_IN_SALT',   'HCLBu+R|9Pprerfoj7p]=#:-xlpuR%Dtuf{9LQUQ|wo5y>`f@p0.P><Ok[E.G8kZ');
define('NONCE_SALT',       'y1?ca|-?X_49*EZO_+f,J~qHjC6y&Es`Qi]iu?P(Ph.os!?KZ2H)wr-H;O1{LqDW');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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

