<?php
/**
 * WPCplant_WooCommerce Class
 *
 * @package  wpcplant
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPCplant_WooCommerce' ) ) :

	/**
	 * The WPCplant WooCommerce Integration class
	 */
	class WPCplant_WooCommerce {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'setup' ) );
			add_filter( 'body_class', array( $this, 'woocommerce_body_class' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'woocommerce_scripts' ), 20 );
			add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
			add_filter( 'woocommerce_product_thumbnails_columns', array( $this, 'thumbnail_columns' ) );
			add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'change_breadcrumb_delimiter' ) );

			// Instead of loading Core CSS files, we only register the font families.
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		}

		/**
		 * Sets up theme defaults and registers support for various WooCommerce features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 *
		 * @return void
		 * @since 2.4.0
		 */
		public function setup() {
			add_theme_support(
				'woocommerce',
				apply_filters(
					'wpcplant_woocommerce_args',
					array(
						'single_image_width'    => 800,
						'thumbnail_image_width' => 400,
						'product_grid'          => array(
							'default_columns' => 3,
							'default_rows'    => 4,
							'min_columns'     => 1,
							'max_columns'     => 6,
							'min_rows'        => 1,
						),
					)
				)
			);

			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );

			/**
			 * Add 'wpcplant_woocommerce_setup' action.
			 *
			 * @since  2.4.0
			 */
			do_action( 'wpcplant_woocommerce_setup' );
		}

		/**
		 * Add WooCommerce specific classes to the body tag
		 *
		 * @param array $classes css classes applied to the body tag.
		 *
		 * @return array $classes modified to include 'woocommerce-active' class
		 */
		public function woocommerce_body_class( $classes ) {
			$classes[] = 'woocommerce-active';

			// Remove `no-wc-breadcrumb` body class.
			$key = array_search( 'no-wc-breadcrumb', $classes, true );

			if ( false !== $key ) {
				unset( $classes[ $key ] );
			}

			return $classes;
		}

		/**
		 * WooCommerce specific scripts & stylesheets
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_scripts() {
			global $wpcplant_version;

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_style( 'wpcplant-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce/woocommerce.css', array(
				'wpcplant-style',
				'wpcplant-icons'
			), $wpcplant_version );
			wp_style_add_data( 'wpcplant-woocommerce-style', 'rtl', 'replace' );

			wp_register_script( 'wpcplant-header-cart', get_template_directory_uri() . '/assets/js/woocommerce/header-cart' . $suffix . '.js', array(), $wpcplant_version, true );
			wp_enqueue_script( 'wpcplant-header-cart' );

			if ( is_shop() || is_tax( 'product_cat' ) ) {
				wp_register_script( 'wpcplant-toggle-layout', get_template_directory_uri() . '/assets/js/woocommerce/toggle-layout' . $suffix . '.js', array(), $wpcplant_version, true );
				wp_enqueue_script( 'wpcplant-toggle-layout' );
			}

			if ( function_exists( 'woosw_init' ) ) {
				wp_enqueue_script( 'wpcplant-wishlist-addon', get_template_directory_uri() . '/assets/js/woocommerce/extensions/wishlist' . $suffix . '.js', array( 'jquery' ), $wpcplant_version, true );
			}
		}

		/**
		 * Related Products Args
		 *
		 * @param array $args related products args.
		 *
		 * @return  array $args related products args
		 * @since 1.0.0
		 */
		public function related_products_args( $args ) {
			$args = apply_filters(
				'wpcplant_related_products_args',
				array(
					'posts_per_page' => 3,
					'columns'        => 3,
				)
			);

			return $args;
		}

		/**
		 * Product gallery thumbnail columns
		 *
		 * @return integer number of columns
		 * @since  1.0.0
		 */
		public function thumbnail_columns() {
			$columns = 4;

			if ( ! is_active_sidebar( 'sidebar-1' ) ) {
				$columns = 5;
			}

			return intval( apply_filters( 'wpcplant_product_thumbnail_columns', $columns ) );
		}

		/**
		 * Query WooCommerce Extension Activation.
		 *
		 * @param string $extension Extension class name.
		 *
		 * @return boolean
		 */
		public function is_woocommerce_extension_activated( $extension = 'WC_Bookings' ) {
			return class_exists( $extension ) ? true : false;
		}

		/**
		 * Remove the breadcrumb delimiter
		 *
		 * @param array $defaults The breadcrumb defaults.
		 *
		 * @return array           The breadcrumb defaults.
		 * @since 2.2.0
		 */
		public function change_breadcrumb_delimiter( $defaults ) {
			$defaults['delimiter']   = '<span class="breadcrumb-separator"> / </span>';
			$defaults['wrap_before'] = '<div class="wpcplant-breadcrumb"><div class="col-full"><nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'breadcrumbs', 'wpcplant' ) . '">';
			$defaults['wrap_after']  = '</nav></div></div>';

			return $defaults;
		}

	}

endif;

return new WPCplant_WooCommerce();
