<?php
/**
 * Template used to display post content on single pages.
 *
 * @package wpcplant
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	do_action( 'wpcplant_single_post_top' );

	/**
	 * Functions hooked into wpcplant_single_post add_action
	 *
	 * @see wpcplant_post_header          - 10
	 * @see wpcplant_post_content         - 30
	 */
	do_action( 'wpcplant_single_post' );

	/**
	 * Functions hooked in to wpcplant_single_post_bottom action
	 *
	 * @see wpcplant_post_nav         - 10
	 * @see wpcplant_display_comments - 20
	 */
	do_action( 'wpcplant_single_post_bottom' );
	?>

</article><!-- #post-## -->
