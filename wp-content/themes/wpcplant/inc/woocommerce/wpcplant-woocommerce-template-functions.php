<?php
/**
 * WooCommerce Template Functions.
 *
 * @package wpcplant
 */

if ( ! function_exists( 'wpcplant_woo_cart_available' ) ) {
	/**
	 * Validates whether the Woo Cart instance is available in the request
	 *
	 * @return bool
	 * @since 2.6.0
	 */
	function wpcplant_woo_cart_available() {
		$woo = WC();

		return $woo instanceof \WooCommerce && $woo->cart instanceof \WC_Cart;
	}
}

if ( ! function_exists( 'wpcplant_before_content' ) ) {
	/**
	 * Before Content
	 * Wraps all WooCommerce content in wrappers which match the theme markup
	 *
	 * @return  void
	 * @since   1.0.0
	 */
	function wpcplant_before_content() {
		?>
        <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
		<?php
	}
}

if ( ! function_exists( 'wpcplant_after_content' ) ) {
	/**
	 * After Content
	 * Closes the wrapping divs
	 *
	 * @return  void
	 * @since   1.0.0
	 */
	function wpcplant_after_content() {
		?>
        </main><!-- #main -->
        </div><!-- #primary -->

		<?php
		do_action( 'wpcplant_sidebar' );
	}
}

if ( ! function_exists( 'wpcplant_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 *
	 * @return array            Fragments to refresh via AJAX
	 */
	function wpcplant_cart_link_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		wpcplant_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}

if ( ! function_exists( 'wpcplant_cart_link' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function wpcplant_cart_link() {
		if ( ! wpcplant_woo_cart_available() ) {
			return;
		}
		?>
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>"
           title="<?php esc_attr_e( 'View your shopping cart', 'wpcplant' ); ?>">
            <span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
			<?php /* translators: %d: number of items in cart */ ?>
			<?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?>
        </a>
		<?php
	}
}

if ( ! function_exists( 'wpcplant_product_search' ) ) {
	/**
	 * Display Product Search
	 *
	 * @return void
	 * @uses  wpcplant_is_woocommerce_activated() check if WooCommerce is activated
	 * @since  1.0.0
	 */
	function wpcplant_product_search() {
		if ( wpcplant_is_woocommerce_activated() ) {
			?>
            <div class="site-search">
				<?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
            </div>
			<?php
		}
	}
}

if ( ! function_exists( 'wpcplant_header_cart' ) ) {
	/**
	 * Display Header Cart
	 *
	 * @return void
	 * @uses  wpcplant_is_woocommerce_activated() check if WooCommerce is activated
	 * @since  1.0.0
	 */
	function wpcplant_header_cart() {
		if ( wpcplant_is_woocommerce_activated() ) {
			?>
            <div id="site-header-cart" class="site-header-cart menu">
				<?php wpcplant_cart_link(); ?>
				<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
            </div>
			<?php
		}
	}
}

if ( ! function_exists( 'wpcplant_upsell_display' ) ) {
	/**
	 * Upsells
	 * Replace the default upsell function with our own which displays the correct number product columns
	 *
	 * @return  void
	 * @since   1.0.0
	 * @uses    woocommerce_upsell_display()
	 */
	function wpcplant_upsell_display() {
		$columns = apply_filters( 'wpcplant_upsells_columns', 3 );
		woocommerce_upsell_display( - 1, $columns );
	}
}

if ( ! function_exists( 'wpcplant_sorting_wrapper' ) ) {
	/**
	 * Sorting wrapper
	 *
	 * @return  void
	 * @since   1.4.3
	 */
	function wpcplant_sorting_wrapper() {
		echo '<div class="wpcplant-sorting">';
	}
}

if ( ! function_exists( 'wpcplant_sorting_wrapper_close' ) ) {
	/**
	 * Sorting wrapper close
	 *
	 * @return  void
	 * @since   1.4.3
	 */
	function wpcplant_sorting_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'wpcplant_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper
	 *
	 * @return  void
	 * @since   2.2.0
	 */
	function wpcplant_product_columns_wrapper() {
		$columns = wpcplant_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
	}
}

if ( ! function_exists( 'wpcplant_loop_columns' ) ) {
	/**
	 * Default loop columns on product archives
	 *
	 * @return integer products per row
	 * @since  1.0.0
	 */
	function wpcplant_loop_columns() {
		$columns = 3; // 3 products per row

		if ( function_exists( 'wc_get_default_products_per_row' ) ) {
			$columns = wc_get_default_products_per_row();
		}

		return apply_filters( 'wpcplant_loop_columns', $columns );
	}
}

if ( ! function_exists( 'wpcplant_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close
	 *
	 * @return  void
	 * @since   2.2.0
	 */
	function wpcplant_product_columns_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'wpcplant_shop_messages' ) ) {
	/**
	 * WPCplant shop messages
	 *
	 * @since   1.4.4
	 * @uses    wpcplant_do_shortcode
	 */
	function wpcplant_shop_messages() {
		if ( ! is_checkout() ) {
			echo wp_kses_post( wpcplant_do_shortcode( 'woocommerce_messages' ) );
		}
	}
}

if ( ! function_exists( 'wpcplant_woocommerce_pagination' ) ) {
	/**
	 * WPCplant WooCommerce Pagination
	 * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
	 * but since WPCplant adds pagination before that function is excuted we need a separate function to
	 * determine whether or not to display the pagination.
	 *
	 * @since 1.4.4
	 */
	function wpcplant_woocommerce_pagination() {
		if ( woocommerce_products_will_display() ) {
			woocommerce_pagination();
		}
	}
}


if ( ! function_exists( 'wpcplant_homepage_products' ) ) {
	/**
	 * Display Recent Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function wpcplant_homepage_products( $args ) {
		$args = apply_filters(
			'wpcplant_homepage_products_args',
			array(
				'limit'    => 12,
				'columns'  => 4,
				'orderby'  => 'date',
				'order'    => 'desc',
				'title'    => __( 'Shop Products', 'wpcplant' ),
				'subtitle' => __( 'Must-have plugins for powering up online visibility.', 'wpcplant' ),
			)
		);

		$shortcode_content = wpcplant_do_shortcode(
			'products',
			apply_filters(
				'wpcplant_recent_products_shortcode_args',
				array(
					'orderby'  => esc_attr( $args['orderby'] ),
					'order'    => esc_attr( $args['order'] ),
					'per_page' => intval( $args['limit'] ),
					'columns'  => intval( $args['columns'] ),
				)
			)
		);

		/**
		 * Only display the section if the shortcode returns products
		 */
		if ( false !== strpos( $shortcode_content, 'product' ) ) {
			echo '<section class="wpcplant-product-section wpcplant-recent-products" aria-label="' . esc_attr__( 'Recent Products', 'wpcplant' ) . '">';

			do_action( 'wpcplant_homepage_before_products' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			if ( ! empty( $args['subtitle'] ) ) {
				echo '<p class="section-subtitle">' . $args['subtitle'] . '</p>';
			}
			do_action( 'wpcplant_homepage_after_products_title' );

			echo $shortcode_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			do_action( 'wpcplant_homepage_after_products' );

			echo '</section>';
		}
	}
}

// Add the opening div to the img
if ( ! function_exists( 'add_img_wrapper_start' ) ) {
	function add_img_wrapper_start() {
		echo '<div class="archive-img-wrap">';
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'add_img_wrapper_start', 5, 2 );

// Close the div that we just added
if ( ! function_exists( 'add_img_wrapper_close' ) ) {
	function add_img_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'add_img_wrapper_close', 12, 2 );

// Add 'Add to cart' inside thumbnail wrapper
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10 );

if ( ! function_exists( 'wpcplant_header_wishlist' ) ) {
	function wpcplant_header_wishlist() {
		if ( wpcplant_is_woocommerce_activated() && function_exists( 'woosw_init' ) ) {
			$key = WPCleverWoosw::get_key();

			?>
            <div class="site-header-wishlist woosw-check">
                <a class="header-wishlist" href="<?php echo esc_url( WPCleverWoosw::get_url( $key, true ) ); ?>">
                    <span class="count"><?php echo esc_html( WPCleverWoosw::get_count( $key ) ); ?></span>
                </a>
            </div>
			<?php
		}
	}
}

if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop.
	 */
	function woocommerce_template_loop_product_title() {
		echo '<h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '"><a href="' . esc_url_raw( get_the_permalink() ) . '">' . get_the_title() . '</a></h2>';
	}
}
if ( ! function_exists( 'wpcplant_product_label_stock' ) ) {
	function wpcplant_product_label_stock() {
		global $product;
		if ( ! $product->is_in_stock() ) {
			echo '<span class="out-of-stock">' . esc_html__( 'Out of stock', 'wpcplant' ) . '</span>';
		}
	}
}

if ( ! function_exists( 'wpcplant_product_label' ) ) {

	function wpcplant_product_label() {
		global $product;

		$output = array();

		if ( $product->is_on_sale() ) {

			$percentage = '';

			if ( $product->get_type() == 'variable' ) {

				$available_variations = $product->get_variation_prices();
				$max_percentage       = 0;

				foreach ( $available_variations['regular_price'] as $key => $regular_price ) {
					$sale_price = $available_variations['sale_price'][ $key ];

					if ( $sale_price < $regular_price ) {
						$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );

						if ( $percentage > $max_percentage ) {
							$max_percentage = $percentage;
						}
					}
				}

				$percentage = $max_percentage;
			} elseif ( ( $product->get_type() == 'simple' || $product->get_type() == 'external' ) ) {
				$percentage = round( ( ( $product->get_regular_price() - $product->get_price() ) / $product->get_regular_price() ) * 100 );
			}

			if ( $percentage ) {
				$output[] = '<span class="onsale">-' . $percentage . '%' . '</span>';
			} else {
				$output[] = '<span class="onsale">' . esc_html__( 'Sale', 'wpcplant' ) . '</span>';
			}
		}

		if ( $output ) {
			echo implode( '', $output );
		}
	}
}
add_filter( 'woocommerce_sale_flash', 'wpcplant_product_label', 10 );


if ( ! function_exists( 'wpcplant_button_grid_list_layout' ) ) {
	function wpcplant_button_grid_list_layout() {
		$layout = 'grid';

		if ( isset( $_COOKIE['shop_layout'] ) ) {
			$layout = sanitize_text_field( wp_unslash( $_COOKIE['shop_layout'] ) );
		}

		$class_grid = 'grid';

		if ( $layout == 'grid' ) {
			$class_grid = 'grid active';
		}

		$class_list = 'list';

		if ( $layout == 'list' ) {
			$class_list = 'list active';
		}
		?>
        <div class="gridlist-toggle">
            <a href="#" class="<?php echo esc_attr( $class_grid ); ?>" data-class="grid"><span
                        class="screen-reader-text"><?php echo esc_html__( 'Grid View', 'wpcplant' ); ?></span></a>
            <a href="#" class="<?php echo esc_attr( $class_list ); ?>" data-class="list"><span
                        class="screen-reader-text"><?php echo esc_html__( 'List View', 'wpcplant' ); ?></span></a>
        </div>
		<?php
	}
}

if ( ! function_exists( 'wpcplant_woocommerce_get_product_description' ) ) {
	function wpcplant_woocommerce_get_product_description() {
		global $post;

		$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

		if ( $short_description ) {
			?>
            <div class="short-description">
				<?php echo wp_kses_post( $short_description ); ?>
            </div>
			<?php
		}
	}
}

if ( ! function_exists( 'wpcplant_quickview_button' ) ) {
	function wpcplant_quickview_button() {
		if ( function_exists( 'woosq_init' ) ) {
			echo do_shortcode( '[woosq]' );
		}
	}
}

if ( ! function_exists( 'wpcplant_compare_button' ) ) {
	function wpcplant_compare_button() {
		if ( function_exists( 'woosc_init' ) ) {
			echo do_shortcode( '[woosc]' );
		}
	}
}

if ( ! function_exists( 'wpcplant_wishlist_button' ) ) {
	function wpcplant_wishlist_button() {
		if ( function_exists( 'woosw_init' ) ) {
			echo do_shortcode( '[woosw]' );
		}
	}
}

// Change Single Image Size
add_filter( 'woocommerce_get_image_size_single', function ( $size ) {
	return array(
		'width'  => 450,
		'height' => 600,
		'crop'   => 1,
	);
} );

add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function ( $size ) {
	return array(
		'width'  => 85,
		'height' => 113,
		'crop'   => 0,
	);
} );
