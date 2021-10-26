<?php
/**
 * WPCplant Customizer Class
 *
 * @package  wpcplant
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPCplant_Customizer' ) ) :

	/**
	 * The WPCplant Customizer class
	 */
	class WPCplant_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ), 10 );
			add_filter( 'body_class', array( $this, 'layout_class' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_customizer_css' ), 130 );
			add_action( 'customize_controls_print_styles', array( $this, 'customizer_custom_control_css' ) );
			add_action( 'customize_register', array( $this, 'edit_default_customizer_settings' ), 99 );
			add_action( 'enqueue_block_assets', array( $this, 'block_editor_customizer_css' ) );
			add_action( 'init', array( $this, 'default_theme_mod_values' ), 10 );
		}

		/**
		 * Returns an array of the desired default WPCplant Options
		 *
		 * @return array
		 */
		public function get_wpcplant_default_setting_values() {
			return apply_filters(
				'wpcplant_setting_default_values',
				$args = array(
					'wpcplant_primary_color'                 => '#63A86D',
					'wpcplant_heading_color'                 => '#000000',
					'wpcplant_text_color'                    => '#777777',
					'wpcplant_accent_color'                  => '#000000',
					'wpcplant_accent_color_hover'            => '#63A86D',
					'wpcplant_hero_heading_color'            => '#000000',
					'wpcplant_hero_text_color'               => '#000000',
					'wpcplant_header_background_color'       => '#ffffff',
					'wpcplant_header_text_color'             => '#777777',
					'wpcplant_header_link_color'             => '#000000',
					'wpcplant_header_link_color_hover'       => '#63A86D',
					'wpcplant_footer_background_color'       => '#000000',
					'wpcplant_footer_heading_color'          => '#ffffff',
					'wpcplant_footer_heading_border_color'   => '#343434',
					'wpcplant_footer_text_color'             => '#ffffff',
					'wpcplant_footer_link_color'             => '#ffffff',
					'wpcplant_button_background_color'       => '#63A86D',
					'wpcplant_button_background_color_hover' => '#000000',
					'wpcplant_button_text_color'             => '#ffffff',
					'wpcplant_button_border_color'           => '#63A86D',
					'wpcplant_button_alt_background_color'   => '#63A86D',
					'wpcplant_button_alt_text_color'         => '#ffffff',
					'wpcplant_layout'                        => 'right',
					'background_color'                       => '#ffffff',
				)
			);
		}

		/**
		 * Adds a value to each WPCplant setting if one isn't already present.
		 *
		 * @uses get_wpcplant_default_setting_values()
		 */
		public function default_theme_mod_values() {
			foreach ( $this->get_wpcplant_default_setting_values() as $mod => $val ) {
				add_filter( 'theme_mod_' . $mod, array( $this, 'get_theme_mod_value' ), 10 );
			}
		}

		/**
		 * Get theme mod value.
		 *
		 * @param string $value Theme modification value.
		 *
		 * @return string
		 */
		public function get_theme_mod_value( $value ) {
			$key = substr( current_filter(), 10 );

			$set_theme_mods = get_theme_mods();

			if ( isset( $set_theme_mods[ $key ] ) ) {
				return $value;
			}

			$values = $this->get_wpcplant_default_setting_values();

			return isset( $values[ $key ] ) ? $values[ $key ] : $value;
		}

		/**
		 * Set Customizer setting defaults.
		 * These defaults need to be applied separately as child themes can filter wpcplant_setting_default_values
		 *
		 * @param array $wp_customize the Customizer object.
		 *
		 * @uses   get_wpcplant_default_setting_values()
		 */
		public function edit_default_customizer_settings( $wp_customize ) {
			foreach ( $this->get_wpcplant_default_setting_values() as $mod => $val ) {
				$wp_customize->get_setting( $mod )->default = $val;
			}
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer along with several other settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 *
		 * @since  1.0.0
		 */
		public function customize_register( $wp_customize ) {

			// Move background color setting alongside background image.
			$wp_customize->get_control( 'background_color' )->section  = 'background_image';
			$wp_customize->get_control( 'background_color' )->priority = 20;

			// Change background image section title & priority.
			$wp_customize->get_section( 'background_image' )->title    = __( 'Background', 'wpcplant' );
			$wp_customize->get_section( 'background_image' )->priority = 30;

			// Change header image section title & priority.
			$wp_customize->get_section( 'header_image' )->title    = __( 'Header', 'wpcplant' );
			$wp_customize->get_section( 'header_image' )->priority = 25;

			// Selective refresh.
			if ( function_exists( 'add_partial' ) ) {
				$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
				$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

				$wp_customize->selective_refresh->add_partial(
					'custom_logo',
					array(
						'selector'        => '.site-branding',
						'render_callback' => array( $this, 'get_site_logo' ),
					)
				);

				$wp_customize->selective_refresh->add_partial(
					'blogname',
					array(
						'selector'        => '.site-title.beta a',
						'render_callback' => array( $this, 'get_site_name' ),
					)
				);

				$wp_customize->selective_refresh->add_partial(
					'blogdescription',
					array(
						'selector'        => '.site-description',
						'render_callback' => array( $this, 'get_site_description' ),
					)
				);
			}

			/**
			 * Custom controls
			 */
			require_once dirname( __FILE__ ) . '/class-wpcplant-customizer-control-radio-image.php';

			/**
			 * Add the typography section
			 */
			$wp_customize->add_section(
				'wpcplant_typography',
				array(
					'title'    => __( 'Typography', 'wpcplant' ),
					'priority' => 45,
				)
			);

			/**
			 * Primary color
			 */
			$wp_customize->add_setting(
				'wpcplant_primary_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_heading_color', '#63A86D' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_primary_color',
					array(
						'label'    => __( 'Primary color', 'wpcplant' ),
						'section'  => 'wpcplant_typography',
						'settings' => 'wpcplant_primary_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Heading color
			 */
			$wp_customize->add_setting(
				'wpcplant_heading_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_heading_color', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_heading_color',
					array(
						'label'    => __( 'Heading color', 'wpcplant' ),
						'section'  => 'wpcplant_typography',
						'settings' => 'wpcplant_heading_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Text Color
			 */
			$wp_customize->add_setting(
				'wpcplant_text_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_text_color', '#43454b' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_text_color',
					array(
						'label'    => __( 'Text color', 'wpcplant' ),
						'section'  => 'wpcplant_typography',
						'settings' => 'wpcplant_text_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Accent Color
			 */
			$wp_customize->add_setting(
				'wpcplant_accent_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_accent_color', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_accent_color',
					array(
						'label'    => __( 'Link / accent color', 'wpcplant' ),
						'section'  => 'wpcplant_typography',
						'settings' => 'wpcplant_accent_color',
						'priority' => 40,
					)
				)
			);

			$wp_customize->add_setting(
				'wpcplant_accent_color_hover',
				array(
					'default'           => apply_filters( 'wpcplant_default_accent_color_hover', '#63A86D' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_accent_color_hover',
					array(
						'label'    => __( 'Link / accent color hover', 'wpcplant' ),
						'section'  => 'wpcplant_typography',
						'settings' => 'wpcplant_accent_color_hover',
						'priority' => 45,
					)
				)
			);

			/**
			 * Hero Heading Color
			 */
			$wp_customize->add_setting(
				'wpcplant_hero_heading_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_hero_heading_color', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_hero_heading_color',
					array(
						'label'    => __( 'Hero heading color', 'wpcplant' ),
						'section'  => 'wpcplant_typography',
						'settings' => 'wpcplant_hero_heading_color',
						'priority' => 50,
					)
				)
			);

			/**
			 * Hero Text Color
			 */
			$wp_customize->add_setting(
				'wpcplant_hero_text_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_hero_text_color', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_hero_text_color',
					array(
						'label'    => __( 'Hero text color', 'wpcplant' ),
						'section'  => 'wpcplant_typography',
						'settings' => 'wpcplant_hero_text_color',
						'priority' => 60,
					)
				)
			);

			/**
			 * Header Background
			 */
			$wp_customize->add_setting(
				'wpcplant_header_background_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_header_background_color', '#ffffff' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);


			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_header_background_color',
					array(
						'label'    => __( 'Background color', 'wpcplant' ),
						'section'  => 'header_image',
						'settings' => 'wpcplant_header_background_color',
						'priority' => 15,
					)
				)
			);

			/**
			 * Header text color
			 */
			$wp_customize->add_setting(
				'wpcplant_header_text_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_header_text_color', '#777777' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_header_text_color',
					array(
						'label'    => __( 'Text color', 'wpcplant' ),
						'section'  => 'header_image',
						'settings' => 'wpcplant_header_text_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Header link color
			 */
			$wp_customize->add_setting(
				'wpcplant_header_link_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_header_link_color', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_header_link_color',
					array(
						'label'    => __( 'Link color', 'wpcplant' ),
						'section'  => 'header_image',
						'settings' => 'wpcplant_header_link_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Header link color hover
			 */
			$wp_customize->add_setting(
				'wpcplant_header_link_color_hover',
				array(
					'default'           => apply_filters( 'wpcplant_default_header_link_color', '#63A86D' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_header_link_color_hover',
					array(
						'label'    => __( 'Link color hover', 'wpcplant' ),
						'section'  => 'header_image',
						'settings' => 'wpcplant_header_link_color_hover',
						'priority' => 30,
					)
				)
			);

			/**
			 * Footer section
			 */
			$wp_customize->add_section(
				'wpcplant_footer',
				array(
					'title'       => __( 'Footer', 'wpcplant' ),
					'priority'    => 28,
					'description' => __( 'Customize the look & feel of your website footer.', 'wpcplant' ),
				)
			);

			/**
			 * Footer Background
			 */
			$wp_customize->add_setting(
				'wpcplant_footer_background_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_footer_background_color', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_footer_background_color',
					array(
						'label'    => __( 'Background color', 'wpcplant' ),
						'section'  => 'wpcplant_footer',
						'settings' => 'wpcplant_footer_background_color',
						'priority' => 10,
					)
				)
			);

			/**
			 * Footer heading color
			 */
			$wp_customize->add_setting(
				'wpcplant_footer_heading_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_footer_heading_color', '#ffffff' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_footer_heading_color',
					array(
						'label'    => __( 'Heading color', 'wpcplant' ),
						'section'  => 'wpcplant_footer',
						'settings' => 'wpcplant_footer_heading_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Footer heading border color
			 */
			$wp_customize->add_setting(
				'wpcplant_footer_heading_border_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_footer_heading_border_color', '#343434' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_footer_heading_border_color',
					array(
						'label'    => __( 'Heading border color', 'wpcplant' ),
						'section'  => 'wpcplant_footer',
						'settings' => 'wpcplant_footer_heading_border_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Footer text color
			 */
			$wp_customize->add_setting(
				'wpcplant_footer_text_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_footer_text_color', '#61656b' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_footer_text_color',
					array(
						'label'    => __( 'Text color', 'wpcplant' ),
						'section'  => 'wpcplant_footer',
						'settings' => 'wpcplant_footer_text_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Footer link color
			 */
			$wp_customize->add_setting(
				'wpcplant_footer_link_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_footer_link_color', '#2c2d33' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_footer_link_color',
					array(
						'label'    => __( 'Link color', 'wpcplant' ),
						'section'  => 'wpcplant_footer',
						'settings' => 'wpcplant_footer_link_color',
						'priority' => 40,
					)
				)
			);

			/**
			 * Footer copyright text
			 */
			$wp_customize->add_setting(
				'wpcplant_footer_copyright_text',
				array(
					'default'           => __( 'Built with WPCplant & WooCommerce', 'wpcplant' ),
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			$wp_customize->add_control(
				'wpcplant_footer_copyright_text',
				array(
					'label'    => __( 'Copyright', 'wpcplant' ),
					'type'     => 'text',
					'section'  => 'wpcplant_footer',
					'priority' => 50,
				)
			);

			/**
			 * Buttons section
			 */
			$wp_customize->add_section(
				'wpcplant_buttons',
				array(
					'title'       => __( 'Buttons', 'wpcplant' ),
					'priority'    => 45,
					'description' => __( 'Customize the look & feel of your website buttons.', 'wpcplant' ),
				)
			);

			/**
			 * Button background color
			 */
			$wp_customize->add_setting(
				'wpcplant_button_background_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_button_background_color', '#63A86D' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_button_background_color',
					array(
						'label'    => __( 'Background color', 'wpcplant' ),
						'section'  => 'wpcplant_buttons',
						'settings' => 'wpcplant_button_background_color',
						'priority' => 10,
					)
				)
			);

			/**
			 * Button background color hover
			 */
			$wp_customize->add_setting(
				'wpcplant_button_background_color_hover',
				array(
					'default'           => apply_filters( 'wpcplant_default_button_background_color_hover', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_button_background_color_hover',
					array(
						'label'    => __( 'Background color hover', 'wpcplant' ),
						'section'  => 'wpcplant_buttons',
						'settings' => 'wpcplant_button_background_color_hover',
						'priority' => 10,
					)
				)
			);

			/**
			 * Button text color
			 */
			$wp_customize->add_setting(
				'wpcplant_button_text_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_button_text_color', '#ffffff' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_button_text_color',
					array(
						'label'    => __( 'Text color', 'wpcplant' ),
						'section'  => 'wpcplant_buttons',
						'settings' => 'wpcplant_button_text_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Button text color
			 */
			$wp_customize->add_setting(
				'wpcplant_button_border_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_button_border_color', '#63A86D' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_button_border_color',
					array(
						'label'    => __( 'Border color', 'wpcplant' ),
						'section'  => 'wpcplant_buttons',
						'settings' => 'wpcplant_button_border_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Button alt background color
			 */
			$wp_customize->add_setting(
				'wpcplant_button_alt_background_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_button_alt_background_color', '#63A86D' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_button_alt_background_color',
					array(
						'label'    => __( 'Alternate button background color', 'wpcplant' ),
						'section'  => 'wpcplant_buttons',
						'settings' => 'wpcplant_button_alt_background_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Button alt text color
			 */
			$wp_customize->add_setting(
				'wpcplant_button_alt_text_color',
				array(
					'default'           => apply_filters( 'wpcplant_default_button_alt_text_color', '#ffffff' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'wpcplant_button_alt_text_color',
					array(
						'label'    => __( 'Alternate button text color', 'wpcplant' ),
						'section'  => 'wpcplant_buttons',
						'settings' => 'wpcplant_button_alt_text_color',
						'priority' => 40,
					)
				)
			);

			/**
			 * Layout
			 */
			$wp_customize->add_section(
				'wpcplant_layout',
				array(
					'title'    => __( 'Layout', 'wpcplant' ),
					'priority' => 50,
				)
			);

			$wp_customize->add_setting(
				'wpcplant_layout',
				array(
					'default'           => apply_filters( 'wpcplant_default_layout', $layout = is_rtl() ? 'left' : 'right' ),
					'sanitize_callback' => 'wpcplant_sanitize_choices',
				)
			);

			$wp_customize->add_control(
				new WPCplant_Custom_Radio_Image_Control(
					$wp_customize,
					'wpcplant_layout',
					array(
						'settings' => 'wpcplant_layout',
						'section'  => 'wpcplant_layout',
						'label'    => __( 'General Layout', 'wpcplant' ),
						'priority' => 1,
						'choices'  => array(
							'right' => get_template_directory_uri() . '/assets/images/customizer/controls/2cr.png',
							'left'  => get_template_directory_uri() . '/assets/images/customizer/controls/2cl.png',
						),
					)
				)
			);
		}

		/**
		 * Get all of the WPCplant theme mods.
		 *
		 * @return array $wpcplant_theme_mods The WPCplant Theme Mods.
		 */
		public function get_wpcplant_theme_mods() {
			$wpcplant_theme_mods = array(
				'background_color'              => wpcplant_get_content_background_color(),
				'accent_color'                  => get_theme_mod( 'wpcplant_accent_color' ),
				'accent_color_hover'            => get_theme_mod( 'wpcplant_accent_color_hover' ),
				'hero_heading_color'            => get_theme_mod( 'wpcplant_hero_heading_color' ),
				'hero_text_color'               => get_theme_mod( 'wpcplant_hero_text_color' ),
				'header_background_color'       => get_theme_mod( 'wpcplant_header_background_color' ),
				'header_link_color'             => get_theme_mod( 'wpcplant_header_link_color' ),
				'header_link_color_hover'       => get_theme_mod( 'wpcplant_header_link_color_hover' ),
				'header_text_color'             => get_theme_mod( 'wpcplant_header_text_color' ),
				'footer_background_color'       => get_theme_mod( 'wpcplant_footer_background_color' ),
				'footer_link_color'             => get_theme_mod( 'wpcplant_footer_link_color' ),
				'footer_heading_color'          => get_theme_mod( 'wpcplant_footer_heading_color' ),
				'footer_heading_border_color'   => get_theme_mod( 'wpcplant_footer_heading_border_color' ),
				'footer_text_color'             => get_theme_mod( 'wpcplant_footer_text_color' ),
				'text_color'                    => get_theme_mod( 'wpcplant_text_color' ),
				'heading_color'                 => get_theme_mod( 'wpcplant_heading_color' ),
				'primary_color'                 => get_theme_mod( 'wpcplant_primary_color' ),
				'button_background_color'       => get_theme_mod( 'wpcplant_button_background_color' ),
				'button_background_color_hover' => get_theme_mod( 'wpcplant_button_background_color_hover' ),
				'button_text_color'             => get_theme_mod( 'wpcplant_button_text_color' ),
				'button_border_color'           => get_theme_mod( 'wpcplant_button_border_color' ),
				'button_alt_background_color'   => get_theme_mod( 'wpcplant_button_alt_background_color' ),
				'button_alt_text_color'         => get_theme_mod( 'wpcplant_button_alt_text_color' ),
			);

			return apply_filters( 'wpcplant_theme_mods', $wpcplant_theme_mods );
		}

		/**
		 * Get Customizer css.
		 *
		 * @return array $styles the css
		 * @see get_wpcplant_theme_mods()
		 */
		public function get_css() {
			$wpcplant_theme_mods = $this->get_wpcplant_theme_mods();

			$styles = '
                body {
                    --primary:' . $wpcplant_theme_mods['primary_color'] . ';
                    --background:' . $wpcplant_theme_mods['background_color'] . ';
                    --accent:' . $wpcplant_theme_mods['accent_color'] . ';
                    --accent_hover:' . $wpcplant_theme_mods['accent_color_hover'] . ';
                    --hero_heading:' . $wpcplant_theme_mods['hero_heading_color'] . ';
                    --hero_text:' . $wpcplant_theme_mods['hero_text_color'] . ';
                    --header_background:' . $wpcplant_theme_mods['header_background_color'] . ';
                    --header_link:' . $wpcplant_theme_mods['header_link_color'] . ';
                    --header_link_hover:' . $wpcplant_theme_mods['header_link_color_hover'] . ';
                    --header_text:' . $wpcplant_theme_mods['header_text_color'] . ';
                    --footer_background:' . $wpcplant_theme_mods['footer_background_color'] . ';
                    --footer_link:' . $wpcplant_theme_mods['footer_link_color'] . ';
                    --footer_heading:' . $wpcplant_theme_mods['footer_heading_color'] . ';
                    --footer_heading_border:' . $wpcplant_theme_mods['footer_heading_border_color'] . ';
                    --footer_text:' . $wpcplant_theme_mods['footer_text_color'] . ';
                    --text:' . $wpcplant_theme_mods['text_color'] . ';
                    --heading:' . $wpcplant_theme_mods['heading_color'] . ';
                    --button_background:' . $wpcplant_theme_mods['button_background_color'] . ';
                    --button_background_hover:' . $wpcplant_theme_mods['button_background_color_hover'] . ';
                    --button_text:' . $wpcplant_theme_mods['button_text_color'] . ';
                    --button_border:' . $wpcplant_theme_mods['button_border_color'] . ';
                    --button_alt_background:' . $wpcplant_theme_mods['button_alt_background_color'] . ';
                    --button_alt_text:' . $wpcplant_theme_mods['button_alt_text_color'] . ';
                }';

			return apply_filters( 'wpcplant_customizer_css', $styles );
		}


		/**
		 * Enqueue dynamic colors to use editor blocks.
		 *
		 * @since 2.4.0
		 */
		public function block_editor_customizer_css() {
			$wpcplant_theme_mods = $this->get_wpcplant_theme_mods();

			$styles = '';

			if ( is_admin() ) {
				$styles .= '
				.editor-styles-wrapper {
					background-color: ' . $wpcplant_theme_mods['background_color'] . ';
				}

				.editor-styles-wrapper table:not( .has-background ) th {
					background-color: ' . wpcplant_adjust_color_brightness( $wpcplant_theme_mods['background_color'], - 7 ) . ';
				}

				.editor-styles-wrapper table:not( .has-background ) tbody td {
					background-color: ' . wpcplant_adjust_color_brightness( $wpcplant_theme_mods['background_color'], - 2 ) . ';
				}

				.editor-styles-wrapper table:not( .has-background ) tbody tr:nth-child(2n) td,
				.editor-styles-wrapper fieldset,
				.editor-styles-wrapper fieldset legend {
					background-color: ' . wpcplant_adjust_color_brightness( $wpcplant_theme_mods['background_color'], - 4 ) . ';
				}

				.editor-post-title__block .editor-post-title__input,
				.editor-styles-wrapper h1,
				.editor-styles-wrapper h2,
				.editor-styles-wrapper h3,
				.editor-styles-wrapper h4,
				.editor-styles-wrapper h5,
				.editor-styles-wrapper h6 {
					color: ' . $wpcplant_theme_mods['heading_color'] . ';
				}

				/* WP <=5.3 */
				.editor-styles-wrapper .editor-block-list__block,
				/* WP >=5.4 */
				.editor-styles-wrapper .block-editor-block-list__block {
					color: ' . $wpcplant_theme_mods['text_color'] . ';
				}

				.editor-styles-wrapper a,
				.wp-block-freeform.block-library-rich-text__tinymce a {
					color: ' . $wpcplant_theme_mods['accent_color'] . ';
				}

				.editor-styles-wrapper a:focus,
				.wp-block-freeform.block-library-rich-text__tinymce a:focus {
					outline-color: ' . $wpcplant_theme_mods['accent_color'] . ';
				}

				body.post-type-post .editor-post-title__block::after {
					content: "";
				}';
			}

			wp_add_inline_style( 'wpcplant-gutenberg-blocks', apply_filters( 'wpcplant_gutenberg_block_editor_customizer_css', $styles ) );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function add_customizer_css() {
			wp_add_inline_style( 'wpcplant-style', $this->get_css() );
		}

		/**
		 * Layout classes
		 * Adds 'right-sidebar' and 'left-sidebar' classes to the body tag
		 *
		 * @param array $classes current body classes.
		 *
		 * @return string[]          modified body classes
		 * @since  1.0.0
		 */
		public function layout_class( $classes ) {
			$left_or_right = get_theme_mod( 'wpcplant_layout' );

			$classes[] = $left_or_right . '-sidebar';

			return $classes;
		}

		/**
		 * Add CSS for custom controls
		 *
		 * This function incorporates CSS from the Kirki Customizer Framework
		 *
		 * The Kirki Customizer Framework, Copyright Aristeides Stathopoulos (@aristath),
		 * is licensed under the terms of the GNU GPL, Version 2 (or later)
		 *
		 * @link https://github.com/reduxframework/kirki/
		 * @since  1.5.0
		 */
		public function customizer_custom_control_css() {
			?>
			<style>
				.customize-control-wpcplant-radio-image input[type=radio] {
					display: none;
				}

				.customize-control-wpcplant-radio-image label {
					display: block;
					width: 48%;
					float: left;
					margin-right: 4%;
				}

				.customize-control-wpcplant-radio-image label:nth-of-type(2n) {
					margin-right: 0;
				}

				.customize-control-wpcplant-radio-image img {
					opacity: .5;
				}

				.customize-control-wpcplant-radio-image input[type=radio]:checked + label img,
				.customize-control-wpcplant-radio-image img:hover {
					opacity: 1;
				}

			</style>
			<?php
		}

		/**
		 * Get site logo.
		 *
		 * @return string
		 * @since 2.1.5
		 */
		public function get_site_logo() {
			return wpcplant_site_title_or_logo( false );
		}

		/**
		 * Get site name.
		 *
		 * @return string
		 * @since 2.1.5
		 */
		public function get_site_name() {
			return get_bloginfo( 'name', 'display' );
		}

		/**
		 * Get site description.
		 *
		 * @return string
		 * @since 2.1.5
		 */
		public function get_site_description() {
			return get_bloginfo( 'description', 'display' );
		}

		/**
		 * Check if current page is using the Homepage template.
		 *
		 * @return bool
		 * @since 2.3.0
		 */
		public function is_homepage_template() {
			$template = get_post_meta( get_the_ID(), '_wp_page_template', true );

			if ( ! $template || 'template-homepage.php' !== $template || ! has_post_thumbnail( get_the_ID() ) ) {
				return false;
			}

			return true;
		}

	}

endif;

return new WPCplant_Customizer();
