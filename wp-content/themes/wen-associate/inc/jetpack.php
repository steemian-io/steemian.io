<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package WEN Associate
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function wen_associate_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
    'render'    => 'wen_associate_infinite_scroll_render',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'wen_associate_jetpack_setup' );


/**
 * Custom render function for Infinite Scroll.
 */
function wen_associate_infinite_scroll_render() {
  while ( have_posts() ) {
    the_post();
    get_template_part( 'template-parts/content', get_post_format() );
  }
} // end function wen_associate_infinite_scroll_render
