<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'afnomasupasal' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '##O4DQ{^Ri>EdfzQ|A8L?.?`}*Xt~VUBJRSaNIHN=(SmQQSPmE!px0XO1Yh_yZ_!' );
define( 'SECURE_AUTH_KEY',  'F>6*(4Fsr*=D]V;(~1170x{9Dgjd_q?wXin@DC9q1{jZj(CilE#rW1UOWT4mC[c$' );
define( 'LOGGED_IN_KEY',    ' ELznpNtq/]CwvbwqV%i8busjRS&Gr@9nz&^T>lOas+!h$.|QDG!Yxj[G=UGIWJA' );
define( 'NONCE_KEY',        '>D V{N[4?W.$nqfpD.WGK[<p>KY>c)]ZH<5w)_i+eR/yZ#L;T{H._Cq--SO&e1!E' );
define( 'AUTH_SALT',        '7Hu7AfJW%jDgx-Z]!a&bnwwb[dmy<jYEEtNXS@pa5cJ7Vs=:ex.![`Q. >LV]%Dv' );
define( 'SECURE_AUTH_SALT', '%xV.#PU3WZ}Ch&VSH|hu;U&`@E4d[a]Ok].ngKo!TE],JP*L]!7QK@(w1e3$oMec' );
define( 'LOGGED_IN_SALT',   '/gpHReq%97L@o{mO|fhBk-gqed`@5v5?u=Uq[%,K,5Z&>Na($V1uEnHl6ijurBd=' );
define( 'NONCE_SALT',       '5xKzP=t9fn8$Y>63-IuqP}PlHd3=w%=jqiSy+E(L@tP:7Wdv-_F)|:YS _<zG:F ' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'mp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
