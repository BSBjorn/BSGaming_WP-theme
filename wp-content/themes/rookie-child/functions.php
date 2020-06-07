<?php
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);

function enqueue_child_theme_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

add_filter('show_admin_bar', '__return_false');

/**
 * Display footer credit
 */
if ( ! function_exists( 'rookie_footer_credit' ) ):
  function rookie_footer_credit() {
    ?>
    <div class="site-credit">
      <?php echo apply_filters( 'rookie_footer_credit', '<a href="http://bitsector.no/">' . sprintf( __( 'Designed by %s', 'rookie' ), 'BitSector' ) . '</a>' ); ?>
    </div><!-- .site-info -->
    <?php
  }
  endif;