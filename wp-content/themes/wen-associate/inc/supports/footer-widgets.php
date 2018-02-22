<?php
$support = get_theme_support( 'footer-widgets' );
if ( empty( $support ) ){
  return;
}
// Number of footer widgets
$footer_widgets_number = absint( $support[0] );

if ( $footer_widgets_number < 1 || $footer_widgets_number > 4 ) {
  return;
}

// Hook footer widgets functions

// Init widgets
add_action( 'widgets_init', 'wen_associate_footer_widgets_init' );
add_action( 'wen_associate_action_before_footer', 'wen_associate_add_footer_widgets', 5 );
add_filter( 'wen_associate_filter_footer_widgets', 'wen_associate_check_footer_widgets_status' );
add_filter( 'wen_associate_filter_footer_widget_class', 'wen_associate_custom_footer_widget_class' );



/**
 * Register footer widgets.
 *
 * @since WEN Associate 1.0
 *
 */
if( ! function_exists( 'wen_associate_footer_widgets_init' ) ):

  function wen_associate_footer_widgets_init() {

    $support = get_theme_support( 'footer-widgets' );

    if ( empty( $support ) ){
      return;
    }
    // Number of footer widgets
    $footer_widgets_number = absint( $support[0] );

    if ( $footer_widgets_number < 1 ) {
      return;
    }

    for( $i = 1; $i <= $footer_widgets_number; $i++ ) {
      register_sidebar( array(
        'name'          => sprintf( __( 'Footer Widget %d', 'wen-associate' ), $i ),
        'id'            => sprintf( 'footer-%d', $i ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
      ) );
    } //end for

  }

endif;

/**
 * Add footer widgets.
 *
 * @since WEN Associate 1.0
 *
 */
if( ! function_exists( 'wen_associate_add_footer_widgets' ) ):

  function wen_associate_add_footer_widgets() {

    $flag_apply_footer_widgets_content = apply_filters('wen_associate_filter_footer_widgets', true );
    if ( true != $flag_apply_footer_widgets_content ) {
      return false;
    }

    $support = get_theme_support( 'footer-widgets' );

    if ( empty( $support ) ){
      return;
    }
    // Number of footer widgets
    $footer_widgets_number = absint( $support[0] );

    if ( $footer_widgets_number < 1 ) {
      return;
    }

    $args = array(
      'container' => 'div',
      'before'    => '<div class="container"><div class="row">',
      'after'     => '</div><!-- .row --></div><!-- .container -->',
    );
    $footer_widgets_content = wen_associate_get_footer_widgets_content( $footer_widgets_number, $args );
    echo $footer_widgets_content;
    return;

  }

endif;


/**
 * Render footer widgets content.
 *
 * @since WEN Associate 1.0
 *
 */
if( ! function_exists( 'wen_associate_get_footer_widgets_content' ) ):

  function wen_associate_get_footer_widgets_content( $number, $args = array() ) {

    $number = absint( $number );
    if ( $number < 1 ) {
      return;
    }
    //Defaults
    $args = wp_parse_args( (array) $args, array(
      'container'       => 'div',
      'container_class' => '',
      'container_style' => '',
      'container_id'    => 'footer-widgets',
      'wrap_class'      => 'footer-widget-area',
      'before'          => '',
      'after'           => '',
      ) );
    $args = apply_filters( 'wen_associate_filter_footer_widgets_args', $args );
    ob_start();
    ///////
    $container_open = '';
    $container_close = '';

    if ( ! empty( $args['container_class'] ) || ! empty( $args['container_id'] ) ) {
      $container_open = sprintf(
        '<%s %s %s %s>',
        $args['container'],
        ( $args['container_class'] ) ? 'class="' . $args['container_class'] . '"':'',
        ( $args['container_id'] ) ? 'id="' . $args['container_id'] . '"':'',
        ( $args['container_style'] ) ? 'style="' . esc_attr( $args['container_style'] ) . '"':''
        );
    }
    if ( ! empty( $args['container_class'] ) || ! empty( $args['container_id'] ) ) {
      $container_close = sprintf(
        '</%s>',
        $args['container']
        );
    }

    echo $container_open;

    echo $args['before'];

    for($i = 1; $i <= $number ;$i++){

      $item_class = apply_filters( 'wen_associate_filter_footer_widget_class', '', $i );
      $div_classes = implode(' ', array( $item_class, $args['wrap_class'] ) );

      echo '<div class="' . $div_classes .  '">';
      $sidebar_name = "footer-$i";
      dynamic_sidebar( $sidebar_name );
      echo '</div><!-- .' . $args['wrap_class'] . ' -->';

    } // end for loop

    echo $args['after'];

    echo $container_close;

    ///////
    $output = ob_get_contents();
    ob_end_clean();
    return $output;

  }

endif;



/**
 * Check status of footer widgets.
 *
 * @since WEN Associate 1.0
 *
 */
if( ! function_exists( 'wen_associate_check_footer_widgets_status' ) ):

  function wen_associate_check_footer_widgets_status( $input ){

    $support = get_theme_support( 'footer-widgets' );

    if ( ! empty( $support ) ){
      // Number of footer widgets
      $footer_widgets_number = absint( $support[0] );
      if ( $footer_widgets_number > 0 ) {
        //check footer widget status
        $status = false;
        for ( $i = 1; $i <= $footer_widgets_number ; $i++ ) {
          if ( is_active_sidebar( 'footer-' . $i ) ) {
            $status = true;
          }
        } //end for
        $input = $status;
      } // end if
    } // end if not empty support

    return $input;
  }

endif;

/**
 * Custom footer widget class.
 *
 * @since WEN Associate 1.0
 *
 */
if( ! function_exists( 'wen_associate_custom_footer_widget_class' ) ):

  function wen_associate_custom_footer_widget_class( $input ){

    $support = get_theme_support( 'footer-widgets' );

    if ( ! empty( $support ) ){
      // Number of footer widgets
      $footer_widgets_number = absint( $support[0] );
      if ( $footer_widgets_number > 0 ) {

        switch ( $footer_widgets_number) {

          case 1:
            $input .= 'col-sm-12';
            break;

          case 2:
            $input .= 'col-sm-6';
            break;

          case 3:
            $input .= 'col-sm-4';
            break;

          case 4:
            $input .= 'col-sm-3';
            break;

          default:
            # code...
            break;
        }

      } // end if
    } // end if not empty support

    return $input;
  }

endif;
