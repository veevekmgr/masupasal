<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package wpcplant
 */

?>

</div><!-- .col-full -->
</div><!-- #content -->

<?php do_action( 'wpcplant_before_footer' ); ?>

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="col-full">

		<?php
		/**
		 * Functions hooked in to wpcplant_footer action
		 *
		 * @see wpcplant_footer_widgets - 10
		 * @see wpcplant_credit         - 20
		 */
		do_action( 'wpcplant_footer' );
		?>

    </div><!-- .col-full -->
</footer><!-- #colophon -->

<?php do_action( 'wpcplant_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
