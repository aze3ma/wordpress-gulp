<?php
/**
 * Template for displaying the footer.
 *
 * Contains the closing of the main container div and all content after
 *
 * @package wordpress-gulp
 */
?>

    </div>

    <footer class="SiteFooter" role="contentinfo">
      <div class="Container u-cf">
        <?php wp_nav_menu( array(
          'theme_location' => 'site_footer_right',
          'menu' => 'Site Footer - Right',
          'container' => '',
          'menu_class' => 'SocialIcons-list',
          'items_wrap' => '<ul class="%2$s">%3$s</ul>'
        ) ); ?>

        <p class="SiteFooter-copyright">
          Copyright <time datetime="<?php echo date( 'Y' ); ?>"><?php echo date( 'Y' ); ?></time> -
          <a class="u-linkClean" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
            <?php bloginfo( 'name' ); ?>
          </a>
        </p>
      </div>
    </footer>

  </div>

  <?php wp_footer(); ?>

</body>
</html>
