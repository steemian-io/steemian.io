<?php
/**
 * WEN Associate functions and definitions
 *
 * @package WEN Associate
 */

/**
 * Include WEN Customizer
 */
require trailingslashit( get_template_directory() ) . 'wen-customizer/init.php';

/**
 * Init customizer
 */
require trailingslashit( get_template_directory() ) . 'inc/init.php';

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1140; /* pixels */
}

if ( ! function_exists( 'wen_associate_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function wen_associate_setup() {

		/*
		 * Make theme available for translation.
		 */
		load_theme_textdomain( 'wen-associate' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 */
		add_theme_support( 'post-thumbnails' );

		// Register image size for featured slider.
		add_image_size( 'wen-associate-slider', 1300, 440, true );

		// Register nav menu locations.
		register_nav_menus( array(
			'primary'  => __( 'Primary Menu', 'wen-associate' ),
			'footer'   => __( 'Footer Menu', 'wen-associate' ),
			'social'   => __( 'Social Menu', 'wen-associate' ),
			'notfound' => __( '404 Menu', 'wen-associate' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'wen_associate_custom_background_args', array(
			'default-color' => 'eeeeee',
			'default-image' => '',
		) ) );

		/*
		 * Enable support for custom logo.
		 */
		add_theme_support( 'custom-logo' );

		// Enable widget selective refresh Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Editor style.
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		add_editor_style( 'assets/css/editor-style' . $min . '.css' );

		/**
		 * Enable support for footer widgets.
		 */
		add_theme_support( 'footer-widgets', 4 );

		// Include supports.
		require get_template_directory() . '/inc/supports.php';

		global $wen_associate_default_theme_options;
		$wen_associate_default_theme_options = wen_associate_get_default_theme_options();

	}
endif;
add_action( 'after_setup_theme', 'wen_associate_setup' );

/**
 * Register widget area.
 */
function wen_associate_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'wen-associate' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Secondary Sidebar', 'wen-associate' ),
		'id'            => 'sidebar-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Front Page : Main', 'wen-associate' ),
		'id'            => 'sidebar-front-page-main',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Front Page : Lower Left', 'wen-associate' ),
		'id'            => 'sidebar-front-page-lower-left',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Front Page : Lower Right', 'wen-associate' ),
		'id'            => 'sidebar-front-page-lower-right',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Front Page : Bottom', 'wen-associate' ),
		'id'            => 'sidebar-front-page-bottom',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

}
add_action( 'widgets_init', 'wen_associate_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wen_associate_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( 'wen-associate-bootstrap', get_template_directory_uri() . '/assets/css/bootstrap' . $min . '.css', '', '3.3.4' );

	wp_enqueue_style( 'wen-associate-fontawesome', get_template_directory_uri() . '/third-party/font-awesome/css/font-awesome' . $min . '.css', '', '4.7.0' );

	wp_enqueue_style( 'wen-associate-google-fonts-lato', '//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic' );

	wp_enqueue_style( 'wen-associate-style', get_stylesheet_uri(), array(), '1.9.4' );

	wp_enqueue_style( 'wen-associate-responsive', get_template_directory_uri() . '/assets/css/responsive' . $min . '.css', '', '1.7.0' );

	wp_enqueue_style( 'wen-associate-mmenu-style', get_template_directory_uri() .'/third-party/mmenu/css/jquery.mmenu' . $min . '.css', '', '4.7.5' );

	wp_enqueue_script( 'wen-associate-html5', get_template_directory_uri() . '/assets/js/html5shiv' . $min . '.js' );
	wp_script_add_data( 'wen-associate-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'wen-associate-respond', get_template_directory_uri() . '/assets/js/respond' . $min . '.js' );
	wp_script_add_data( 'wen-associate-respond', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'wen-associate-navigation', get_template_directory_uri() . '/assets/js/navigation' . $min . '.js', array(), '20120206', true );

	wp_enqueue_script( 'wen-associate-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix' . $min . '.js', array(), '20130115', true );

	wp_enqueue_script( 'wen-associate-cycle2-script', get_template_directory_uri() . '/third-party/cycle2/js/jquery.cycle2' . $min . '.js', array( 'jquery' ), '2.1.6', true );

	wp_enqueue_script( 'wen-associate-mmenu-script', get_template_directory_uri() . '/third-party/mmenu/js/jquery.mmenu' . $min . '.js', array( 'jquery' ), '4.7.5', true );

	wp_enqueue_script( 'wen-associate-custom-js', get_template_directory_uri() . '/assets/js/custom' . $min . '.js', array( 'jquery' ), '1.0.0', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wen_associate_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
