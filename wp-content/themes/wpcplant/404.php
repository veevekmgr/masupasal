<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package wpcplant
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<div class="error-404 not-found">
				<div class="page-content">
					<header class="page-header">
						<p class="error-heading"><?php echo esc_html__( '404', 'wpcplant' ); ?></p>
						<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'wpcplant' ); ?></h1>
					</header><!-- .page-header -->
					<p><?php esc_html_e( 'Nothing was found at this location. Try searching, or check out the links below.', 'wpcplant' ); ?></p>
				</div><!-- .page-content -->
			</div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
