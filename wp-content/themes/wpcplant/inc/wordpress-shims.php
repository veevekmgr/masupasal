<?php
/**
 * WordPress shims.
 *
 * @package wpcplant
 */

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Adds backwards compatibility for wp_body_open() introduced with WordPress 5.2
	 *
	 * @return void
	 * @see https://developer.wordpress.org/reference/functions/wp_body_open/
	 * @since 2.5.4
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
