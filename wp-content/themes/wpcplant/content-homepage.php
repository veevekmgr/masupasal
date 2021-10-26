<?php
/**
 * The template used for displaying page content in template-homepage.php
 *
 * @package wpcplant
 */

?>
<?php
$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="<?php wpcplant_homepage_content_styles(); ?>"
     data-featured-image="<?php echo esc_url( $featured_image ); ?>">

	<?php
	/**
	 * Functions hooked in to wpcplant_page add_action
	 *
	 * @see wpcplant_homepage_header      - 10
	 * @see wpcplant_page_content         - 20
	 */
	do_action( 'wpcplant_homepage' );
	?>

</div><!-- #post-## -->
