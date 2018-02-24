<?php
/**
 * The default template for displaying header
 *
 * @package WEN Themes
 * @subpackage WEN Associate
 * @since WEN Associate 1.0
 */

  /**
   * wen_associate_action_doctype hook
   *
   * @hooked wen_associate_doctype - 10
   *
   */
  do_action( 'wen_associate_action_doctype' );?>

<head>
<base href="/" />
<?php
  /**
   * wen_associate_action_head hook
   *
   * @hooked wen_associate_head - 10
   *
   */
  do_action( 'wen_associate_action_head' );
?>
<?php wp_head(); ?>
</head>
<!-- 修改为ng-app标签，ng-controller标签可以在不同的应用中单独添加 -->
<body ng-app="app" <?php body_class(); ?>>

<?php
  /**
   * wen_associate_action_before hook
   *
   * @hooked wen_associate_page_start - 10
   *
   */
  do_action( 'wen_associate_action_before' );
?>

  <?php
    /**
     * wen_associate_action_before_header hook
     *
     * @hooked wen_associate_header_start - 10
     *
     */
    do_action( 'wen_associate_action_before_header' );
  ?>
    <?php
      /**
       * wen_associate_action_header hook
       *
       * @hooked wen_associate_site_branding - 10
       *
       */
      do_action( 'wen_associate_action_header' );
    ?>
  <?php
    /**
     * wen_associate_action_after_header hook
     *
     * @hooked wen_associate_header_end - 10
     * @hooked wen_associate_primary_navigation - 50
     *
     */
    do_action( 'wen_associate_action_after_header' );
  ?>

  <?php
    /**
     * wen_associate_action_before_content hook
     *
     * @hooked wen_associate_add_featured_slider - 5
     * @hooked wen_associate_add_breadcrumb - 7
     * @hooked wen_associate_content_start - 10
     *
     */
    do_action( 'wen_associate_action_before_content' );
  ?>
    <?php
      /**
       * wen_associate_action_content hook
       *
       */
      do_action( 'wen_associate_action_content' );
    ?>

