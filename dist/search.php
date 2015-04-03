<?php
/**
 * Template for displaying search results pages.
 *
 * @package wordpress-gulp
 */

get_header(); ?>

  <main role="main">

  <?php if ( have_posts() ) : ?>

    <header class="PageHeader">
      <h1 class="PageHeader-title"><?php printf( __( 'Search Results for: %s' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
    </header>

    <?php while ( have_posts() ) : the_post(); ?>

      <?php
      /**
       * Run the loop for the search to output the results.
       * If you want to overload this in a child theme then include a file
       * called content-search.php and that will be used instead.
       */
      get_template_part( 'content', 'search' );
      ?>

    <?php endwhile; ?>

    <?php wpg_paging_nav(); ?>

  <?php else : ?>

    <?php get_template_part( 'content', 'none' ); ?>

  <?php endif; ?>

  </main>

<?php get_footer(); ?>