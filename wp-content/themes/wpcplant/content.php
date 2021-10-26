<?php
/**
 * Template used to display post content.
 *
 * @package wpcplant
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Functions hooked in to wpcplant_loop_post action.
	 *
	 * @see wpcplant_post_header          - 10
	 * @see wpcplant_post_content         - 30
	 * @see wpcplant_post_taxonomy        - 40
	 */
	do_action( 'wpcplant_loop_post' );
	?>

</article><!-- #post-## -->
