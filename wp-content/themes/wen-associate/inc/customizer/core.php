<?php

add_filter( 'wen_associate_filter_default_theme_options', 'wen_associate_core_default_options' );

/**
 * Core defaults.
 *
 * @since  WEN Associate 1.0
 */
if( ! function_exists( 'wen_associate_core_default_options' ) ):

  function wen_associate_core_default_options( $input ){

    $input['custom_css']         = '';
    $input['search_placeholder'] = __( 'Search...', 'wen-associate' );

    return $input;
  }

endif;


add_filter( 'wen_associate_theme_options_args', 'wen_associate_core_theme_options_args' );


/**
 * Add core options.
 *
 * @since  WEN Associate 1.0
 */
if( ! function_exists( 'wen_associate_core_theme_options_args' ) ):

  function wen_associate_core_theme_options_args( $args ){

    // Create theme option panel
    $args['panels']['theme_option_panel']['title'] = __( 'Themes Options', 'wen-associate' );

    // Advance Section
    $args['panels']['theme_option_panel']['sections']['section_advanced'] = array(
      'title'    => __( 'Advanced Options', 'wen-associate' ),
      'priority' => 1000,
      'fields'   => array(
        'custom_css' => array(
          'title'                => __( 'Custom CSS', 'wen-associate' ),
          'type'                 => 'textarea',
          'sanitize_callback'    => 'wp_filter_nohtml_kses',
          'sanitize_js_callback' => 'wp_filter_nohtml_kses',
        ),
      )
    );

    // Search Section
    $args['panels']['theme_option_panel']['sections']['section_search'] = array(
      'title'    => __( 'Search Options', 'wen-associate' ),
      'priority' => 70,
      'fields'   => array(
        'search_placeholder' => array(
          'title'             => __( 'Search Placeholder', 'wen-associate' ),
          'type'              => 'text',
          'sanitize_callback' => 'sanitize_text_field',
        ),
      )
    );
    return $args;
  }

endif;
