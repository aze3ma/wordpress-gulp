<?php
/**
 * Template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package wordpress-gulp
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

<div id="comments" class="Comments">

  <?php if ( have_comments() ) : ?>
    <h2 class="Comments-title">
      <?php
        printf(
          esc_html( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'wpg' ) ),
          number_format_i18n( get_comments_number() ),
          '<span>' . get_the_title() . '</span>'
        );
      ?>
    </h2>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
      <nav id="comment-nav-above" class="Pager" role="navigation" aria-labelledby="comment-nav-above-heading">
        <p id="comment-nav-above-heading" class="screen-reader-text">Comment navigation</p>
        <ul class="Pager-list u-cf">
          <li class="Pager-listItem Pager-previous"><?php previous_comments_link( __( 'Older Comments', 'wpg' ) ); ?></li>
          <li class="Pager-listItem Pager-next"><?php next_comments_link( __( 'Newer Comments', 'wpg' ) ); ?></li>
        </ul>
      </nav>
    <?php endif; // check for comment navigation ?>

    <ol class="Comments-list">
      <?php
        wp_list_comments( array(
          'style'      => 'ol',
          'short_ping' => true,
        ) );
      ?>
    </ol>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
      <nav id="comment-nav-below" class="Pager" role="navigation" aria-labelledby="comment-nav-below-heading">
        <p id="comment-nav-below-heading" class="screen-reader-text">Comment navigation</p>
        <ul class="Pager-list u-cf">
          <li class="Pager-listItem Pager-previous"><?php previous_comments_link( __( 'Older Comments', 'wpg' ) ); ?></li>
          <li class="Pager-listItem Pager-next"><?php next_comments_link( __( 'Newer Comments', 'wpg' ) ); ?></li>
        </ul>
      </nav>
    <?php endif; // check for comment navigation ?>

  <?php endif; // have_comments() ?>

  <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
  ?>
    <p class="Comments-closed"><?php esc_html_e( 'Comments are closed.', 'wpg' ); ?></p>
  <?php endif; ?>

  <?php comment_form(array( 'comment_notes_after' => '' )); ?>

</div>
