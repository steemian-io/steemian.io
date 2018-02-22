<?php
/**
 * Include base
 */
require trailingslashit( get_template_directory() ) . 'inc/base.php';

/**
 * Include Helper functions
 */
require trailingslashit( get_template_directory() ) . 'inc/helper/customize.php';
require trailingslashit( get_template_directory() ) . 'inc/helper/common.php';
require trailingslashit( get_template_directory() ) . 'inc/helper/customize-callback.php';

/**
 * Include Metabox
 */
require trailingslashit( get_template_directory() ) . 'inc/metabox.php';

/**
 * Include Widgets
 */
require trailingslashit( get_template_directory() ) . 'inc/widgets.php';

/**
 * Include Customizer options
 */
require trailingslashit( get_template_directory() ) . 'inc/customizer/core.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/setting.php';
require trailingslashit( get_template_directory() ) . 'inc/customizer/slider.php';

/**
 * Include Hooks
 */
require trailingslashit( get_template_directory() ) . 'inc/hook/core.php';
require trailingslashit( get_template_directory() ) . 'inc/hook/structure.php';
require trailingslashit( get_template_directory() ) . 'inc/hook/slider.php';
require trailingslashit( get_template_directory() ) . 'inc/hook/custom.php';


function wen_associate_options_setup() {

  global $wen_associate_default_theme_options;
  global $wen_associate_customizer_object;

  $custom_settings = array();
  $custom_settings = apply_filters( 'wen_associate_theme_options_args', $custom_settings );


  $wen_associate_customizer_object = new WEN_Customizer( $custom_settings, $wen_associate_default_theme_options );

}
add_action( 'after_setup_theme', 'wen_associate_options_setup', 20 );

