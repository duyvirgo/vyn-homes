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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'vynhomes' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
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
define( 'AUTH_KEY',         'vUkRGFY.iHTo>yyGSth&vZ|HpU;J8l=qRB[f1HFf:>D.jQKRQZ_v^ln6{m~zq*0G' );
define( 'SECURE_AUTH_KEY',  'JaS=foa1liWoMf,cW/UYynMh~B}I^I>s3z.1WP^fy<lYk0q{0;[*oT.uWTOE<G2/' );
define( 'LOGGED_IN_KEY',    'Wj=C<Y6voA?NGgz)%/a~<{XPqN*sJ_j3!/I;n+eTj5:d1Ber(fmPEL)1UbmPnfcj' );
define( 'NONCE_KEY',        'QC%~ 9.2k]2t-O11-.;cIX^&I@G-Kz`xX4}E0Qt5)f=3fxNzFY@[P(fCY}}>+#zq' );
define( 'AUTH_SALT',        'mqG<.ee3ngJX38cw7M*0jP4pSvXU^55=Or9r@d`^}_?9gA2lF%u-/N}g/vy;}*B{' );
define( 'SECURE_AUTH_SALT', '^MMc (K3~ku[`EPcsvMQ7r4Ebh%x){{F)D0YpoBcPLa^|<Zf$)~A4*= sf8A,}WE' );
define( 'LOGGED_IN_SALT',   '+.rS}0lTpx=GfF>DStMq8~0TsuO|}Vm4qbvMzc(N;+Uj47Mok(6[av[$?tp7juER' );
define( 'NONCE_SALT',       '7>6&tyzMt?pM@vBL9WfkR2u|?s&6[w=8QP*lDzx;zaE~gM/RMWUL@iqJJG3RuWa[' );

/**#@-*/

/**
 * WordPress database table prefix.
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
