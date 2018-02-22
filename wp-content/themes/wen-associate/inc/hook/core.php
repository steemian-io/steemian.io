<?php

/**
 * Add custom CSS
 *
 * @since  WEN Associate 1.0
 */

if( ! function_exists( 'wen_associate_add_custom_css' ) ) :

  function wen_associate_add_custom_css(){

    $custom_css = wen_associate_get_option( 'custom_css' );
    $output = '';
    if ( ! empty( $custom_css ) ) {
      $output = "\n" . '<style type="text/css">' . "\n";
      $output .= esc_textarea( $custom_css ) ;
      $output .= "\n" . '</style>' . "\n" ;
    }
    echo $output;

  }

endif;
add_action( 'wp_head', 'wen_associate_add_custom_css' );

if( ! function_exists( 'wen_associate_site_branding' ) ) :

  /**
   * Site branding
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_site_branding() {

    ?>
    <div class="site-branding">

      	<?php if ( function_exists( 'the_custom_logo' ) ) : ?>
      		<?php the_custom_logo(); ?>
      	<?php endif; ?>

      <?php $show_site_title = wen_associate_get_option( 'show_site_title' ); ?>
      <?php if ( 1 == $show_site_title ) : ?>
      <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
	      <?php bloginfo( 'name' ); ?>
      </a></h1>
	  <?php endif; ?>
      <?php $show_tagline = wen_associate_get_option( 'show_tagline' ); ?>
       <?php if ( 1 == $show_tagline ) : ?>
        <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
       <?php endif; ?>
    </div><!-- .site-branding -->

    <?php
      $social_in_header = wen_associate_get_option( 'social_in_header' );
      $search_in_header = wen_associate_get_option( 'search_in_header' );
    ?>
    <?php if ( ( 1 == $social_in_header && wen_associate_is_social_menu_active() ) || 1 == $search_in_header ): ?>

      <aside class="sidebar-header-right">

        <?php if ( 1 == $social_in_header && wen_associate_is_social_menu_active() ): ?>
          <?php the_widget( 'WEN_Associate_Social_Widget' ); ?>
        <?php endif ?>

        <?php if ( 1 == $search_in_header ) : ?>

          <div class="search-btn-wrap"><a href="#" id="btn-search-icon"><i class="fa fa-search"></i></a></div><!-- .search-btn-wrap -->
          <div id="header-search-form">
            <?php get_search_form(); ?>
          </div><!-- #header-search-form -->

        <?php endif ?>
      </aside><!-- .sidebar-header-right -->

    <?php endif ?>

    <?php

  }

endif;
add_action( 'wen_associate_action_header', 'wen_associate_site_branding' );


if( ! function_exists( 'wen_associate_primary_navigation' ) ) :

  /**
   * Primary navigation
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_primary_navigation(){

    ?>
    <div id="site-navigation" role="navigation">
      <div class="container">

        <?php
          wp_nav_menu( array(
			'theme_location'  => 'primary',
			'container'       => 'nav',
			'container_class' => 'main-navigation',
			'fallback_cb'     => 'wen_associate_primary_navigation_fallback',
            )
          );
        ?>

      </div><!-- .container -->
    </div><!-- #site-navigation -->
    <?php

  }

endif;
add_action( 'wen_associate_action_after_header', 'wen_associate_primary_navigation', 50 );

if( ! function_exists( 'wen_associate_mobile_navigation' ) ) :

  /**
   * Mobile navigation.
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_mobile_navigation(){

    ?>
    <a href="#mob-menu" id="mobile-trigger"><i class="fa fa-bars"></i></a>
    <div style="display:none;">
      <div id="mob-menu">
          <?php
            wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => '',
				'fallback_cb'    => 'wen_associate_primary_navigation_fallback',
            ) );
          ?>
      </div><!-- #mob-menu -->
    </div>

    <?php

  }

endif;
add_action( 'wen_associate_action_before', 'wen_associate_mobile_navigation', 20 );

if( ! function_exists( 'wen_associate_primary_navigation_fallback' ) ) :
	/**
	 * Fallback for primary navigation.
	 *
	 * @since 1.0.0
	 */
	function wen_associate_primary_navigation_fallback() {
		echo '<ul>';
		echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home', 'wen-associate' ). '</a></li>';
		wp_list_pages( array(
			'title_li' => '',
			'depth'    => 1,
			'number'   => 7,
		) );
		echo '</ul>';

	}
endif;

if( ! function_exists( 'wen_associate_implement_excerpt_length' ) ) :

  /**
   * Implement excerpt length.
   *
   * @since 1.0.0
   */
  function wen_associate_implement_excerpt_length( $length ){

    $excerpt_length = wen_associate_get_option( 'excerpt_length' );
    if ( empty( $excerpt_length) ) {
      $excerpt_length = $length;
    }
    return apply_filters( 'wen_associate_filter_excerpt_length', esc_attr( $excerpt_length ) );

  }

endif;
add_filter( 'excerpt_length', 'wen_associate_implement_excerpt_length', 999 );


if( ! function_exists( 'wen_associate_implement_read_more' ) ) :

  /**
   * Implement read more in excerpt.
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_implement_read_more( $more ){

    $flag_apply_excerpt_read_more = apply_filters( 'wen_associate_filter_excerpt_read_more', true );
    if ( true != $flag_apply_excerpt_read_more ) {
      return $more;
    }

    $output = $more;
    $read_more_text = wen_associate_get_option( 'read_more_text' );
    if ( ! empty( $read_more_text ) ) {
      $output = ' <a href="'. esc_url( get_permalink() ) . '" class="read-more">' . esc_html( $read_more_text ) . '</a>';
      $output = apply_filters( 'wen_associate_filter_read_more_link' , $output );
    }
    return $output;

  }

endif;
add_filter( 'excerpt_more', 'wen_associate_implement_read_more' );


if( ! function_exists( 'wen_associate_content_more_link' ) ) :

  /**
   * Implement read more in content
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_content_more_link( $more_link, $more_link_text ) {

    $flag_apply_excerpt_read_more = apply_filters( 'wen_associate_filter_excerpt_read_more', true );
    if ( true != $flag_apply_excerpt_read_more ) {
      return $more_link;
    }

    $read_more_text = wen_associate_get_option( 'read_more_text' );
    if ( ! empty( $read_more_text ) ) {
      $more_link =  str_replace( $more_link_text, esc_html( $read_more_text ), $more_link );
    }
    return $more_link;

  }

endif;

add_filter( 'the_content_more_link', 'wen_associate_content_more_link', 10, 2 );


if( ! function_exists( 'wen_associate_exclude_category_in_blog_page' ) ) :

  /**
   * Exclude category in blog page
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_exclude_category_in_blog_page( $query ) {

    if( $query->is_home && $query->is_main_query()   ) {
      $exclude_categories = wen_associate_get_option( 'exclude_categories' );
      if ( ! empty( $exclude_categories ) ) {
        $cats = explode( ',', $exclude_categories );
        $cats = array_filter( $cats, 'is_numeric' );
        $string_exclude = '';
        if ( ! empty( $cats ) ) {
          $string_exclude = '-' . implode( ',-', $cats);
          $query->set( 'cat', $string_exclude );
        }
      }
    }
    return $query;

  }

endif;

add_filter( 'pre_get_posts', 'wen_associate_exclude_category_in_blog_page' );

if( ! function_exists( 'wen_associate_add_image_in_single_display' ) ) :

  /**
   * Add image in single post
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_add_image_in_single_display(){

    global $post;

    if ( has_post_thumbnail() ){

      $values = get_post_meta( $post->ID, 'theme_settings', true );
      $theme_settings_single_image = isset( $values['single_image'] ) ? esc_attr( $values['single_image'] ) : '';
      $theme_settings_single_image_alignment = isset( $values['single_image_alignment'] ) ? esc_attr( $values['single_image_alignment'] ) : '';

      if ( ! $theme_settings_single_image ) {
        $theme_settings_single_image = wen_associate_get_option( 'single_image' );
      }
      if ( ! $theme_settings_single_image_alignment ) {
        $theme_settings_single_image_alignment = wen_associate_get_option( 'single_image_alignment' );
      }

      if ( 'disable' != $theme_settings_single_image ) {
        $args = array(
          'class' => 'align' . $theme_settings_single_image_alignment,
        );
        the_post_thumbnail( $theme_settings_single_image, $args );
      }

    }

  }

endif;
add_action( 'wen_associate_single_image', 'wen_associate_add_image_in_single_display' );


if ( ! function_exists( 'wen_associate_custom_posts_navigation' ) ) :

  /**
   * Posts navigation
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_custom_posts_navigation() {

    $pagination_type = wen_associate_get_option( 'pagination_type' );

    switch ( $pagination_type ) {

      case 'default':
        the_posts_navigation();
        break;

      case 'numeric':
        if ( function_exists( 'wp_pagenavi' ) ){
          wp_pagenavi();
        }
        else{
          the_posts_navigation();
        }
        break;

      default:
        break;
    }

  }
endif;
add_action( 'wen_associate_action_posts_navigation', 'wen_associate_custom_posts_navigation' );


if( ! function_exists( 'wen_associate_footer_copyright' ) ) :

  /**
   * Footer copyright
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_footer_copyright(){

    // Check if footer is disabled.
    $footer_status = apply_filters( 'wen_associate_filter_footer_status', true );
    if ( true !== $footer_status) {
      return;
    }

    // Copyright.
    $copyright_text = wen_associate_get_option( 'copyright_text' );
    $copyright_text = apply_filters( 'wen_associate_filter_copyright_text', $copyright_text );

    // Footer navigation.
    $footer_menu_content = wp_nav_menu( array(
      'theme_location' => 'footer' ,
      'container'      => 'div' ,
      'container_id'   => 'footer-navigation' ,
      'depth'          => 1 ,
      'fallback_cb'    => false ,
      'echo'           => false ,
    ) );

    ?>
    <div class="row">

      <?php if ( ! empty( $footer_menu_content ) || ! empty( $copyright_text ) ): ?>
        <div class="col-sm-6">
          <?php if ( ! empty( $footer_menu_content ) ): ?>
              <?php echo $footer_menu_content; ?>
          <?php endif ?>
          <?php if ( ! empty( $copyright_text ) ): ?>
            <div class="copyright">
              <?php echo esc_html( $copyright_text ); ?>
            </div><!-- .copyright -->
          <?php endif ?>
        </div><!-- .col-sm-6 -->
      <?php endif ?>

      <div class="col-sm-6 pull-right">

          <div class="site-info">
            <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'wen-associate' ) ); ?>"><?php printf( __( 'Powered by %s', 'wen-associate' ), 'WordPress' ); ?></a>
            <span class="sep"> | </span>
            <?php printf( __( '%1$s by %2$s', 'wen-associate' ), 'WEN Associate', '<a href="' . esc_url( 'https://wenthemes.com/' ) . '" rel="designer" target="_blank">WEN Themes</a>' ); ?>
          </div><!-- .site-info -->

      </div><!-- .col-sm-6 -->
    </div><!-- .row -->

    <?php

  }

endif;

add_action( 'wen_associate_action_footer', 'wen_associate_footer_copyright', 10 );

if( ! function_exists( 'wen_associate_footer_goto_top' ) ) :

  /**
   * Go to top.
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_footer_goto_top(){

    $go_to_top = wen_associate_get_option( 'go_to_top' );
    if ( 1 != $go_to_top ) {
      return;
    }
    echo '<a href="#" class="scrollup" id="btn-scrollup"><i class="fa fa-chevron-circle-up"></i></a>';

  }

endif;

add_action( 'wen_associate_action_after', 'wen_associate_footer_goto_top', 20 );

if( ! function_exists( 'wen_associate_add_sidebar' ) ) :

  /**
   * Add sidebar.
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_add_sidebar(){

    global $post;

	$global_layout = wen_associate_get_option( 'global_layout' );

    // Check if single
    if ( $post && is_singular() ) {
      $post_options = get_post_meta( $post->ID, 'theme_settings', true );
      if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
        $global_layout = $post_options['post_layout'];
      }
    }

    // Include sidebar.
    if ( 'no-sidebar' != $global_layout ) {
      $sidebar = apply_filters( 'wen_associate_filter_select_primary_sidebar', '' );
      get_sidebar( $sidebar );
    }
    if ( 'three-columns' == $global_layout ) {
      $sidebar = apply_filters( 'wen_associate_filter_select_secondary_sidebar', 'secondary' );
      get_sidebar( $sidebar );
    }

  }

endif;

add_action( 'wen_associate_action_sidebar', 'wen_associate_add_sidebar' );

add_action( 'wen_associate_action_before_content', 'wen_associate_add_breadcrumb' , 7 );

if( ! function_exists( 'wen_associate_add_breadcrumb' ) ) :

  /**
   * Add breadcrumb
   *
   * @since  WEN Associate 1.0
   */
  function wen_associate_add_breadcrumb() {

    // Bail if Breadcrumb disabled.
    $breadcrumb_type = wen_associate_get_option( 'breadcrumb_type' );
    if ( 'disabled' == $breadcrumb_type ) {
      return;
    }

    // Bail if Home Page.
    if ( is_front_page() || is_home() ) {
      return;
    }

    echo '<div id="breadcrumb"><div class="container">';
    switch ( $breadcrumb_type ) {
      case 'simple':
        $breadcrumb_separator = wen_associate_get_option( 'breadcrumb_separator' );
        $args = array(
			'separator' => $breadcrumb_separator,
        );
        wen_associate_simple_breadcrumb( $args );
        break;

      case 'advanced':
        if ( function_exists( 'bcn_display' ) ) {
          bcn_display();
        }
        break;

      default:
        break;
    }

    echo '</div><!-- .container --></div><!-- #breadcrumb -->';
    return;

  }

endif;

if ( ! function_exists( 'wen_associate_import_custom_css' ) ) :

	/**
	 * Import Custom CSS.
	 *
	 * @since 1.9.0
	 */
	function wen_associate_import_custom_css() {

		// Bail if not WP 4.7.
		if ( ! function_exists( 'wp_get_custom_css_post' ) ) {
			return;
		}

		$custom_css = wen_associate_get_option( 'custom_css' );

		// Bail if there is no Custom CSS.
		if ( empty( $custom_css ) ) {
			return;
		}

		$core_css = wp_get_custom_css();
		$return = wp_update_custom_css_post( $core_css . $custom_css );

		if ( ! is_wp_error( $return ) ) {

			// Remove from theme.
			$all_options = wen_associate_get_options();
			$all_options['custom_css'] = '';
			set_theme_mod( 'theme_options', $all_options );
		}

	}
endif;

add_action( 'after_setup_theme', 'wen_associate_import_custom_css', 99 );
