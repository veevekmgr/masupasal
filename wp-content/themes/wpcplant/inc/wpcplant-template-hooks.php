<?php
/**
 * WPCplant hooks
 *
 * @package wpcplant
 */

/**
 * General
 *
 * @see  wpcplant_get_sidebar()
 */

add_action( 'wpcplant_sidebar', 'wpcplant_get_sidebar', 10 );

/**
 * Header
 *
 * @see  wpcplant_skip_links()
 * @see  wpcplant_secondary_navigation()
 * @see  wpcplant_site_branding()
 * @see  wpcplant_primary_navigation()
 */
add_action( 'wpcplant_header', 'wpcplant_header_container', 0 );
add_action( 'wpcplant_header', 'wpcplant_header_row', 1 );
add_action( 'wpcplant_header', 'wpcplant_skip_links', 5 );
add_action( 'wpcplant_header', 'wpcplant_handheld_navigation_button', 10 );
add_action( 'wpcplant_header', 'wpcplant_site_branding', 20 );
add_action( 'wpcplant_header', 'wpcplant_header_woo_buttons', 21 );
add_action( 'wpcplant_header', 'wpcplant_header_woo_buttons_close', 36 );
add_action( 'wpcplant_header', 'wpcplant_header_row_close', 41 );
add_action( 'wpcplant_header', 'wpcplant_header_row', 42 );
add_action( 'wpcplant_header', 'wpcplant_primary_navigation', 50 );
add_action( 'wpcplant_header', 'wpcplant_header_row_close', 69 );
add_action( 'wpcplant_header', 'wpcplant_header_row', 70 );
add_action( 'wpcplant_header', 'wpcplant_handheld_navigation', 75 );
add_action( 'wpcplant_header', 'wpcplant_header_row_close', 79 );
add_action( 'wpcplant_header', 'wpcplant_header_container_close', 99 );

/**
 * Before Content
 *
 * @see  woocommerce_breadcrumb()
 * @see  wpcplant_page_title()
 */
if ( wpcplant_is_woocommerce_activated() ) {
	add_action( 'wpcplant_before_content', 'woocommerce_breadcrumb', 10 );
}
add_action( 'wpcplant_before_content', 'wpcplant_page_title', 11 );

/**
 * Footer
 *
 * @see  wpcplant_footer_widgets()
 * @see  wpcplant_credit()
 */
add_action( 'wpcplant_footer', 'wpcplant_footer_widgets', 10 );
add_action( 'wpcplant_footer', 'wpcplant_credit', 20 );


/**
 * Posts
 *
 * @see  wpcplant_post_header()
 * @see  wpcplant_post_meta()
 * @see  wpcplant_post_content()
 * @see  wpcplant_paging_nav()
 * @see  wpcplant_single_post_header()
 * @see  wpcplant_post_nav()
 * @see  wpcplant_display_comments()
 */
add_action( 'wpcplant_loop_post', 'wpcplant_post_header', 10 );
add_action( 'wpcplant_loop_post', 'wpcplant_post_content', 30 );
add_action( 'wpcplant_loop_after', 'wpcplant_paging_nav', 10 );
add_action( 'wpcplant_single_post', 'wpcplant_post_header', 10 );
add_action( 'wpcplant_single_post', 'wpcplant_post_content', 30 );
add_action( 'wpcplant_single_post_bottom', 'wpcplant_edit_post_link', 5 );
add_action( 'wpcplant_single_post_bottom', 'wpcplant_display_comments', 20 );
add_action( 'wpcplant_post_header_after', 'wpcplant_post_meta', 10 );
add_action( 'wpcplant_post_content_before', 'wpcplant_post_thumbnail', 10 );

/**
 * Pages
 *
 * @see  wpcplant_page_header()
 * @see  wpcplant_page_content()
 * @see  wpcplant_display_comments()
 */
add_action( 'wpcplant_page', 'wpcplant_page_header', 10 );
add_action( 'wpcplant_page', 'wpcplant_page_content', 20 );
add_action( 'wpcplant_page', 'wpcplant_edit_post_link', 30 );
add_action( 'wpcplant_page_after', 'wpcplant_display_comments', 10 );

/**
 * Homepage Page Template
 *
 * @see  wpcplant_homepage_header()
 * @see  wpcplant_page_content()
 */
add_action( 'homepage', 'wpcplant_homepage_content', 20 );
//add_action('wpcplant_homepage', 'wpcplant_homepage_header', 10);
add_action( 'wpcplant_homepage', 'wpcplant_page_content', 20 );
