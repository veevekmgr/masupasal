<?php
/**
 * The template for displaying search results pages.
 *
 * @package wpcplant
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<?php
				get_template_part( 'loop' );

			else :

				get_template_part( 'content', 'none' );

			endif;
			?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
do_action( 'wpcplant_sidebar' );
get_footer();
