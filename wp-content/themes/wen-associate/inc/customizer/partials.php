<?php
/**
 * Customizer partials.
 *
 * @package WEN_Associate
 */

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.8.0
 *
 * @return void
 */
function wen_associate_customize_partial_blogname() {

	bloginfo( 'name' );

}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.8.0
 *
 * @return void
 */
function wen_associate_customize_partial_blogdescription() {

	bloginfo( 'description' );

}
