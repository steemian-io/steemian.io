<?php

add_action( 'widgets_init', 'wen_associate_load_widgets' );

if ( ! function_exists( 'wen_associate_load_widgets' ) ) :

  /**
   * Load widgets
   *
   * @since WEN Associate 1.0
   *
   */
  function wen_associate_load_widgets()
  {

    // Social widget
    register_widget( 'WEN_Associate_Social_Widget' );

    // Featured Page widget
    register_widget( 'WEN_Associate_Featured_Page_Widget' );

    // Latest News widget
    register_widget( 'WEN_Associate_Latest_News_Widget' );

    // Testimonial widget
    register_widget( 'WEN_Associate_Testimonial_Widget' );

    // Service widget
    register_widget( 'WEN_Associate_Service_Widget' );

    // Partners widget
    register_widget( 'WEN_Associate_Partners_Widget' );

    // Advanced Recent Posts widget
    register_widget( 'WEN_Associate_Advanced_Recent_Posts_Widget' );

  }

endif;


if ( ! class_exists( 'WEN_Associate_Social_Widget' ) ) :

  /**
   * Social Widget Class
   *
   * @since WEN Associate 1.0
   *
   */
  class WEN_Associate_Social_Widget extends WP_Widget {

    function __construct() {
      $opts = array(
                  'classname'   => 'wen_associate_widget_social',
                  'description' => __( 'Social Icons Widget. Displays social icons.', 'wen-associate' ),
                  'customize_selective_refresh' => true,
              );

      parent::__construct( 'wen-associate-social', __( 'Associate Social Widget', 'wen-associate' ), $opts );
    }


    function widget( $args, $instance ) {
        extract( $args );

        $title        = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $custom_class = apply_filters( 'widget_custom_class', empty( $instance['custom_class'] ) ? '' : $instance['custom_class'], $instance, $this->id_base );


        if ( $custom_class ) {
          $before_widget = str_replace('class="', 'class="'. $custom_class . ' ', $before_widget);
        }

        echo $before_widget;

        // Title.
        if ( $title ) echo $before_title . $title . $after_title;

        if ( has_nav_menu( 'social' ) ) {
        	wp_nav_menu( array(
        		'theme_location' => 'social',
        		'container'      => false,
        		'depth'          => 1,
        		'link_before'    => '<span class="screen-reader-text">',
        		'link_after'     => '</span>',
        	) );
        }

        echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

		$instance['title']        = sanitize_text_field( $new_instance['title'] ) ;
		$instance['custom_class'] = esc_attr( $new_instance['custom_class'] );

        return $instance;
    }

      function form( $instance ) {

        //Defaults
        $instance = wp_parse_args( (array) $instance, array(
          'title'        => '',
          'custom_class' => '',
        ) );
        $title        = esc_attr( $instance['title'] );
        $custom_class = esc_attr( $instance['custom_class'] );

        ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'title'); ?>"><?php _e('Title:', 'wen-associate'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'custom_class' ); ?>"><?php _e( 'Custom Class:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'custom_class'); ?>" name="<?php echo $this->get_field_name( 'custom_class' ); ?>" type="text" value="<?php echo esc_attr( $custom_class ); ?>" />
        </p>

        <?php if ( false === has_nav_menu( 'social' ) ) : ?>
        	<p><?php esc_html_e( 'Social menu is not set. Please create menu and assign it to Social Menu.', 'wen-associate' ); ?></p>
        <?php endif; ?>

        <?php
      }

  }

endif;

if ( ! class_exists( 'WEN_Associate_Featured_Page_Widget' ) ) :

  /**
   * Featured Page Widget Class
   *
   * @since WEN Associate 1.0
   *
   */
  class WEN_Associate_Featured_Page_Widget extends WP_Widget {

    function __construct() {
      $opts = array(
                  'classname'   => 'wen_associate_widget_featured_page',
                  'description' => __( 'Featured Page Widget. Displays content of selected page.', 'wen-associate' ),
                  'customize_selective_refresh' => true,
              );

      parent::__construct( 'wen-associate-featured-page', __( 'Associate Featured Page Widget', 'wen-associate' ), $opts);
    }


    function widget( $args, $instance ) {
        extract( $args );

        $title          = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base );
        $use_page_title = ! empty( $instance['use_page_title'] ) ? $instance['use_page_title'] : false ;
        $featured_page  = ! empty( $instance['featured_page'] ) ? $instance['featured_page'] : 0;
        $content_type   = ! empty( $instance['content_type'] ) ? $instance['content_type'] : 'full';
        $featured_image = ! empty( $instance['featured_image'] ) ? $instance['featured_image'] : 'disable';
        $custom_class   = apply_filters( 'widget_custom_class', empty( $instance['custom_class'] ) ? '' : $instance['custom_class'], $instance, $this->id_base );

        if ( $custom_class ) {
          $before_widget = str_replace( 'class="', 'class="' . esc_attr( $custom_class ) . ' ', $before_widget );
        }

        echo $before_widget;

        if ( absint( $featured_page ) > 0 ) {

        	$qargs = array(
				'p'             => absint( $featured_page ),
				'post_type'     => 'page',
				'no_found_rows' => true,
    		);

        	$the_query = new WP_Query( $qargs );
        	if ( $the_query->have_posts() ) {

        		while ( $the_query->have_posts() ) {
        			$the_query->the_post();

        			if ( false != $use_page_title ) {
        				the_title( $before_title, $after_title );
        			} else {
        				if ( $title ) {
        					echo $before_title . $title . $after_title;
        				}
        			}

        			if ( 'disable' != $featured_image && has_post_thumbnail() ) {
        				the_post_thumbnail( $featured_image, array( 'class' => 'aligncenter' ) );
        			}

        			echo '<div class="featured-page-widget entry-content">';

        			if ( 'short' == $content_type ) {
        				the_excerpt();
        			} else {
        				the_content();
        			}

        			echo '</div><!-- .featured-page-widget -->';
        		}
        	}

        	wp_reset_postdata();
        }

        echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']          = sanitize_text_field( $new_instance['title'] );
        $instance['use_page_title'] = isset( $new_instance['use_page_title'] );
        $instance['featured_page']  = absint( $new_instance['featured_page'] );
        $instance['content_type']   = esc_attr( $new_instance['content_type'] );
        $instance['featured_image'] = esc_attr( $new_instance['featured_image'] );
        $instance['custom_class']   = esc_attr( $new_instance['custom_class'] );

        return $instance;
    }

      function form( $instance ) {

        //Defaults
        $instance = wp_parse_args( (array) $instance, array(
          'title'          => '',
          'use_page_title' => 1,
          'featured_page'  => '',
          'content_type'   => 'full',
          'featured_image' => 'disable',
          'custom_class'   => '',
        ) );
        $title          = strip_tags( $instance['title'] );
        $use_page_title = esc_attr( $instance['use_page_title'] );
        $featured_page  = absint( $instance['featured_page'] );
        $content_type   = esc_attr( $instance['content_type'] );
        $featured_image = esc_attr($instance['featured_image']);
        $custom_class   = esc_attr( $instance['custom_class'] );

        ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'title'); ?>"><?php _e('Title:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p><input id="<?php echo $this->get_field_id( 'use_page_title' ); ?>" name="<?php echo $this->get_field_name( 'use_page_title' ); ?>" type="checkbox" <?php checked(isset($instance['use_page_title']) ? $instance['use_page_title'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'use_page_title' ); ?>"><?php _e( 'Use Page Name as Widget Title', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'featured_page'); ?>"><?php _e( 'Select Page:', 'wen-associate' ); ?></label>
          <?php
            wp_dropdown_pages( array(
              'id'       => $this->get_field_id( 'featured_page' ),
              'name'     => $this->get_field_name( 'featured_page' ),
              'selected' => $featured_page,
              )
            );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'content_type' ); ?>"><?php _e( 'Show Page Content:', 'wen-associate' ); ?></label>
          <select id="<?php echo $this->get_field_id( 'content_type' ); ?>" name="<?php echo $this->get_field_name( 'content_type' ); ?>">
            <option value="short" <?php selected( $content_type, 'short' ) ?>><?php _e( 'Short', 'wen-associate' ) ?></option>
            <option value="full" <?php selected( $content_type, 'full' ) ?>><?php _e( 'Full', 'wen-associate' ) ?></option>
          </select>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'featured_image' ); ?>"><?php _e( 'Select Image Size:', 'wen-associate' ); ?></label>
          <?php
            $this->dropdown_image_sizes( array(
              'id'       => $this->get_field_id( 'featured_image' ),
              'name'     => $this->get_field_name( 'featured_image' ),
              'selected' => $featured_image,
              )
            );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'custom_class' ); ?>"><?php _e( 'Custom Class:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'custom_class'); ?>" name="<?php echo $this->get_field_name( 'custom_class' ); ?>" type="text" value="<?php echo esc_attr( $custom_class ); ?>" />
        </p>
        <?php
      }

    function dropdown_image_sizes( $args ){
      $defaults = array(
        'id'       => '',
        'name'     => '',
        'selected' => 0,
        'echo'     => 1,
      );

      $r = wp_parse_args( $args, $defaults );
      $output = '';

      $choices = wen_associate_get_image_sizes_options();

      if ( ! empty( $choices ) ) {

        $output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
        foreach ( $choices as $key => $choice ) {
          $output .= '<option value="' . esc_attr( $key ) . '" ';
          $output .= selected( $r['selected'], $key, false );
          $output .= '>' . esc_html( $choice ) . '</option>\n';
        }
        $output .= "</select>\n";
      }

      if ( $r['echo'] ) {
        echo $output;
      }
      return $output;

    }

  }

endif;


if ( ! class_exists( 'WEN_Associate_Latest_News_Widget' ) ) :

  /**
   * Latest News Widget Class
   *
   * @since WEN Associate 1.0
   *
   */
  class WEN_Associate_Latest_News_Widget extends WP_Widget {

    function __construct() {
      $opts = array(
                  'classname'   => 'wen_associate_widget_latest_news',
                  'description' => __( 'Latest News Widget. Displays latest posts in grid. Most suitable for home page.', 'wen-associate' ),
                  'customize_selective_refresh' => true,
              );

      parent::__construct( 'wen-associate-latest-news', __( 'Associate Latest News Widget', 'wen-associate' ), $opts );
    }


    function widget( $args, $instance ) {
        extract( $args );

        $title          = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base );
        $post_category     = ! empty( $instance['post_category'] ) ? $instance['post_category'] : 0;
        $post_column       = ! empty( $instance['post_column'] ) ? $instance['post_column'] : 4;
        $featured_image    = ! empty( $instance['featured_image'] ) ? $instance['featured_image'] : 'thumbnail';
        $post_number       = ! empty( $instance['post_number'] ) ? $instance['post_number'] : 4;
        $excerpt_length    = ! empty( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 40;
        $more_text         = ! empty( $instance['more_text'] ) ? $instance['more_text'] : __( 'Read more','wen-associate' );
        $disable_date      = ! empty( $instance['disable_date'] ) ? $instance['disable_date'] : false ;
        $disable_comment   = ! empty( $instance['disable_comment'] ) ? $instance['disable_comment'] : false ;
        $disable_excerpt   = ! empty( $instance['disable_excerpt'] ) ? $instance['disable_excerpt'] : false ;
        $disable_more_text = ! empty( $instance['disable_more_text'] ) ? $instance['disable_more_text'] : false ;
        $custom_class   = apply_filters( 'widget_custom_class', empty( $instance['custom_class'] ) ? '' : $instance['custom_class'], $instance, $this->id_base );

        // Column class
        switch ( $post_column ) {
          case 1:
            $column_class = 'col-sm-12';
            break;
          case 2:
            $column_class = 'col-sm-6';
            break;
          case 3:
            $column_class = 'col-sm-4';
            break;
          case 4:
            $column_class = 'col-sm-3';
            break;
          case 5:
            $column_class = 'col-sm-5ths';
            break;
          case 6:
            $column_class = 'col-sm-2';
            break;
          default:
            $column_class = '';
            break;
        }


        // Add Custom class
        if ( $custom_class ) {
          $before_widget = str_replace( 'class="', 'class="'. $custom_class . ' ', $before_widget );
        }

        echo $before_widget;

        // Title
        if ( $title ) echo $before_title . $title . $after_title;

        //
        ?>
        <?php
          $qargs = array(
            'posts_per_page' => $post_number,
            'no_found_rows'  => true,
            );
          if ( absint( $post_category ) > 0 ) {
            $qargs['cat'] = $post_category;
          }

          $all_posts = get_posts( $qargs );
        ?>
        <?php if ( ! empty( $all_posts ) ): ?>


          <?php global $post; ?>

          <div class="latest-news-widget">

            <div class="row">

              <?php foreach ( $all_posts as $key => $post ): ?>
                <?php setup_postdata( $post ); ?>

                <div class="latest-news-item <?php echo esc_attr( $column_class ); ?>">

                <?php if ( 'disable' != $featured_image && has_post_thumbnail() ): ?>
                  <div class="latest-news-thumb">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                      <?php
                        $img_attributes = array( 'class' => 'aligncenter' );
                        the_post_thumbnail( $featured_image, $img_attributes );
                      ?>
                    </a>
                  </div><!-- .latest-news-thumb -->
                <?php endif ?>
                <div class="latest-news-text-wrap">
                  <h3 class="latest-news-title">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                  </h3><!-- .latest-news-title -->

                  <?php if ( false == $disable_date || ( false == $disable_comment && comments_open( get_the_ID() ) ) ): ?>
                    <div class="latest-news-meta">

                      <?php if ( false == $disable_date ): ?>
                        <span class="latest-news-date"><?php the_time( get_option('date_format') ); ?></span><!-- .latest-news-date -->
                      <?php endif ?>

                      <?php if ( false == $disable_comment ): ?>
                        <?php
                        if ( comments_open( get_the_ID() ) ) {
                          echo '<span class="latest-news-comments">';
                          comments_popup_link( '<span class="leave-reply">' . __( 'No Comment', 'wen-associate' ) . '</span>', __( '1 Comment', 'wen-associate' ), __( '% Comments', 'wen-associate' ) );
                          echo '</span>';
                        }
                        ?>
                      <?php endif ?>

                    </div><!-- .latest-news-meta -->
                  <?php endif ?>

                  <?php if ( false == $disable_excerpt ): ?>
                    <div class="latest-news-summary">
	                    <?php
	                    $excerpt = wen_associate_the_excerpt( $excerpt_length, $post );
	                    echo wp_kses_post( $excerpt );
	                    ?>
                    </div><!-- .latest-news-summary -->
                  <?php endif ?>
                  <?php if ( false == $disable_more_text ): ?>
                    <div class="latest-news-read-more"><a href="<?php the_permalink(); ?>" class="read-more" title="<?php the_title_attribute(); ?>"><?php echo esc_html( $more_text ); ?></a></div><!-- .latest-news-read-more -->
                  <?php endif ?>
                </div><!-- .latest-news-text-wrap -->

                </div><!-- .latest-news-item .col-sm-3 -->

              <?php endforeach ?>

            </div><!-- .row -->

          </div><!-- .latest-news-widget -->

          <?php wp_reset_postdata(); // Reset ?>

        <?php endif; ?>
        <?php
        //
        echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']             = strip_tags($new_instance['title']);
        $instance['post_category']     = absint( $new_instance['post_category'] );
        $instance['post_number']       = absint( $new_instance['post_number'] );
        $instance['post_column']       = absint( $new_instance['post_column'] );
        $instance['excerpt_length']    = absint( $new_instance['excerpt_length'] );
        $instance['featured_image']    = esc_attr( $new_instance['featured_image'] );
        $instance['disable_date']      = isset( $new_instance['disable_date'] );
        $instance['disable_comment']   = isset( $new_instance['disable_comment'] );
        $instance['disable_excerpt']   = isset( $new_instance['disable_excerpt'] );
        $instance['disable_more_text'] = isset( $new_instance['disable_more_text'] );
        $instance['more_text']         = esc_attr( $new_instance['more_text'] );
        $instance['custom_class']      = esc_attr( $new_instance['custom_class'] );

        return $instance;
    }

      function form( $instance ) {

        //Defaults
        $instance = wp_parse_args( (array) $instance, array(
          'title'             => '',
          'post_category'     => '',
          'post_column'       => 4,
          'featured_image'    => 'thumbnail',
          'post_number'       => 4,
          'excerpt_length'    => 40,
          'more_text'         => __( 'Read more', 'wen-associate' ),
          'disable_date'      => 0,
          'disable_comment'   => 0,
          'disable_excerpt'   => 0,
          'disable_more_text' => 0,
          'custom_class'      => '',
        ) );
        $title             = strip_tags( $instance['title'] );
        $post_category     = absint( $instance['post_category'] );
        $post_column       = absint( $instance['post_column'] );
        $featured_image    = esc_attr( $instance['featured_image'] );
        $post_number       = absint( $instance['post_number'] );
        $excerpt_length    = absint( $instance['excerpt_length'] );
        $more_text         = strip_tags( $instance['more_text'] );
        $disable_date      = esc_attr( $instance['disable_date'] );
        $disable_comment   = esc_attr( $instance['disable_comment'] );
        $disable_excerpt   = esc_attr( $instance['disable_excerpt'] );
        $disable_more_text = esc_attr( $instance['disable_more_text'] );
        $custom_class      = esc_attr( $instance['custom_class'] );

        ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_category' ); ?>"><?php _e( 'Select Category:', 'wen-associate' ); ?></label>
          <?php
            $cat_args = array(
                'orderby'         => 'name',
                'hide_empty'      => 0,
                'taxonomy'        => 'category',
                'name'            => $this->get_field_name('post_category'),
                'id'              => $this->get_field_id('post_category'),
                'selected'        => $post_category,
                'show_option_all' => __( 'All Categories','wen-associate' ),
              );
            wp_dropdown_categories( $cat_args );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_number' ); ?>"><?php _e('Number of Posts:', 'wen-associate' ); ?></label>
          <input class="widefat1" id="<?php echo $this->get_field_id( 'post_number' ); ?>" name="<?php echo $this->get_field_name( 'post_number' ); ?>" type="number" value="<?php echo esc_attr( $post_number ); ?>" min="1" style="max-width:50px;" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_column' ); ?>"><?php _e('Number of Columns:', 'wen-associate' ); ?></label>
          <?php
            $this->dropdown_post_columns( array(
              'id'       => $this->get_field_id( 'post_column' ),
              'name'     => $this->get_field_name( 'post_column' ),
              'selected' => $post_column,
              )
            );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'featured_image' ); ?>"><?php _e( 'Select Image Size:', 'wen-associate' ); ?></label>
          <?php
            $this->dropdown_image_sizes( array(
              'id'       => $this->get_field_id( 'featured_image' ),
              'name'     => $this->get_field_name( 'featured_image' ),
              'selected' => $featured_image,
              )
            );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e( 'Excerpt Length:', 'wen-associate' ); ?></label>
          <input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="number" value="<?php echo esc_attr( $excerpt_length ); ?>" min="1" style="max-width:50px;" />&nbsp;<small><?php _e('in words', 'wen-associate' ); ?></small>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'more_text' ); ?>"><?php _e( 'Read More Text:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'more_text'); ?>" name="<?php echo $this->get_field_name( 'more_text' ); ?>" type="text" value="<?php echo esc_attr( $more_text ); ?>" />
        </p>
        <p><input id="<?php echo $this->get_field_id( 'disable_date' ); ?>" name="<?php echo $this->get_field_name( 'disable_date' ); ?>" type="checkbox" <?php checked(isset($instance['disable_date']) ? $instance['disable_date'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_date' ); ?>"><?php _e( 'Disable Date in Post', 'wen-associate' ); ?>
          </label>
        </p>
        <p><input id="<?php echo $this->get_field_id( 'disable_comment' ); ?>" name="<?php echo $this->get_field_name( 'disable_comment' ); ?>" type="checkbox" <?php checked(isset($instance['disable_comment']) ? $instance['disable_comment'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_comment' ); ?>"><?php _e( 'Disable Comment in Post', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <input id="<?php echo $this->get_field_id( 'disable_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'disable_excerpt' ); ?>" type="checkbox" <?php checked(isset($instance['disable_excerpt']) ? $instance['disable_excerpt'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_excerpt' ); ?>"><?php _e( 'Disable Post Excerpt', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <input id="<?php echo $this->get_field_id( 'disable_more_text' ); ?>" name="<?php echo $this->get_field_name( 'disable_more_text' ); ?>" type="checkbox" <?php checked(isset($instance['disable_more_text']) ? $instance['disable_more_text'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_more_text' ); ?>"><?php _e( 'Disable Read More Text', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'custom_class' ); ?>"><?php _e( 'Custom Class:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'custom_class'); ?>" name="<?php echo $this->get_field_name( 'custom_class' ); ?>" type="text" value="<?php echo esc_attr( $custom_class ); ?>" />
        </p>
        <?php
      }


    function dropdown_post_columns( $args ){
      $defaults = array(
        'id'       => '',
        'name'     => '',
        'selected' => 0,
        'echo'     => 1,
      );

      $r = wp_parse_args( $args, $defaults );
      $output = '';

      $choices = array(
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
      );

      if ( ! empty( $choices ) ) {

        $output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
        foreach ( $choices as $key => $choice ) {
          $output .= '<option value="' . esc_attr( $key ) . '" ';
          $output .= selected( $r['selected'], $key, false );
          $output .= '>' . esc_html( $choice ) . '</option>\n';
        }
        $output .= "</select>\n";
      }

      if ( $r['echo'] ) {
        echo $output;
      }
      return $output;

    }

    function dropdown_image_sizes( $args ){
      $defaults = array(
        'id'       => '',
        'name'     => '',
        'selected' => 0,
        'echo'     => 1,
      );

      $r = wp_parse_args( $args, $defaults );
      $output = '';

      $choices = wen_associate_get_image_sizes_options();

      if ( ! empty( $choices ) ) {

        $output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
        foreach ( $choices as $key => $choice ) {
          $output .= '<option value="' . esc_attr( $key ) . '" ';
          $output .= selected( $r['selected'], $key, false );
          $output .= '>' . esc_html( $choice ) . '</option>\n';
        }
        $output .= "</select>\n";
      }

      if ( $r['echo'] ) {
        echo $output;
      }
      return $output;

    }

  }

endif;

if ( ! class_exists( 'WEN_Associate_Testimonial_Widget' ) ) :

  /**
   * Testimonial Widget Class
   *
   * @since WEN Associate 1.0
   *
   */
  class WEN_Associate_Testimonial_Widget extends WP_Widget {

    function __construct() {
      $opts = array(
                  'classname'   => 'wen_associate_widget_testimonial',
                  'description' => __( 'Testimonial Slider Widget. Displays posts from a category as a slider.', 'wen-associate' ),
              );

      parent::__construct('wen-associate-testimonial', __( 'Associate Testimonial Widget', 'wen-associate' ), $opts);
    }


    function widget( $args, $instance ) {
        extract( $args );

        $title          = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base );
        $post_category       = ! empty( $instance['post_category'] ) ? $instance['post_category'] : 0;
        $featured_image      = ! empty( $instance['featured_image'] ) ? $instance['featured_image'] : 'thumbnail';
        $post_number         = ! empty( $instance['post_number'] ) ? $instance['post_number'] : 4;
        $excerpt_length      = ! empty( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 40;
        $transition_delay    = ! empty( $instance['transition_delay'] ) ? $instance['transition_delay'] : 3;
        $transition_duration = ! empty( $instance['transition_duration'] ) ? $instance['transition_duration'] : 1;
        $disable_pager       = ! empty( $instance['disable_pager'] ) ? $instance['disable_pager'] : false ;
        $custom_class        = apply_filters( 'widget_custom_class', empty( $instance['custom_class'] ) ? '' : $instance['custom_class'], $instance, $this->id_base );

        // Add Custom class
        if ( $custom_class ) {
          $before_widget = str_replace( 'class="', 'class="'. $custom_class . ' ', $before_widget );
        }

        echo $before_widget;

        // Title
        if ( $title ) echo $before_title . $title . $after_title;

        //
        ?>
        <?php
          $qargs = array(
            'posts_per_page' => $post_number,
            'no_found_rows'  => true,
            );
          if ( absint( $post_category ) > 0 ) {
            $qargs['cat'] = $post_category;
          }

          $all_posts = get_posts( $qargs );
        ?>
        <?php if ( ! empty( $all_posts ) ): ?>

          <?php global $post; ?>

          <?php
            // Cycle data
            $slide_data = array(
              'fx'             => 'fade',
              'speed'          => $transition_duration * 1000,
              'pause-on-hover' => 'true',
              'log'            => 'false',
              'swipe'          => 'true',
              'auto-height'    => 'container',
              'slides'         => '> article',
            );
            $slide_data['timeout'] = $transition_delay * 1000;
            $slide_attributes_text = '';
            foreach ($slide_data as $key => $item) {
              $slide_attributes_text .= ' ';
              $slide_attributes_text .= ' data-cycle-'.esc_attr( $key );
              $slide_attributes_text .= '="'.esc_attr( $item ).'"';
            }

          ?>

          <div class="testimonial-widget">

            <div class="cycle-slideshow" <?php echo $slide_attributes_text; ?> >

              <?php foreach ( $all_posts as $key => $post ): ?>
                <?php setup_postdata( $post ); ?>

                <article class="testimonial-item">

                  <?php if ( 'disable' != $featured_image && has_post_thumbnail()  ): ?>
                    <div class="testimonial-thumb">
                        <?php
                          the_post_thumbnail( $featured_image );
                        ?>
                    </div><!-- .testimonial-thumb -->
                  <?php endif ?>
                  <div class="testimonial-text-wrap">
                    <div class="testimonial-summary">
	                    <?php
	                    $excerpt = wen_associate_the_excerpt( $excerpt_length, $post );
	                    echo wp_kses_post( $excerpt );
	                    ?>
                    </div><!-- .testimonial-summary -->
                    <h3 class="testimonial-title">
                      <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                      </a>
                    </h3><!-- .testimonial-title -->
                  </div><!-- .testimonial-text-wrap -->

                </article><!-- .testimonial-item -->

              <?php endforeach ?>

              <?php if ( false == $disable_pager ): ?>
                <div class="cycle-pager"></div>
              <?php endif ?>

            </div><!-- .cycle-slideshow -->


          </div><!-- .testimonial-widget -->

          <?php wp_reset_postdata(); // Reset ?>

        <?php endif; ?>
        <?php
        //
        echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']               = strip_tags($new_instance['title']);
        $instance['post_category']       = absint( $new_instance['post_category'] );
        $instance['featured_image']      = esc_attr( $new_instance['featured_image'] );
        $instance['post_number']         = absint( $new_instance['post_number'] );
        $instance['excerpt_length']      = absint( $new_instance['excerpt_length'] );
        $instance['transition_delay']    = absint( $new_instance['transition_delay'] );
        $instance['transition_duration'] = absint( $new_instance['transition_duration'] );
        $instance['disable_pager']       = isset( $new_instance['disable_pager'] );
        $instance['custom_class']        = esc_attr( $new_instance['custom_class'] );

        return $instance;
    }

      function form( $instance ) {

        //Defaults
        $instance = wp_parse_args( (array) $instance, array(
          'title'               => '',
          'post_category'       => '',
          'featured_image'      => 'thumbnail',
          'post_number'         => 4,
          'excerpt_length'      => 40,
          'transition_delay'    => 3,
          'transition_duration' => 1,
          'disable_pager'       => 0,
          'custom_class'        => '',
        ) );
        $title               = strip_tags( $instance['title'] );
        $post_category       = absint( $instance['post_category'] );
        $featured_image      = esc_attr( $instance['featured_image'] );
        $post_number         = absint( $instance['post_number'] );
        $excerpt_length      = absint( $instance['excerpt_length'] );
        $transition_delay    = absint( $instance['transition_delay'] );
        $transition_duration = absint( $instance['transition_duration'] );
        $disable_pager       = esc_attr( $instance['disable_pager'] );
        $custom_class        = esc_attr( $instance['custom_class'] );

        ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_category' ); ?>"><?php _e( 'Select Category:', 'wen-associate' ); ?></label>
          <?php
            $cat_args = array(
                'orderby'         => 'name',
                'hide_empty'      => 0,
                'taxonomy'        => 'category',
                'name'            => $this->get_field_name('post_category'),
                'id'              => $this->get_field_id('post_category'),
                'selected'        => $post_category,
                'show_option_all' => __( 'All Categories','wen-associate' ),
              );
            wp_dropdown_categories( $cat_args );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_number' ); ?>"><?php _e('Number of Posts:', 'wen-associate' ); ?></label>
          <input id="<?php echo $this->get_field_id( 'post_number' ); ?>" name="<?php echo $this->get_field_name( 'post_number' ); ?>" type="number" value="<?php echo esc_attr( $post_number ); ?>" min="1" style="max-width:50px;" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'featured_image' ); ?>"><?php _e( 'Select Image Size:', 'wen-associate' ); ?></label>
          <?php
            $this->dropdown_image_sizes( array(
              'id'       => $this->get_field_id( 'featured_image' ),
              'name'     => $this->get_field_name( 'featured_image' ),
              'selected' => $featured_image,
              )
            );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e('Excerpt Length:', 'wen-associate' ); ?></label>
          <input class="widefat1" id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="number" value="<?php echo esc_attr( $excerpt_length ); ?>" min="1" style="max-width:50px;" />&nbsp;<small><?php _e('in words', 'wen-associate' ); ?></small>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'transition_delay' ); ?>"><?php _e( 'Transition Delay:', 'wen-associate' ); ?></label>
          <input id="<?php echo $this->get_field_id( 'transition_delay' ); ?>" name="<?php echo $this->get_field_name( 'transition_delay' ); ?>" type="text" value="<?php echo esc_attr( $transition_delay ); ?>" style="max-width:50px;" />&nbsp;<small><?php _e('in seconds', 'wen-associate' ); ?></small>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'transition_duration' ); ?>"><?php _e( 'Transition Duration:', 'wen-associate' ); ?></label>
          <input id="<?php echo $this->get_field_id( 'transition_duration' ); ?>" name="<?php echo $this->get_field_name( 'transition_duration' ); ?>" type="text" value="<?php echo esc_attr( $transition_duration ); ?>" style="max-width:50px;" />&nbsp;<small><?php _e('in seconds', 'wen-associate' ); ?></small>
        </p>
        <p><input id="<?php echo $this->get_field_id( 'disable_pager' ); ?>" name="<?php echo $this->get_field_name( 'disable_pager' ); ?>" type="checkbox" <?php checked(isset($instance['disable_pager']) ? $instance['disable_pager'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_pager' ); ?>"><?php _e( 'Disable Pager', 'wen-associate' ); ?>
          </label>&nbsp;<small>(<?php _e('Check this to hide pager icons', 'wen-associate' ); ?>)</small>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'custom_class' ); ?>"><?php _e( 'Custom Class:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'custom_class'); ?>" name="<?php echo $this->get_field_name( 'custom_class' ); ?>" type="text" value="<?php echo esc_attr( $custom_class ); ?>" />
        </p>
        <?php
      }

    function dropdown_image_sizes( $args ){
      $defaults = array(
        'id'       => '',
        'name'     => '',
        'selected' => 0,
        'echo'     => 1,
      );

      $r = wp_parse_args( $args, $defaults );
      $output = '';

      $all_choices = wen_associate_get_image_sizes_options();
      $allowed_options = array( 'disable', 'thumbnail' );
      $choices = array();
      foreach ( $allowed_options as $c ) {
        if ( array_key_exists( $c, $all_choices ) ) {
          $choices[ $c ] = $all_choices[ $c ];
        }
      }

      if ( ! empty( $choices ) ) {

        $output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
        foreach ( $choices as $key => $choice ) {
          $output .= '<option value="' . esc_attr( $key ) . '" ';
          $output .= selected( $r['selected'], $key, false );
          $output .= '>' . esc_html( $choice ) . '</option>\n';
        }
        $output .= "</select>\n";
      }

      if ( $r['echo'] ) {
        echo $output;
      }
      return $output;

    }

  }

endif;

if ( ! class_exists( 'WEN_Associate_Service_Widget' ) ) :

  /**
   * Service Widget Class
   *
   * @since WEN Associate 1.0
   *
   */
  class WEN_Associate_Service_Widget extends WP_Widget {

    function __construct() {
      $opts = array(
                  'classname'   => 'wen_associate_widget_service',
                  'description' => __( 'Service Widget. Show your services with icon and read more link.', 'wen-associate' ),
                  'customize_selective_refresh' => true,
              );
      parent::__construct( 'wen-associate-service', __( 'Associate Service Widget', 'wen-associate' ), $opts );
    }


    function widget( $args, $instance ) {
        extract( $args );

        $title             = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base );
        $excerpt_length    = ! empty( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 20;
        $more_text         = ! empty( $instance['more_text'] ) ? $instance['more_text'] : __( 'Read more','wen-associate' );
        $disable_excerpt   = ! empty( $instance['disable_excerpt'] ) ? $instance['disable_excerpt'] : false ;
        $disable_more_text = ! empty( $instance['disable_more_text'] ) ? $instance['disable_more_text'] : false ;
        $custom_class      = apply_filters( 'widget_custom_class', empty( $instance['custom_class'] ) ? '' : $instance['custom_class'], $instance, $this->id_base );

        $block_page_1      = ! empty( $instance['block_page_1'] ) ? $instance['block_page_1'] : '';
        $block_icon_1      = ! empty( $instance['block_icon_1'] ) ? $instance['block_icon_1'] : 'fa-cogs';

        $block_page_2      = ! empty( $instance['block_page_2'] ) ? $instance['block_page_2'] : '';
        $block_icon_2      = ! empty( $instance['block_icon_2'] ) ? $instance['block_icon_2'] : 'fa-cogs';

        $block_page_3      = ! empty( $instance['block_page_3'] ) ? $instance['block_page_3'] : '';
        $block_icon_3      = ! empty( $instance['block_icon_3'] ) ? $instance['block_icon_3'] : 'fa-cogs';

        $block_page_4      = ! empty( $instance['block_page_4'] ) ? $instance['block_page_4'] : '';
        $block_icon_4      = ! empty( $instance['block_icon_4'] ) ? $instance['block_icon_4'] : 'fa-cogs';

        if ( $custom_class ) {
          $before_widget = str_replace( 'class="', 'class="'. $custom_class . ' ', $before_widget );
        }

        echo $before_widget;

        // Title
        if ( $title ) echo $before_title . $title . $after_title;

        //
        // Arrange data
        $service_arr = array();
        for ( $i=0; $i < 4 ; $i++ ) {
          $block = ( $i + 1 );
          $service_arr[ $i ] = array(
            'page'       => ${"block_page_" . $block},
            'icon'        => ${"block_icon_" . $block},
          );
        }
        // Clean up data
        $refined_arr = array();
        foreach ($service_arr as $key => $item) {
          if ( !empty( $item['page'] ) && get_post( $item['page'] ) ) {
            $refined_arr[] = $item;
          }
        }

        // Render content
        if ( ! empty( $refined_arr ) ) {
          $extra_args = array(
            'excerpt_length'    => $excerpt_length,
            'more_text'         => $more_text,
            'disable_excerpt'   => $disable_excerpt,
            'disable_more_text' => $disable_more_text,
          );
          $this->render_widget_content( $refined_arr, $extra_args );
        }

        //
        echo $after_widget;

    }

    function render_widget_content( $service_arr, $args = array() ){

      $column = count( $service_arr );
      switch ( $column ) {
        case 1:
          $block_item_class = 'col-sm-12';
          break;

        case 2:
          $block_item_class = 'col-sm-6';
          break;

        case 3:
          $block_item_class = 'col-sm-4';
          break;

        case 4:
          $block_item_class = 'col-sm-3';
          break;

        default:
          $block_item_class = '';
          break;
      }
      ?>
      <div class="service-block-list row">

        <?php foreach ( $service_arr as $key => $service ): ?>
          <?php
            $obj = get_post( $service['page'] );
           ?>

          <div class="service-block-item <?php echo esc_attr( $block_item_class ); ?>">
            <div class="service-block-inner">

              <a href="<?php echo esc_url( get_permalink( $obj->ID ) ); ?>"><i class="<?php echo 'fa ' . esc_attr( $service['icon'] ); ?>"></i></a>
              <div class="service-block-inner-content">
                <h3 class="service-item-title">
                  <a href="<?php echo esc_url( get_permalink( $obj->ID ) ); ?>">
                    <?php echo esc_html( $obj->post_title ); ?>
                  </a>
                </h3>
                <?php if ( true != $args['disable_excerpt'] ): ?>
                  <div class="service-block-item-excerpt">
                    <?php
                    $excerpt = wen_associate_the_excerpt( $args['excerpt_length'], $obj );
                    echo wp_kses_post( wpautop( $excerpt ) );
                    ?>
                  </div><!-- .service-block-item-excerpt -->
                <?php endif ?>

                <?php if ( true != $args['disable_more_text'] ): ?>
                  <a href="<?php echo esc_url( get_permalink( $obj -> ID ) ); ?>" class="read-more" title="<?php echo esc_html( $obj->post_title ); ?>" ><?php echo esc_html( $args['more_text'] ); ?></a>
                <?php endif ?>

              </div><!-- .service-block-inner-content -->

            </div><!-- .service-block-inner -->
          </div><!-- .service-block-item -->

        <?php endforeach ?>

      </div><!-- .service-block-list -->

      <?php


    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']             = strip_tags($new_instance['title']);

        $instance['block_page_1']      = esc_html( $new_instance['block_page_1'] );
        $instance['block_icon_1']      = esc_attr( $new_instance['block_icon_1'] );

        $instance['block_page_2']      = esc_html( $new_instance['block_page_2'] );
        $instance['block_icon_2']      = esc_attr( $new_instance['block_icon_2'] );

        $instance['block_page_3']      = esc_html( $new_instance['block_page_3'] );
        $instance['block_icon_3']      = esc_attr( $new_instance['block_icon_3'] );

        $instance['block_page_4']      = esc_html( $new_instance['block_page_4'] );
        $instance['block_icon_4']      = esc_attr( $new_instance['block_icon_4'] );

        $instance['excerpt_length']    = absint( $new_instance['excerpt_length'] );
        $instance['disable_excerpt']   = isset( $new_instance['disable_excerpt'] );
        $instance['disable_more_text'] = isset( $new_instance['disable_more_text'] );
        $instance['more_text']         = esc_attr( $new_instance['more_text'] );

        $instance['custom_class']      =   esc_attr( $new_instance['custom_class'] );

        return $instance;
    }

      function form( $instance ) {

        //Defaults
        $instance = wp_parse_args( (array) $instance, array(
          'title'             => '',

          'block_page_1'      => '',
          'block_icon_1'      => 'fa-cogs',

          'block_page_2'      => '',
          'block_icon_2'      => 'fa-cogs',

          'block_page_3'      => '',
          'block_icon_3'      => 'fa-cogs',

          'block_page_4'      => '',
          'block_icon_4'      => 'fa-cogs',

          'excerpt_length'    => 20,
          'more_text'         => __( 'Read more', 'wen-associate' ),
          'disable_excerpt'   => 0,
          'disable_more_text' => 0,

          'custom_class'      => '',
        ) );
        $title             = strip_tags( $instance['title'] );

        $block_page_1      = esc_html( $instance['block_page_1'] );
        $block_icon_1      = esc_attr( $instance['block_icon_1'] );

        $block_page_2      = esc_html( $instance['block_page_2'] );
        $block_icon_2      = esc_attr( $instance['block_icon_2'] );

        $block_page_3      = esc_html( $instance['block_page_3'] );
        $block_icon_3      = esc_attr( $instance['block_icon_3'] );

        $block_page_4      = esc_html( $instance['block_page_4'] );
        $block_icon_4      = esc_attr( $instance['block_icon_4'] );

        $excerpt_length    = absint( $instance['excerpt_length'] );
        $more_text         = strip_tags( $instance['more_text'] );
        $disable_excerpt   = esc_attr( $instance['disable_excerpt'] );
        $disable_more_text = esc_attr( $instance['disable_more_text'] );

        $custom_class      = esc_attr( $instance['custom_class'] );

        ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e('Excerpt Length:', 'wen-associate' ); ?></label>
          <input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="number" value="<?php echo esc_attr( $excerpt_length ); ?>" min="1" style="max-width:50px;" />&nbsp;<small><?php _e( 'in words', 'wen-associate' ); ?></small>
        </p>
        <p>
          <input id="<?php echo $this->get_field_id( 'disable_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'disable_excerpt' ); ?>" type="checkbox" <?php checked(isset($instance['disable_excerpt']) ? $instance['disable_excerpt'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_excerpt' ); ?>"><?php _e( 'Disable Excerpt', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'more_text' ); ?>"><?php _e( 'Read More Text:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'more_text'); ?>" name="<?php echo $this->get_field_name( 'more_text' ); ?>" type="text" value="<?php echo esc_attr( $more_text ); ?>" />
        </p>

        <p>
          <input id="<?php echo $this->get_field_id( 'disable_more_text' ); ?>" name="<?php echo $this->get_field_name( 'disable_more_text' ); ?>" type="checkbox" <?php checked(isset($instance['disable_more_text']) ? $instance['disable_more_text'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_more_text' ); ?>"><?php _e( 'Disable Read More Text', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'custom_class' ); ?>"><?php _e( 'Custom Class:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'custom_class'); ?>" name="<?php echo $this->get_field_name( 'custom_class' ); ?>" type="text" value="<?php echo esc_attr( $custom_class ); ?>" />
        </p>
        <hr style="border-top:2px #aaa solid;" />
        <h4 class="block-heading"><?php printf( __( 'Block %d','wen-associate' ), 1 ); ?></h4>
        <p>
          <label for="<?php echo $this->get_field_id( 'block_page_1' ); ?>"><?php _e( 'Page:', 'wen-associate' ); ?></label>
          <?php
            wp_dropdown_pages( array(
              'id'               => $this->get_field_id( 'block_page_1' ),
              'name'             => $this->get_field_name( 'block_page_1' ),
              'selected'         => $block_page_1,
              'show_option_none' => __( '&mdash; Select &mdash;', 'wen-associate' ),
              )
            );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'block_icon_1' ); ?>"><?php _e( 'Icon:', 'wen-associate' ); ?></label>
          <input  id="<?php echo $this->get_field_id( 'block_icon_1' ); ?>" name="<?php echo $this->get_field_name( 'block_icon_1' ); ?>" type="text" value="<?php echo esc_attr( $block_icon_1 ); ?>" style="max-width:100px;" />&nbsp;<em><?php _e( 'eg: fa-cogs', 'wen-associate' ); ?>&nbsp;<a href="<?php echo esc_url( 'http://fontawesome.io/cheatsheet/' ); ?>" target="_blank" title="<?php _e( 'View Reference', 'wen-associate' ); ?>"><?php _e( 'Reference', 'wen-associate' ); ?></a></em>
        </p>

        <h4 class="block-heading"><?php printf( __( 'Block %d','wen-associate' ), 2 ); ?></h4>
        <p>
          <label for="<?php echo $this->get_field_id( 'block_page_2' ); ?>"><?php _e( 'Page:', 'wen-associate' ); ?></label>
          <?php
            wp_dropdown_pages( array(
              'id'               => $this->get_field_id( 'block_page_2' ),
              'name'             => $this->get_field_name( 'block_page_2' ),
              'selected'         => $block_page_2,
              'show_option_none' => __( '&mdash; Select &mdash;', 'wen-associate' ),
              )
            );
          ?>

        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'block_icon_2' ); ?>"><?php _e( 'Icon:', 'wen-associate' ); ?></label>
          <input  id="<?php echo $this->get_field_id( 'block_icon_2' ); ?>" name="<?php echo $this->get_field_name( 'block_icon_2' ); ?>" type="text" value="<?php echo esc_attr( $block_icon_2 ); ?>"  style="max-width:100px;" />&nbsp;<em><?php _e( 'eg: fa-cogs', 'wen-associate' ); ?>&nbsp;<a href="<?php echo esc_url( 'http://fontawesome.io/cheatsheet/' ); ?>" target="_blank" title="<?php _e( 'View Reference', 'wen-associate' ); ?>"><?php _e( 'Reference', 'wen-associate' ); ?></a></em>
        </p>

        <h4 class="block-heading"><?php printf( __( 'Block %d','wen-associate' ), 3 ); ?></h4>
        <p>
          <label for="<?php echo $this->get_field_id( 'block_page_3' ); ?>"><?php _e( 'Page:', 'wen-associate' ); ?></label>
          <?php
            wp_dropdown_pages( array(
              'id'               => $this->get_field_id( 'block_page_3' ),
              'name'             => $this->get_field_name( 'block_page_3' ),
              'selected'         => $block_page_3,
              'show_option_none' => __( '&mdash; Select &mdash;', 'wen-associate' ),
              )
            );
          ?>

        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'block_icon_3' ); ?>"><?php _e( 'Icon:', 'wen-associate' ); ?></label>
          <input  id="<?php echo $this->get_field_id( 'block_icon_3' ); ?>" name="<?php echo $this->get_field_name( 'block_icon_3' ); ?>" type="text" value="<?php echo esc_attr( $block_icon_3 ); ?>"  style="max-width:100px;" />&nbsp;<em><?php _e( 'eg: fa-cogs', 'wen-associate' ); ?>&nbsp;<a href="<?php echo esc_url( 'http://fontawesome.io/cheatsheet/' ); ?>" target="_blank" title="<?php _e( 'View Reference', 'wen-associate' ); ?>"><?php _e( 'Reference', 'wen-associate' ); ?></a></em>
        </p>

        <h4 class="block-heading"><?php printf( __( 'Block %d','wen-associate' ), 4 ); ?></h4>
        <p>
          <label for="<?php echo $this->get_field_id( 'block_page_4' ); ?>"><?php _e( 'Page:', 'wen-associate' ); ?></label>
          <?php
            wp_dropdown_pages( array(
              'id'               => $this->get_field_id( 'block_page_4' ),
              'name'             => $this->get_field_name( 'block_page_4' ),
              'selected'         => $block_page_4,
              'show_option_none' => __( '&mdash; Select &mdash;', 'wen-associate' ),
              )
            );
          ?>

        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'block_icon_4' ); ?>"><?php _e( 'Icon:', 'wen-associate' ); ?></label>
          <input  id="<?php echo $this->get_field_id( 'block_icon_4' ); ?>" name="<?php echo $this->get_field_name( 'block_icon_4' ); ?>" type="text" value="<?php echo esc_attr( $block_icon_4 ); ?>"  style="max-width:100px;" />&nbsp;<em><?php _e( 'eg: fa-cogs', 'wen-associate' ); ?>&nbsp;<a href="<?php echo esc_url( 'http://fontawesome.io/cheatsheet/' ); ?>" target="_blank" title="<?php _e( 'View Reference', 'wen-associate' ); ?>"><?php _e( 'Reference', 'wen-associate' ); ?></a></em>
        </p>

        <?php
      }
  }

endif;

if ( ! class_exists( 'WEN_Associate_Partners_Widget' ) ) :

  /**
   * Partners Widget Class
   *
   * @since WEN Associate 1.0
   *
   */
  class WEN_Associate_Partners_Widget extends WP_Widget {

    function __construct() {
      $opts = array(
                  'classname'   => 'wen_associate_widget_partners',
                  'description' => __( 'Partners Grid Widget. Displays posts with thumbnail in grid. Most suitable for Front Page : Bottom widget area.', 'wen-associate' ),
                  'customize_selective_refresh' => true,
              );

      parent::__construct( 'wen-associate-partners', __( 'Associate Partners Widget', 'wen-associate' ), $opts);
    }


    function widget( $args, $instance ) {
        extract( $args );

        $title          = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base );
        $post_category     = ! empty( $instance['post_category'] ) ? $instance['post_category'] : 0;
        $post_column       = ! empty( $instance['post_column'] ) ? $instance['post_column'] : 4;
        $featured_image    = ! empty( $instance['featured_image'] ) ? $instance['featured_image'] : 'thumbnail';
        $post_number       = ! empty( $instance['post_number'] ) ? $instance['post_number'] : 4;
        $excerpt_length    = ! empty( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 40;
        $post_order_by     = ! empty( $instance['post_order_by'] ) ? $instance['post_order_by'] : 'date';
        $post_order        = ! empty( $instance['post_order'] ) ? $instance['post_order'] : 'desc';
        $more_text         = ! empty( $instance['more_text'] ) ? $instance['more_text'] : __( 'Read more','wen-associate' );
        $disable_excerpt   = ! empty( $instance['disable_excerpt'] ) ? $instance['disable_excerpt'] : false ;
        $disable_more_text = ! empty( $instance['disable_more_text'] ) ? $instance['disable_more_text'] : false ;
        $custom_class   = apply_filters( 'widget_custom_class', empty( $instance['custom_class'] ) ? '' : $instance['custom_class'], $instance, $this->id_base );

        // Validation
        // Order
        if ( in_array( $post_order, array( 'asc', 'desc' ) ) ) {
          $post_order = strtoupper( $post_order );
        }
        else{
          $post_order = 'DESC';
        }
        // Order By
        switch ( $post_order_by ) {
          case 'date':
            $post_order_by = 'date';
            break;
          case 'title':
            $post_order_by = 'title';
            break;
          case 'random':
            $post_order_by = 'rand';
            break;
          case 'menu-order':
            $post_order_by = 'menu_order';
            break;
          default:
            $post_order_by = 'date';
            break;
        }
        // Column class
        switch ( $post_column ) {
          case 1:
            $column_class = 'col-sm-12';
            break;
          case 2:
            $column_class = 'col-sm-6';
            break;
          case 3:
            $column_class = 'col-sm-4';
            break;
          case 4:
            $column_class = 'col-sm-3';
            break;
          case 5:
            $column_class = 'col-sm-5ths';
            break;
          case 6:
            $column_class = 'col-sm-2';
            break;
          default:
            $column_class = '';
            break;
        }


        // Add Custom class
        if ( $custom_class ) {
          $before_widget = str_replace( 'class="', 'class="'. $custom_class . ' ', $before_widget );
        }

        echo $before_widget;

        // Title
        if ( $title ) echo $before_title . $title . $after_title;

        //
        ?>
        <?php
          $qargs = array(
            'posts_per_page' => $post_number,
            'no_found_rows'  => true,
            'orderby'        => $post_order_by,
            'order'          => $post_order,
            );
          if ( absint( $post_category ) > 0 ) {
            $qargs['cat'] = $post_category;
          }

          $all_posts = get_posts( $qargs );
        ?>
        <?php if ( ! empty( $all_posts ) ): ?>


          <?php global $post; ?>

          <div class="partners-widget">

            <div class="row">

              <?php foreach ( $all_posts as $key => $post ): ?>
                <?php setup_postdata( $post ); ?>

                <div class="partners-item <?php echo esc_attr( $column_class ); ?>">

                <?php if ( 'disable' != $featured_image && has_post_thumbnail() ): ?>
                  <div class="partners-thumb">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                      <?php
                        $img_attributes = array( 'class' => 'aligncenter' );
                        the_post_thumbnail( $featured_image, $img_attributes );
                      ?>
                    </a>
                  </div><!-- .partners-thumb -->
                <?php endif ?>
                <div class="partners-text-wrap">
                  <h3 class="partners-title">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                  </h3><!-- .partners-title -->
                  <?php if ( false == $disable_excerpt ): ?>
                    <div class="partners-summary">
	                    <?php
	                    $excerpt = wen_associate_the_excerpt( $excerpt_length, $post );
	                    echo wp_kses_post( $excerpt );
	                    ?>
                    </div><!-- .partners-summary -->
                  <?php endif ?>
                  <?php if ( false == $disable_more_text ): ?>
                    <div class="partners-read-more"><a href="<?php the_permalink(); ?>" class="read-more" title="<?php the_title_attribute(); ?>"><?php echo esc_html( $more_text ); ?></a></div><!-- .partners-read-more -->
                  <?php endif ?>
                </div><!-- .partners-text-wrap -->

                </div><!-- .partners-item .col-sm-3 -->

              <?php endforeach ?>

            </div><!-- .row -->

          </div><!-- .partners-widget -->

          <?php wp_reset_postdata(); // Reset ?>

        <?php endif; ?>
        <?php
        //
        echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']             = strip_tags($new_instance['title']);
        $instance['post_category']     = absint( $new_instance['post_category'] );
        $instance['post_number']       = absint( $new_instance['post_number'] );
        $instance['post_column']       = absint( $new_instance['post_column'] );
        $instance['excerpt_length']    = absint( $new_instance['excerpt_length'] );
        $instance['post_order_by']     = esc_attr( $new_instance['post_order_by'] );
        $instance['post_order']        = esc_attr( $new_instance['post_order'] );
        $instance['featured_image']    = esc_attr( $new_instance['featured_image'] );
        $instance['disable_excerpt']   = isset( $new_instance['disable_excerpt'] );
        $instance['disable_more_text'] = isset( $new_instance['disable_more_text'] );
        $instance['more_text']         = esc_attr( $new_instance['more_text'] );
        $instance['custom_class']      = esc_attr( $new_instance['custom_class'] );

        return $instance;
    }

      function form( $instance ) {

        //Defaults
        $instance = wp_parse_args( (array) $instance, array(
          'title'             => '',
          'post_category'     => '',
          'post_column'       => 4,
          'featured_image'    => 'thumbnail',
          'post_number'       => 4,
          'excerpt_length'    => 40,
          'post_order_by'     => 'date',
          'post_order'        => 'desc',
          'more_text'         => __( 'Read more', 'wen-associate' ),
          'disable_excerpt'   => 0,
          'disable_more_text' => 0,
          'custom_class'      => '',
        ) );
        $title             = strip_tags( $instance['title'] );
        $post_category     = absint( $instance['post_category'] );
        $post_column       = absint( $instance['post_column'] );
        $featured_image    = esc_attr( $instance['featured_image'] );
        $post_number       = absint( $instance['post_number'] );
        $excerpt_length    = absint( $instance['excerpt_length'] );
        $post_order_by     = esc_attr( $instance['post_order_by'] );
        $post_order        = esc_attr( $instance['post_order'] );
        $more_text         = strip_tags( $instance['more_text'] );
        $disable_excerpt   = esc_attr( $instance['disable_excerpt'] );
        $disable_more_text = esc_attr( $instance['disable_more_text'] );
        $custom_class      = esc_attr( $instance['custom_class'] );

        ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_category' ); ?>"><?php _e( 'Select Category:', 'wen-associate' ); ?></label>
          <?php
            $cat_args = array(
                'orderby'         => 'name',
                'hide_empty'      => 0,
                'taxonomy'        => 'category',
                'name'            => $this->get_field_name('post_category'),
                'id'              => $this->get_field_id('post_category'),
                'selected'        => $post_category,
                'show_option_all' => __( 'All Categories','wen-associate' ),
              );
            wp_dropdown_categories( $cat_args );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_number' ); ?>"><?php _e('Number of Posts:', 'wen-associate' ); ?></label>
          <input class="widefat1" id="<?php echo $this->get_field_id( 'post_number' ); ?>" name="<?php echo $this->get_field_name( 'post_number' ); ?>" type="number" value="<?php echo esc_attr( $post_number ); ?>" min="1" style="max-width:50px;" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_column' ); ?>"><?php _e('Number of Columns:', 'wen-associate' ); ?></label>
          <?php
            $this->dropdown_post_columns( array(
              'id'       => $this->get_field_id( 'post_column' ),
              'name'     => $this->get_field_name( 'post_column' ),
              'selected' => $post_column,
              )
            );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'featured_image' ); ?>"><?php _e( 'Select Image Size:', 'wen-associate' ); ?></label>
          <?php
            $this->dropdown_image_sizes( array(
              'id'       => $this->get_field_id( 'featured_image' ),
              'name'     => $this->get_field_name( 'featured_image' ),
              'selected' => $featured_image,
              )
            );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e('Excerpt Length:', 'wen-associate' ); ?></label>
          <input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="number" value="<?php echo esc_attr( $excerpt_length ); ?>" min="1" style="max-width:50px;" />&nbsp;<small><?php _e( 'in words', 'wen-associate' ); ?></small>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_order_by' ); ?>"><?php _e( 'Post Order By:', 'wen-associate' ); ?></label>
          <?php
            $this->dropdown_post_order_by( array(
              'id'       => $this->get_field_id( 'post_order_by' ),
              'name'     => $this->get_field_name( 'post_order_by' ),
              'selected' => $post_order_by,
              )
            );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_order' ); ?>"><?php _e( 'Post Order:', 'wen-associate' ); ?></label>
          <select id="<?php echo $this->get_field_id( 'post_order' ); ?>" name="<?php echo $this->get_field_name( 'post_order' ); ?>">
            <option value="asc" <?php selected( $post_order, 'asc' ) ?>><?php _e( 'Ascending', 'wen-associate' ) ?></option>
            <option value="desc" <?php selected( $post_order, 'desc' ) ?>><?php _e( 'Descending', 'wen-associate' ) ?></option>
          </select>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'more_text' ); ?>"><?php _e( 'Read More Text:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'more_text'); ?>" name="<?php echo $this->get_field_name( 'more_text' ); ?>" type="text" value="<?php echo esc_attr( $more_text ); ?>" />
        </p>
        <p>
          <input id="<?php echo $this->get_field_id( 'disable_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'disable_excerpt' ); ?>" type="checkbox" <?php checked(isset($instance['disable_excerpt']) ? $instance['disable_excerpt'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_excerpt' ); ?>"><?php _e( 'Disable Post Excerpt', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <input id="<?php echo $this->get_field_id( 'disable_more_text' ); ?>" name="<?php echo $this->get_field_name( 'disable_more_text' ); ?>" type="checkbox" <?php checked(isset($instance['disable_more_text']) ? $instance['disable_more_text'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_more_text' ); ?>"><?php _e( 'Disable Read More Text', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'custom_class' ); ?>"><?php _e( 'Custom Class:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'custom_class'); ?>" name="<?php echo $this->get_field_name( 'custom_class' ); ?>" type="text" value="<?php echo esc_attr( $custom_class ); ?>" />
        </p>
        <?php
      }


    function dropdown_post_columns( $args ){
      $defaults = array(
        'id'       => '',
        'name'     => '',
        'selected' => 0,
        'echo'     => 1,
      );

      $r = wp_parse_args( $args, $defaults );
      $output = '';

      $choices = array(
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
      );

      if ( ! empty( $choices ) ) {

        $output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
        foreach ( $choices as $key => $choice ) {
          $output .= '<option value="' . esc_attr( $key ) . '" ';
          $output .= selected( $r['selected'], $key, false );
          $output .= '>' . esc_html( $choice ) . '</option>\n';
        }
        $output .= "</select>\n";
      }

      if ( $r['echo'] ) {
        echo $output;
      }
      return $output;

    }

    function dropdown_post_order_by( $args ){
      $defaults = array(
        'id'       => '',
        'name'     => '',
        'selected' => 0,
        'echo'     => 1,
      );

      $r = wp_parse_args( $args, $defaults );
      $output = '';

      $choices = array(
        'date'          => __( 'Date','wen-associate' ),
        'title'         => __( 'Title','wen-associate' ),
        'menu-order'    => __( 'Menu Order','wen-associate' ),
        'random'        => __( 'Random','wen-associate' ),
      );

      if ( ! empty( $choices ) ) {

        $output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
        foreach ( $choices as $key => $choice ) {
          $output .= '<option value="' . esc_attr( $key ) . '" ';
          $output .= selected( $r['selected'], $key, false );
          $output .= '>' . esc_html( $choice ) . '</option>\n';
        }
        $output .= "</select>\n";
      }

      if ( $r['echo'] ) {
        echo $output;
      }
      return $output;

    }

    function dropdown_image_sizes( $args ){
      $defaults = array(
        'id'       => '',
        'name'     => '',
        'selected' => 0,
        'echo'     => 1,
      );

      $r = wp_parse_args( $args, $defaults );
      $output = '';

      $choices = wen_associate_get_image_sizes_options();

      if ( ! empty( $choices ) ) {

        $output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
        foreach ( $choices as $key => $choice ) {
          $output .= '<option value="' . esc_attr( $key ) . '" ';
          $output .= selected( $r['selected'], $key, false );
          $output .= '>' . esc_html( $choice ) . '</option>\n';
        }
        $output .= "</select>\n";
      }

      if ( $r['echo'] ) {
        echo $output;
      }
      return $output;

    }

  }

endif;


if ( ! class_exists( 'WEN_Associate_Advanced_Recent_Posts_Widget' ) ) :

  /**
   * Advanced Recent Posts Widget Class
   *
   * @since WEN Associate 1.0
   *
   */
  class WEN_Associate_Advanced_Recent_Posts_Widget extends WP_Widget {

    function __construct() {
      $opts = array(
                  'classname'   => 'wen_associate_widget_advanced_recent_posts',
                  'description' => __( 'Advanced Recent Posts Widget. Displays recent posts with thumbnail.', 'wen-associate' ),
                  'customize_selective_refresh' => true,
              );

      parent::__construct( 'wen-associate-advanced-recent-posts', __( 'Associate Recent Posts Widget', 'wen-associate' ), $opts );
    }


    function widget( $args, $instance ) {
        extract( $args );

        $title          = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base );
        $post_category     = ! empty( $instance['post_category'] ) ? $instance['post_category'] : 0;
        $post_column       = ! empty( $instance['post_column'] ) ? $instance['post_column'] : 4;
        $featured_image    = ! empty( $instance['featured_image'] ) ? $instance['featured_image'] : 'thumbnail';
        $image_width       = ! empty( $instance['image_width'] ) ? $instance['image_width'] : 90;
        $post_number       = ! empty( $instance['post_number'] ) ? $instance['post_number'] : 4;
        $excerpt_length    = ! empty( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 40;
        $more_text         = ! empty( $instance['more_text'] ) ? $instance['more_text'] : __( 'Read more','wen-associate' );
        $disable_date      = ! empty( $instance['disable_date'] ) ? $instance['disable_date'] : false ;
        $disable_comment   = ! empty( $instance['disable_comment'] ) ? $instance['disable_comment'] : false ;
        $disable_excerpt   = ! empty( $instance['disable_excerpt'] ) ? $instance['disable_excerpt'] : false ;
        $disable_more_text = ! empty( $instance['disable_more_text'] ) ? $instance['disable_more_text'] : false ;
        $custom_class   = apply_filters( 'widget_custom_class', empty( $instance['custom_class'] ) ? '' : $instance['custom_class'], $instance, $this->id_base );

        // Add Custom class
        if ( $custom_class ) {
          $before_widget = str_replace( 'class="', 'class="'. $custom_class . ' ', $before_widget );
        }

        echo $before_widget;

        // Title
        if ( $title ) echo $before_title . $title . $after_title;

        //
        ?>
        <?php
          $qargs = array(
            'posts_per_page' => $post_number,
            'no_found_rows'  => true,
            );
          if ( absint( $post_category ) > 0 ) {
            $qargs['cat'] = $post_category;
          }

          $all_posts = get_posts( $qargs );
        ?>
        <?php if ( ! empty( $all_posts ) ): ?>


          <?php global $post; ?>

          <div class="advanced-recent-posts-widget">

              <?php foreach ( $all_posts as $key => $post ): ?>
                <?php setup_postdata( $post ); ?>

                <div class="advanced-recent-posts-item">

                <?php if ( 'disable' != $featured_image && has_post_thumbnail() ): ?>
                  <div class="advanced-recent-posts-thumb">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                      <?php
                        $img_attributes = array(
                          'class' => 'alignleft',
                          'style' => 'max-width:' . esc_attr( $image_width ). 'px;',
                        );
                        the_post_thumbnail( $featured_image, $img_attributes );
                      ?>
                    </a>
                  </div><!-- .advanced-recent-posts-thumb -->
                <?php endif ?>
                <div class="advanced-recent-posts-text-wrap">
                  <h3 class="advanced-recent-posts-title">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                  </h3><!-- .advanced-recent-posts-title -->

                  <?php if ( false == $disable_date ): ?>
                    <div class="advanced-recent-posts-meta">

                      <?php if ( false == $disable_date ): ?>
                        <span class="advanced-recent-posts-date"><?php the_time( get_option('date_format') ); ?></span><!-- .advanced-recent-posts-date -->
                      <?php endif ?>

                    </div><!-- .advanced-recent-posts-meta -->
                  <?php endif ?>

                  <?php if ( false == $disable_excerpt ): ?>
                    <div class="advanced-recent-posts-summary">
	                    <?php
	                    $excerpt = wen_associate_the_excerpt( $excerpt_length, $post );
	                    echo wp_kses_post( $excerpt );
	                    ?>
                    </div><!-- .advanced-recent-posts-summary -->
                  <?php endif ?>
                  <?php if ( false == $disable_more_text ): ?>
                    <div class="advanced-recent-posts-read-more"><a href="<?php the_permalink(); ?>" class="read-more" title="<?php the_title_attribute(); ?>"><?php echo esc_html( $more_text ); ?></a></div><!-- .advanced-recent-posts-read-more -->
                  <?php endif ?>
                </div><!-- .advanced-recent-posts-text-wrap -->

                </div><!-- .advanced-recent-posts-item .col-sm-3 -->

              <?php endforeach ?>

          </div><!-- .advanced-recent-posts-widget -->

          <?php wp_reset_postdata(); // Reset ?>

        <?php endif; ?>
        <?php
        //
        echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']             = strip_tags($new_instance['title']);
        $instance['post_category']     = absint( $new_instance['post_category'] );
        $instance['post_number']       = absint( $new_instance['post_number'] );
        $instance['excerpt_length']    = absint( $new_instance['excerpt_length'] );
        $instance['featured_image']    = esc_attr( $new_instance['featured_image'] );
        $instance['image_width']       = absint( $new_instance['image_width'] );
        $instance['disable_date']      = isset( $new_instance['disable_date'] );
        $instance['disable_excerpt']   = isset( $new_instance['disable_excerpt'] );
        $instance['disable_more_text'] = isset( $new_instance['disable_more_text'] );
        $instance['more_text']         = esc_attr( $new_instance['more_text'] );
        $instance['custom_class']      = esc_attr( $new_instance['custom_class'] );

        return $instance;
    }

      function form( $instance ) {

        //Defaults
        $instance = wp_parse_args( (array) $instance, array(
          'title'             => '',
          'post_category'     => '',
          'featured_image'    => 'thumbnail',
          'image_width'       => 90,
          'post_number'       => 4,
          'excerpt_length'    => 40,
          'more_text'         => __( 'Read more', 'wen-associate' ),
          'disable_date'      => 0,
          'disable_excerpt'   => 1,
          'disable_more_text' => 0,
          'custom_class'      => '',
        ) );
        $title             = strip_tags( $instance['title'] );
        $post_category     = absint( $instance['post_category'] );
        $featured_image    = esc_attr( $instance['featured_image'] );
        $image_width       = absint( $instance['image_width'] );
        $post_number       = absint( $instance['post_number'] );
        $excerpt_length    = absint( $instance['excerpt_length'] );
        $more_text         = strip_tags( $instance['more_text'] );
        $disable_date      = esc_attr( $instance['disable_date'] );
        $disable_excerpt   = esc_attr( $instance['disable_excerpt'] );
        $disable_more_text = esc_attr( $instance['disable_more_text'] );
        $custom_class      = esc_attr( $instance['custom_class'] );

        ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_category' ); ?>"><?php _e( 'Select Category:', 'wen-associate' ); ?></label>
          <?php
            $cat_args = array(
                'orderby'         => 'name',
                'hide_empty'      => 0,
                'taxonomy'        => 'category',
                'name'            => $this->get_field_name('post_category'),
                'id'              => $this->get_field_id('post_category'),
                'selected'        => $post_category,
                'show_option_all' => __( 'All Categories','wen-associate' ),
              );
            wp_dropdown_categories( $cat_args );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'featured_image' ); ?>"><?php _e( 'Select Image Size:', 'wen-associate' ); ?></label>
          <?php
            $this->dropdown_image_sizes( array(
              'id'       => $this->get_field_id( 'featured_image' ),
              'name'     => $this->get_field_name( 'featured_image' ),
              'selected' => $featured_image,
              )
            );
          ?>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'image_width' ); ?>"><?php _e( 'Image Width:', 'wen-associate' ); ?></label>
          <input id="<?php echo $this->get_field_id( 'image_width' ); ?>" name="<?php echo $this->get_field_name( 'image_width' ); ?>" type="number" value="<?php echo esc_attr( $image_width ); ?>" min="1" style="max-width:50px;" />&nbsp;<em><?php _e( 'px', 'wen-associate' ); ?></em>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'post_number' ); ?>"><?php _e( 'Number of Posts:', 'wen-associate' ); ?></label>
          <input id="<?php echo $this->get_field_id( 'post_number' ); ?>" name="<?php echo $this->get_field_name( 'post_number' ); ?>" type="number" value="<?php echo esc_attr( $post_number ); ?>" min="1" style="max-width:50px;" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>"><?php _e( 'Excerpt Length:', 'wen-associate' ); ?></label>
          <input id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" type="number" value="<?php echo esc_attr( $excerpt_length ); ?>" min="1" style="max-width:50px;" />&nbsp;<small><?php _e( 'in words', 'wen-associate' ); ?></small>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'more_text' ); ?>"><?php _e( 'Read More Text:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'more_text'); ?>" name="<?php echo $this->get_field_name( 'more_text' ); ?>" type="text" value="<?php echo esc_attr( $more_text ); ?>" />
        </p>
        <p><input id="<?php echo $this->get_field_id( 'disable_date' ); ?>" name="<?php echo $this->get_field_name( 'disable_date' ); ?>" type="checkbox" <?php checked(isset($instance['disable_date']) ? $instance['disable_date'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_date' ); ?>"><?php _e( 'Disable Date in Post', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <input id="<?php echo $this->get_field_id( 'disable_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'disable_excerpt' ); ?>" type="checkbox" <?php checked(isset($instance['disable_excerpt']) ? $instance['disable_excerpt'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_excerpt' ); ?>"><?php _e( 'Disable Post Excerpt', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <input id="<?php echo $this->get_field_id( 'disable_more_text' ); ?>" name="<?php echo $this->get_field_name( 'disable_more_text' ); ?>" type="checkbox" <?php checked(isset($instance['disable_more_text']) ? $instance['disable_more_text'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'disable_more_text' ); ?>"><?php _e( 'Disable Read More Text', 'wen-associate' ); ?>
          </label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'custom_class' ); ?>"><?php _e( 'Custom Class:', 'wen-associate' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'custom_class'); ?>" name="<?php echo $this->get_field_name( 'custom_class' ); ?>" type="text" value="<?php echo esc_attr( $custom_class ); ?>" />
        </p>
        <?php
      }

    function dropdown_image_sizes( $args ){
      $defaults = array(
        'id'       => '',
        'name'     => '',
        'selected' => 0,
        'echo'     => 1,
      );

      $r = wp_parse_args( $args, $defaults );
      $output = '';

      $choices = $this->get_image_sizes_options();

      if ( ! empty( $choices ) ) {

        $output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
        foreach ( $choices as $key => $choice ) {
          $output .= '<option value="' . esc_attr( $key ) . '" ';
          $output .= selected( $r['selected'], $key, false );
          $output .= '>' . esc_html( $choice ) . '</option>\n';
        }
        $output .= "</select>\n";
      }

      if ( $r['echo'] ) {
        echo $output;
      }
      return $output;

    }

    private function get_image_sizes_options(){

      $choices = array();
      $choices['disable']   = __( 'No Image', 'wen-associate' );
      $choices['thumbnail'] = __( 'Thumbnail', 'wen-associate' );
      return $choices;
    }

  }

endif;

