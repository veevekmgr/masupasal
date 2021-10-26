<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package wpcplant
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area" aria-label="<?php esc_attr_e( 'Post Comments', 'wpcplant' ); ?>">

	<?php
	if ( have_comments() ) :
		?>
        <h2 class="comments-title">
			<?php
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			printf(
			/* translators: 1: number of comments, 2: post title */
				esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'wpcplant' ) ),
				number_format_i18n( get_comments_number() ),
				'<span>' . get_the_title() . '</span>'
			);
			// phpcs:enable
			?>
        </h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
        <nav id="comment-nav-above" class="comment-navigation" role="navigation"
             aria-label="<?php esc_attr_e( 'Comment Navigation Above', 'wpcplant' ); ?>">
            <span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'wpcplant' ); ?></span>
            <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'wpcplant' ) ); ?></div>
            <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'wpcplant' ) ); ?></div>
        </nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation.
		?>

        <ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'callback'   => 'wpcplant_comment',
				)
			);
			?>
        </ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
        <nav id="comment-nav-below" class="comment-navigation" role="navigation"
             aria-label="<?php esc_attr_e( 'Comment Navigation Below', 'wpcplant' ); ?>">
            <span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'wpcplant' ); ?></span>
            <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'wpcplant' ) ); ?></div>
            <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'wpcplant' ) ); ?></div>
        </nav><!-- #comment-nav-below -->
	<?php
	endif; // Check for comment navigation.

	endif;

	if ( ! comments_open() && 0 !== intval( get_comments_number() ) && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'wpcplant' ); ?></p>
	<?php
	endif;

	$args = apply_filters(
		'wpcplant_comment_form_args',
		array(
			'title_reply_before' => '<span id="reply-title" class="gamma comment-reply-title">',
			'title_reply_after'  => '</span>',
			// Change the title of send button
			'label_submit' => __( 'Submit now', 'wpcplant' ),
			// Change the title of the reply section
			'title_reply' => __( 'Write a Reply or Comment', 'wpcplant' ),
		)
	);

	comment_form( $args );
	?>

</section><!-- #comments -->
