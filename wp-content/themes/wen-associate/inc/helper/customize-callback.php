<?php
/**
 * Callback functions for active_callback.
 *
 * @package WEN_Associate
 */

if ( ! function_exists( 'wen_associate_is_featured_slider_active' ) ) :

	/**
	 * Check if featured slider is active.
	 *
	 * @since 1.8.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function wen_associate_is_featured_slider_active( $control ) {

		if ( 'disabled' !== $control->manager->get_setting( 'theme_options[featured_slider_status]' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;

if ( ! function_exists( 'wen_associate_is_featured_category_slider_active' ) ) :

	/**
	 * Check if featured category slider is active.
	 *
	 * @since 1.8.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function wen_associate_is_featured_category_slider_active( $control ) {

		if (
		'featured-category' === $control->manager->get_setting( 'theme_options[featured_slider_type]' )->value()
		&& 'disabled' !== $control->manager->get_setting( 'theme_options[featured_slider_status]' )->value()
		) {
			return true;
		} else {
			return false;
		}

	}

endif;
