<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package wpcplant
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to wpcplant_page add_action
	 *
	 * @see wpcplant_page_header          - 10
	 * @see wpcplant_page_content         - 20
	 */
	do_action( 'wpcplant_page' );
	?>
</article><!-- #post-## -->
