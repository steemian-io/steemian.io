<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WEN Associate
 */
?>


  <?php
    /**
     * wen_associate_action_after_content hook
     *
     * @hooked wen_associate_content_end - 10
     *
     */
    do_action( 'wen_associate_action_after_content' );
  ?>


  <?php
    /**
     * wen_associate_action_before_footer hook
     *
     * @hooked wen_associate_add_front_bottom_widget_area - 2
     * @hooked wen_associate_add_footer_widgets - 5
     * @hooked wen_associate_footer_start - 10
     *
     */
    do_action( 'wen_associate_action_before_footer' );
  ?>
    <?php
      /**
       * wen_associate_action_footer hook
       *
       * @hooked wen_associate_site_info - 10
       *
       */
      do_action( 'wen_associate_action_footer' );
    ?>
  <?php
    /**
     * wen_associate_action_after_footer hook
     *
     * @hooked wen_associate_footer_end - 10
     *
     */
    do_action( 'wen_associate_action_after_footer' );
  ?>


<?php
  /**
   * wen_associate_action_after hook
   *
   * @hooked wen_associate_page_end - 10
   * @hooked wen_associate_footer_goto_top - 20
   *
   */
  do_action( 'wen_associate_action_after' );
?>


<?php wp_footer(); ?>
</body>
</html>
