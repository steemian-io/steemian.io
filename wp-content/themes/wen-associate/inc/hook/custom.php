<?php

if ( ! function_exists( 'wen_associate_custom_body_class' ) ) :
  /**
   * Custom body class
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_custom_body_class( $input ) {

    // Site layout
    $site_layout = wen_associate_get_option( 'site_layout' );
    $input[] = 'site-layout-' . esc_attr( $site_layout );

    // Header layout
    $header_layout = wen_associate_get_option( 'header_layout' );
    $input[] = 'header-' . esc_attr( $header_layout );

    // Global layout
    global $post;
    $global_layout = wen_associate_get_option( 'global_layout' );
    // Check if single
    if ( $post  && is_singular() ) {
      $post_options = get_post_meta( $post->ID, 'theme_settings', true );
      if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
        $global_layout = $post_options['post_layout'];
      }
    }

    $input[] = 'global-layout-' . esc_attr( $global_layout );

    // Hide content condition
    if (
      is_front_page() &&
      ! is_active_sidebar( 'sidebar-front-page-main' ) &&
      ! is_active_sidebar( 'sidebar-front-page-lower-left' ) &&
      ! is_active_sidebar( 'sidebar-front-page-lower-right' ) &&
      is_active_sidebar( 'sidebar-front-page-bottom' )
      ) {
      $input[] = 'hide-content';
    }


    return $input;
  }
endif;
add_filter( 'body_class', 'wen_associate_custom_body_class' );


if ( ! function_exists( 'wen_associate_custom_content_class' ) ) :

  /**
   * Custom Primary class
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_custom_content_class( $input ) {

    global $post;
    $global_layout = wen_associate_get_option( 'global_layout' );
    // Check if single
    if ( $post  && is_singular() ) {
      $post_options = get_post_meta( $post->ID, 'theme_settings', true );
      if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
        $global_layout = $post_options['post_layout'];
      }
    }

    $new_class = '';

    switch ( $global_layout ) {
      case 'three-columns':
        $new_class = 'col-sm-6';
        break;

      case 'no-sidebar':
        $new_class = 'col-sm-12';
        break;

      case 'left-sidebar':
      case 'right-sidebar':
        $new_class = 'col-sm-8';
        break;

      default:
        break;
    }
    if ( ! empty( $new_class ) ) {
      $input[] = $new_class;
    }

    return $input;
  }
endif;
add_filter( 'wen_associate_filter_content_class', 'wen_associate_custom_content_class' );


if ( ! function_exists( 'wen_associate_custom_sidebar_primary_class' ) ) :
  /**
   * Custom Sidebar Primary class
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_custom_sidebar_primary_class( $input ) {


    global $post;
    $global_layout = wen_associate_get_option( 'global_layout' );
    // Check if single
    if ( $post && is_singular() ) {
      $post_options = get_post_meta( $post->ID, 'theme_settings', true );
      if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
        $global_layout = $post_options['post_layout'];
      }
    }

    $new_class = '';

    switch ( $global_layout ) {
      case 'three-columns':
        $new_class = 'col-sm-3';
        break;

      case 'left-sidebar':
      case 'right-sidebar':
        $new_class = 'col-sm-4';
        break;

      default:
        break;
    }
    if ( ! empty( $new_class ) ) {
      $input[] = $new_class;
    }

    return $input;
  }
endif;
add_filter( 'wen_associate_filter_sidebar_primary_class', 'wen_associate_custom_sidebar_primary_class' );


if ( ! function_exists( 'wen_associate_custom_sidebar_secondary_class' ) ) :

  /**
   * Custom Sidebar Secondary class
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_custom_sidebar_secondary_class( $input ) {

    global $post;
    $global_layout = wen_associate_get_option( 'global_layout' );
    // Check if single
    if ( $post  && is_singular() ) {
      $post_options = get_post_meta( $post->ID, 'theme_settings', true );
      if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
        $global_layout = $post_options['post_layout'];
      }
    }

    $new_class = '';

    switch ( $global_layout ) {
      case 'three-columns':
        $new_class = 'col-sm-3';
        break;

      default:
        break;
    }
    if ( ! empty( $new_class ) ) {
      $input[] = $new_class;
    }

    return $input;
  }
endif;

add_filter( 'wen_associate_filter_sidebar_secondary_class', 'wen_associate_custom_sidebar_secondary_class' );



if ( ! function_exists( 'wen_associate_custom_content_width' ) ) :

  /**
   * Custom Content Width
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_custom_content_width( $input ) {

    global $post, $wp_query, $content_width;

    $global_layout = wen_associate_get_option( 'global_layout' );

    // Check if single
    if ( $post  && is_singular() ) {
      $post_options = get_post_meta( $post->ID, 'theme_settings', true );
      if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
        $global_layout = $post_options['post_layout'];
      }
    }
    switch ( $global_layout ) {

      case 'no-sidebar':
        $content_width = 1140;
        break;

      case 'three-columns':
        $content_width = 555;
        break;

      case 'left-sidebar':
      case 'right-sidebar':
        $content_width = 750;
        break;

      default:
        break;
    }

  }
endif;

add_filter( 'template_redirect', 'wen_associate_custom_content_width' );


if ( ! function_exists( 'wen_associate_implement_front_page_widget_area' ) ) :

  /**
   * Implement front page widget area
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_implement_front_page_widget_area(){

    ?>
    <?php if ( is_active_sidebar( 'sidebar-front-page-main' ) ): ?>
      <div id="sidebar-front-page-main" class="widget-area">
        <?php dynamic_sidebar( 'sidebar-front-page-main' ); ?>
      </div><!-- #sidebar-front-page-main -->
    <?php endif ?>
    <?php if ( is_active_sidebar( 'sidebar-front-page-lower-left' ) || is_active_sidebar( 'sidebar-front-page-lower-right' ) ): ?>
      <div id="sidebar-front-page-lower">
        <div class="row">
          <div class="col-sm-8">
            <div id="sidebar-front-page-lower-left">
              <?php dynamic_sidebar( 'sidebar-front-page-lower-left' ); ?>
            </div><!-- #sidebar-front-page-lower-left -->
          </div><!-- .col-sm-8 -->
          <div class="col-sm-4">
            <div id="sidebar-front-page-lower-right">
              <?php dynamic_sidebar( 'sidebar-front-page-lower-right' ); ?>
            </div><!-- #sidebar-front-page-lower-right -->
            </div><!-- .col-sm-4 -->
        </div><!-- .row -->
      </div><!-- #sidebar-front-page-lower -->
    <?php endif ?>
    <?php

  }

endif;

add_action( 'wen_associate_action_front_page', 'wen_associate_implement_front_page_widget_area' );



if ( ! function_exists( 'wen_associate_add_front_bottom_widget_area' ) ) :

  /**
   * Add front bottom widget area
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_add_front_bottom_widget_area() {

    if ( ! is_front_page() ) {
      return;
    }
    ?>
    <?php if ( is_active_sidebar( 'sidebar-front-page-bottom' ) ): ?>
      <div id="sidebar-front-page-bottom" class="widget-area">
        <div class="container">
        <?php dynamic_sidebar( 'sidebar-front-page-bottom' ); ?>
        </div><!-- .container -->
      </div><!-- #sidebar-front-page-bottom -->
    <?php endif ?>
    <?php

  }
endif;

add_action( 'wen_associate_action_before_footer', 'wen_associate_add_front_bottom_widget_area', 2 );

if ( ! function_exists( 'wen_associate_add_author_bio_in_single' ) ) :

  /**
   * Display Author bio
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_add_author_bio_in_single() {

    $author_bio_in_single = wen_associate_get_option( 'author_bio_in_single' );
    if ( 1 != $author_bio_in_single ) {
      return;
    }
    get_template_part( 'template-parts/single-author', 'bio' );

  }
endif;

add_action( 'wen_associate_author_bio', 'wen_associate_add_author_bio_in_single' );


if ( ! function_exists( 'wen_associate_check_front_widget_status' ) ) :

  /**
   * Filter for front page
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_check_front_widget_status( $template ) {

    if ( ! is_active_sidebar( 'sidebar-front-page-main' ) && ! is_active_sidebar( 'sidebar-front-page-lower-left' ) && ! is_active_sidebar( 'sidebar-front-page-lower-right' ) && ! is_active_sidebar( 'sidebar-front-page-bottom' ) ) {
      return '';
    }
    return $template;

  }
endif;

add_filter( 'frontpage_template', 'wen_associate_check_front_widget_status' );

if ( ! function_exists( 'wen_associate_featured_image_instruction' ) ) :

  /**
   * Message to show in the Featured Image Meta box.
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_featured_image_instruction( $content, $post_id ) {

  	$allowed = array( 'post', 'page' );
    if ( in_array( get_post_type( $post_id ), $allowed ) ) {
      $content .= '<br/><strong>' . __( 'Recommended Image Sizes', 'wen-associate' ) . ':</strong><br/>';
      $content .= __( 'Featured Slider', 'wen-associate' ).' : 1300px X 440px';
    }

    return $content;

  }

endif;
add_filter( 'admin_post_thumbnail_html', 'wen_associate_featured_image_instruction', 10, 2 );

