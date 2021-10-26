<?php
/**
 * WPCplant engine room
 *
 * @package wpcplant
 */

/**
 * Assign the WPCplant version to a var
 */
$wpcplant_theme   = wp_get_theme( 'wpcplant' );
$wpcplant_version = $wpcplant_theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$wpcplant = (object) array(
	'version'    => $wpcplant_version,
	'main'       => require 'inc/class-wpcplant.php',
	'customizer' => require 'inc/customizer/class-wpcplant-customizer.php',
);

require 'inc/wpcplant-functions.php';
require 'inc/wpcplant-template-hooks.php';
require 'inc/wpcplant-template-functions.php';
require 'inc/wpcplant-notice.php';
require 'inc/wordpress-shims.php';

if ( wpcplant_is_woocommerce_activated() ) {
	$wpcplant->woocommerce = require 'inc/woocommerce/class-wpcplant-woocommerce.php';

	require 'inc/woocommerce/wpcplant-woocommerce-template-hooks.php';
	require 'inc/woocommerce/wpcplant-woocommerce-template-functions.php';
	require 'inc/woocommerce/wpcplant-woocommerce-functions.php';
}
