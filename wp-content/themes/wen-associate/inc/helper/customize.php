<?php

if( ! function_exists( 'wen_associate_get_featured_slider_transition_effects' ) ) :

  /**
   * Returns the featured slider transition effects.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_featured_slider_transition_effects(){

    $choices = array(
      'fade'       => _x( 'fade', 'Transition Effect', 'wen-associate' ),
      'fadeout'    => _x( 'fadeout', 'Transition Effect', 'wen-associate' ),
      'none'       => _x( 'none', 'Transition Effect', 'wen-associate' ),
      'scrollHorz' => _x( 'scrollHorz', 'Transition Effect', 'wen-associate' ),
    );
    $output = apply_filters( 'wen_associate_filter_featured_slider_transition_effects', $choices );
    if ( ! empty( $output ) ) {
      ksort( $output );
    }
    return $output;

  }

endif;


if( ! function_exists( 'wen_associate_get_featured_slider_content_options' ) ) :

  /**
   * Returns the featured slider content options.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_featured_slider_content_options(){

    $choices = array(
      'home-page-only' => __( 'Home Page Only', 'wen-associate' ),
      'home-blog-page' => __( 'Home Page + Blog Page', 'wen-associate' ),
      'entire-site'    => __( 'Entire Site', 'wen-associate' ),
      'disabled'       => __( 'Disabled', 'wen-associate' ),
    );
    $output = apply_filters( 'wen_associate_filter_featured_slider_content_options', $choices );
    if ( ! empty( $output ) ) {
      ksort( $output );
    }
    return $output;


  }

endif;


if( ! function_exists( 'wen_associate_get_featured_slider_type' ) ) :

  /**
   * Returns the featured slider type.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_featured_slider_type(){

    $choices = array(
      'featured-category' => __( 'Featured Category', 'wen-associate' ),
    );
    $output = apply_filters( 'wen_associate_filter_featured_slider_type', $choices );
    if ( ! empty( $output ) ) {
      ksort( $output );
    }
    return $output;


  }

endif;


if( ! function_exists( 'wen_associate_get_global_layout_options' ) ) :

  /**
   * Returns global layout options.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_global_layout_options(){

    $choices = array(
      'left-sidebar'  => __( 'Primary Sidebar - Content', 'wen-associate' ),
      'right-sidebar' => __( 'Content - Primary Sidebar', 'wen-associate' ),
      'three-columns' => __( 'Three Columns', 'wen-associate' ),
      'no-sidebar'    => __( 'No Sidebar', 'wen-associate' ),
    );
    $output = apply_filters( 'wen_associate_filter_layout_options', $choices );
    return $output;

  }

endif;


if( ! function_exists( 'wen_associate_get_site_layout_options' ) ) :

  /**
   * Returns site options.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_site_layout_options(){

    $choices = array(
      'fluid' => __( 'Fluid', 'wen-associate' ),
      'boxed' => __( 'Boxed', 'wen-associate' ),
    );
    $output = apply_filters( 'wen_associate_filter_site_layout_options', $choices );
    if ( ! empty( $output ) ) {
      ksort( $output );
    }
    return $output;

  }

endif;


if( ! function_exists( 'wen_associate_get_archive_layout_options' ) ) :

  /**
   * Returns archive layout options.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_archive_layout_options(){

    $choices = array(
      'full'          => __( 'Full Post', 'wen-associate' ),
      'excerpt'       => __( 'Excerpt Only', 'wen-associate' ),
      'excerpt-thumb' => __( 'Excerpt and Thumbnail', 'wen-associate' ),
    );
    $output = apply_filters( 'wen_associate_filter_archive_layout_options', $choices );
    if ( ! empty( $output ) ) {
      ksort( $output );
    }
    return $output;


  }

endif;


if( ! function_exists( 'wen_associate_get_image_sizes_options' ) ) :

  /**
   * Returns archive layout options.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_image_sizes_options(){

    global $_wp_additional_image_sizes;
    $get_intermediate_image_sizes = get_intermediate_image_sizes();
    $choices = array();
    $choices['disable'] = __( 'No Image', 'wen-associate' );
    foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
      $choices[ $_size ] = $_size . ' ('. get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
    }
    $choices['full'] = __( 'full (original)', 'wen-associate' );
    if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {

      foreach ($_wp_additional_image_sizes as $key => $size ) {
        $choices[ $key ] = $key . ' ('. $size['width'] . 'x' . $size['height'] . ')';
      }

    }
    return $choices;

  }

endif;



if( ! function_exists( 'wen_associate_get_single_image_alignment_options' ) ) :

  /**
   * Returns single image options.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_single_image_alignment_options(){

    $choices = array(
      'none'   => _x( 'None', 'Alignment', 'wen-associate' ),
      'left'   => _x( 'Left', 'Alignment', 'wen-associate' ),
      'center' => _x( 'Center', 'Alignment', 'wen-associate' ),
      'right'  => _x( 'Right', 'Alignment', 'wen-associate' ),
    );
    return $choices;

  }

endif;


if( ! function_exists( 'wen_associate_get_pagination_type_options' ) ) :

  /**
   * Returns pagination type options.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_pagination_type_options(){

    $choices = array(
      'default' => __( 'Default (Older Post / Newer Post)', 'wen-associate' ),
      'numeric' => __( 'Numeric', 'wen-associate' ),
    );
    return $choices;

  }

endif;


if( ! function_exists( 'wen_associate_get_breadcrumb_type_options' ) ) :

  /**
   * Returns breadcrumb type options.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_breadcrumb_type_options(){

    $choices = array(
      'disabled' => __( 'Disabled', 'wen-associate' ),
      'simple'   => __( 'Simple', 'wen-associate' ),
      'advanced' => __( 'Advanced', 'wen-associate' ),
    );
    return $choices;

  }

endif;

if( ! function_exists( 'wen_associate_get_header_layout_options' ) ) :

  /**
   * Returns header layout options.
   *
   * @since WEN Associate 1.0
   */
  function wen_associate_get_header_layout_options(){

    $choices = array(
      'layout-1' => get_template_directory_uri() . '/images/header-layout-1.png',
      'layout-2' => get_template_directory_uri() . '/images/header-layout-2.png',
    );
    return $choices;

  }

endif;

if( ! function_exists( 'wen_associate_sanitize_positive_integer' ) ) :

  /**
   * Sanitize positive integer
   *
   * @since 1.0
   */
  function wen_associate_sanitize_positive_integer( $input, $setting ) {

    $input = absint( $input );

    // If the input is an absolute integer, return it;
    // otherwise, return the default
    return ( $input ? $input : $setting->default );

  }

endif;

if( ! function_exists( 'wen_associate_is_simple_breadcrumb_active' ) ) :

	/**
	 * Check if simple breadcrumb is active
	 *
	 * @since  WEN Associate 1.0
	 */
  function wen_associate_is_simple_breadcrumb_active( $control ) {

    if ( 'simple' == $control->manager->get_setting( 'theme_options[breadcrumb_type]' )->value() )
    {
      return true;
    } else {
      return false;
    }

  }

endif;
