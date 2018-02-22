<?php

if ( ! function_exists( 'wen_associate_doctype' ) ) :
  /**
   * Doctype Declaration
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_doctype() {
    ?><!DOCTYPE html> <html <?php language_attributes(); ?>><?php
  }
endif;
add_action( 'wen_associate_action_doctype', 'wen_associate_doctype', 10 );


if ( ! function_exists( 'wen_associate_head' ) ) :
  /**
   * Header Codes
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_head() {
    ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php
  }
endif;
add_action( 'wen_associate_action_head', 'wen_associate_head', 10 );

if ( ! function_exists( 'wen_associate_page_start' ) ) :
  /**
   * Page Start
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_page_start() {
    // Get site layout
    $site_layout = wen_associate_get_option( 'site_layout' );
    ?>
    <?php if ( 'boxed' == $site_layout ): ?>
    <div id="page" class="hfeed site container">
    <?php else: ?>
    <div id="page" class="hfeed site container-fluid">
    <?php endif ?>
    <?php
  }
endif;
add_action( 'wen_associate_action_before', 'wen_associate_page_start' );


if ( ! function_exists( 'wen_associate_skip_to_content' ) ) :
  /**
   * Skip to content
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_skip_to_content() {
    ?><a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'wen-associate' ); ?></a><?php
  }
endif;
add_action( 'wen_associate_action_before', 'wen_associate_skip_to_content', 15 );


if ( ! function_exists( 'wen_associate_page_end' ) ) :
  /**
   * Page Start
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_page_end() {
    ?></div><!-- #page --><?php
  }
endif;
add_action( 'wen_associate_action_after', 'wen_associate_page_end' );


if ( ! function_exists( 'wen_associate_header_start' ) ) :
  /**
   * Header Start
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_header_start() {
    ?><header id="masthead" class="site-header" role="banner"><div class="container"><?php
  }
endif;
add_action( 'wen_associate_action_before_header', 'wen_associate_header_start' );

if ( ! function_exists( 'wen_associate_header_end' ) ) :
  /**
   * Header End
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_header_end() {
    ?></div><!-- .container --></header><!-- #masthead --><?php
  }
endif;
add_action( 'wen_associate_action_after_header', 'wen_associate_header_end' );


if ( ! function_exists( 'wen_associate_footer_start' ) ) :
  /**
   * Footer Start
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_footer_start() {
    $footer_status = apply_filters( 'wen_associate_filter_footer_status', true );
    if ( true !== $footer_status) {
      return;
    }
    ?><footer id="colophon" class="site-footer" role="contentinfo" ><div class="container"><?php
  }
endif;
add_action( 'wen_associate_action_before_footer', 'wen_associate_footer_start' );


if ( ! function_exists( 'wen_associate_footer_end' ) ) :
  /**
   * Footer End
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_footer_end() {
    $footer_status = apply_filters( 'wen_associate_filter_footer_status', true );
    if ( true !== $footer_status) {
      return;
    }
    ?></div><!-- .container --></footer><!-- #colophon --><?php
  }
endif;
add_action( 'wen_associate_action_after_footer', 'wen_associate_footer_end' );


if ( ! function_exists( 'wen_associate_content_start' ) ) :
  /**
   * Content Start
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_content_start() {
    ?><div id="content" class="site-content"><div class="container"><div class="row"><?php
  }
endif;
add_action( 'wen_associate_action_before_content', 'wen_associate_content_start' );


if ( ! function_exists( 'wen_associate_content_end' ) ) :
  /**
   * Content End
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_content_end() {
    ?></div><!-- .row --></div><!-- .container --></div><!-- #content --><?php
  }
endif;
add_action( 'wen_associate_action_after_content', 'wen_associate_content_end' );

