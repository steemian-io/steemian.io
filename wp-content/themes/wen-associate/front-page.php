<?php
/**
 * Front Page.
 *
 * @package WEN Associate
 *
 */

get_header(); ?>

  <div id="primary" class="content-area col-sm-12" >
    <main id="main" class="site-main" role="main">

      <?php
      /**
       * wen_associate_action_front_page hook
       */
      do_action( 'wen_associate_action_front_page' );
      ?>

    </main><!-- #main -->
  </div><!-- #primary -->

<?php get_footer(); ?>
