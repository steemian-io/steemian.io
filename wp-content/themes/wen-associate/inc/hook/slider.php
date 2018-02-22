<?php

// Check slider status
add_filter( 'wen_associate_filter_slider_status', 'wen_associate_check_slider_status' );

// Slider details
add_filter( 'wen_associate_filter_slider_details', 'wen_associate_get_slider_details' );

if ( ! function_exists( 'wen_associate_add_featured_slider' ) ) :
  /**
   * Add featured slider
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_add_featured_slider() {

    $flag_apply_slider = apply_filters( 'wen_associate_filter_slider_status', true );
    if ( true != $flag_apply_slider ) {
      return false;
    }

    $slider_details = array();
    $slider_details = apply_filters( 'wen_associate_filter_slider_details', $slider_details );

    if ( empty( $slider_details ) ) {
      return;
    }

    // Render slider now
    wen_associate_render_featured_slider( $slider_details );

  }
endif;
add_action( 'wen_associate_action_before_content', 'wen_associate_add_featured_slider', 5 );


/**
 * Render featured slider.
 *
 * @since WEN Associate 1.0
 *
 */
if( ! function_exists( 'wen_associate_render_featured_slider' ) ):

  function wen_associate_render_featured_slider( $slider_details = array() ){

    if ( empty( $slider_details ) ) {
      return;
    }

    $featured_slider_transition_effect   = wen_associate_get_option( 'featured_slider_transition_effect' );
    $featured_slider_enable_caption      = wen_associate_get_option( 'featured_slider_enable_caption' );
    $featured_slider_enable_arrow        = wen_associate_get_option( 'featured_slider_enable_arrow' );
    $featured_slider_enable_pager        = wen_associate_get_option( 'featured_slider_enable_pager' );
    $featured_slider_enable_autoplay     = wen_associate_get_option( 'featured_slider_enable_autoplay' );
    $featured_slider_transition_duration = wen_associate_get_option( 'featured_slider_transition_duration' );
    $featured_slider_transition_delay    = wen_associate_get_option( 'featured_slider_transition_delay' );

    // Cycle data
    $slide_data = array(
      'fx'             => esc_attr( $featured_slider_transition_effect ),
      'speed'          => esc_attr( $featured_slider_transition_duration ) * 1000,
      'pause-on-hover' => 'true',
      'loader'         => 'true',
      'log'            => 'false',
      'swipe'          => 'true',
      'auto-height'    => 'container',
    );
    if ( $featured_slider_enable_caption ) {
      $slide_data['caption-template'] = '<h3><a href="{{url}}">{{title}}</a></h3><p>{{excerpt}}</p>';
    }

    if ( $featured_slider_enable_pager ) {
      $slide_data['pager-template'] = '<span class="pager-box"></span>';
    }
    if ( $featured_slider_enable_autoplay ) {
      $slide_data['timeout'] = esc_attr( $featured_slider_transition_delay ) * 1000;
    }
    else{
      $slide_data['timeout'] = 0;
    }
    // Slides
    $slide_data['slides'] = 'li';

    $slide_attributes_text = '';
    foreach ($slide_data as $key => $item) {

      $slide_attributes_text .= ' ';
      $slide_attributes_text .= ' data-cycle-'.esc_attr( $key );
      $slide_attributes_text .= '="'.esc_attr( $item ).'"';

    }

    ?>
    <div id="featured-slider">
      <div class="container">

        <ul class="cycle-slideshow" id="main-slider" <?php echo $slide_attributes_text; ?>>

          <?php if ( $featured_slider_enable_arrow ): ?>
            <!-- prev/next links -->
            <div class="cycle-prev"></div>
            <div class="cycle-next"></div>
          <?php endif ?>

          <?php if ( $featured_slider_enable_caption ): ?>
            <!-- empty element for caption -->
            <div class="cycle-caption"></div>
          <?php endif ?>

            <?php $cnt = 1; ?>
            <?php foreach ($slider_details as $key => $slide): ?>

                <?php $class_text = ( 1 == $cnt ) ? ' class="first" ' : ''; ?>

                <li data-cycle-title="<?php echo esc_attr( $slide['title'] ); ?>"  data-cycle-url="<?php echo esc_url( $slide['url'] ); ?>"  data-cycle-excerpt="<?php echo esc_attr( $slide['excerpt'] ); ?>" <?php echo $class_text; ?>>
                  <a href="<?php echo esc_url( $slide['url'] ); ?>">
                    <img src="<?php echo esc_url( $slide['images'][0] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>"  />
                  </a>
                </li>

                <?php $cnt++; ?>

            <?php endforeach ?>


            <?php if ( $featured_slider_enable_pager ): ?>
              <!-- pager -->
              <div class="cycle-pager"></div>
            <?php endif ?>


        </ul> <!-- #main-slider -->

      </div><!-- .container -->
    </div><!-- #featured-slider -->

    <?php


  }

endif;


/**
 * Check status of slider.
 *
 * @since WEN Associate 1.0
 *
 */
if( ! function_exists( 'wen_associate_check_slider_status' ) ):

  function wen_associate_check_slider_status( $input ){

    global $post, $wp_query;

    // Slider status
    $featured_slider_status = wen_associate_get_option( 'featured_slider_status' );

    // Initial
    $status = false;

    switch ( $featured_slider_status ) {

      case 'entire-site':
        $status = true;
        break;

      case 'home-page-only':
        if ( is_front_page() ) {
          $status = true;
        }
        break;

      case 'home-blog-page':
        if ( is_front_page() || is_home() ) {
          $status = true;
        }
        break;

      case 'disabled':
      default:
        $status = false;
        break;

    }
    return $status;

  }

endif;


/**
 * Return slider details.
 *
 * @since WEN Associate 1.0
 *
 */
if( ! function_exists( 'wen_associate_get_slider_details' ) ):

  function wen_associate_get_slider_details( $input ){

    $featured_slider_type = wen_associate_get_option( 'featured_slider_type' );

    switch ( $featured_slider_type ) {

      case 'featured-category':

        $featured_slider_number   = wen_associate_get_option( 'featured_slider_number' );
        $featured_slider_category = wen_associate_get_option( 'featured_slider_category' );
        $qargs = array(
          'posts_per_page' => esc_attr( $featured_slider_number ),
          'no_found_rows'  => true,
          'meta_query'     => array(
              array( 'key' => '_thumbnail_id' ), //Show only posts with featured images
            )
          );

        if ( absint( $featured_slider_category ) > 0  ) {
          $qargs['cat'] = esc_attr( $featured_slider_category );
        }

        // Fetch posts
        $all_posts = get_posts( $qargs );

        $slides = array();

        if ( ! empty( $all_posts ) ){

          $cnt = 0;
          foreach ( $all_posts as $key => $post ){

            if ( has_post_thumbnail( $post->ID ) ) {
              $image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'wen-associate-slider' );
              $slides[$cnt]['images']     = $image_array;
              $slides[$cnt]['title']       = esc_html( $post->post_title );
              $slides[$cnt]['url']         = esc_url( get_permalink( $post->ID ) );
              $slides[$cnt]['excerpt']     = wen_associate_the_excerpt( 20, $post );

              $cnt++;
            }

          }

        }
        if ( ! empty( $slides ) ) {
          $input = $slides;
        }
        break;

      default:
        break;
    }
    return $input;

  }

endif;
