<?php
/* 
@TODO
Theme Notes:
- No package management
- No coding standards
- No CRLF standard
- Associated repo: https://github.com/pete-for-america-community/pfa-volunteer-theme
- No license
- Unimplemented Localization
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/* Theme constants and globals */
DEFINE( 'PFATHEME', '1.0.0-dev' );
if ( ! isset( $content_width ) ) {
	$content_width = 800; // pixels
}
$uploads_dir = wp_get_upload_dir(); DEFINE( 'IMGDIR', $uploads_dir['url'] );
$pfa_scripts = array();
$pfa_styles = array();

add_action( 'after_setup_theme', 'theme_setup' );
function theme_setup() {

	/* WP Core features */
	$hook_result = apply_filters_deprecated( 'hello_elementor_theme_add_theme_support', [ true ], '2.0', 'hello_elementor_add_theme_support' );
	if ( function_exists( 'add_theme_support' ) ) {
		if ( apply_filters( 'hello_elementor_add_theme_support', $hook_result ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'make-relative-urls-in-content-absolute' );
			add_theme_support( 'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			) );
			add_theme_support( 'custom-logo', array(
				'height' => 100,
				'width' => 350,
				'flex-height' => true,
				'flex-width' => true,
			) );
		}
	}
	

	/* Theme image sizes */
	if ( function_exists( 'add_image_size' ) ) {

		//Fixed ratios from Amy's design
		add_image_size( 'logo', 200, 80);           //All Pages (Header, footer)                                (2.5:1)

		//Fluid areas based on Amy's design
		add_image_size( 'slide', 1920, 650);        //Slides on the homepage, full width                        (3:1)
		add_image_size( 'homepage_cta', 930, 700);  //Home Page CTA space, 50% screen width                     (1.125:1)
        
		//Standard Placeholders
		add_image_size( 'intro_banner', 620, 550);  //Intro banner                                              (1.125:1)
		add_image_size( 'three-by-two', 480, 320);  //                                                          (3:2)
		add_image_size( 'icon', 100, 100);          //                                                          (1:1, min 100)

		//Include custom image sizes in WP admin dropdowns:
		add_filter( 'image_size_names_choose', 'theme_image_size_names' );
		function theme_image_size_names( $sizes ) {
			return array_merge( $sizes, array(
				'intro_banner' => 'Page Intro Banner (1.125:1)',
				'homepage_cta' => 'Fluid Homepage CTA (1.125:1)',
				'slide' => 'Homepage Slide (3:1)',
				'three-by-two' => 'Wide Rectangle (3:2)',
				'icon' => 'Icon (1:1)',
				'logo' => 'Site Logo (2.5:1)',
			) );
		}

		add_filter( 'intermediate_image_sizes_advanced', 'remove_default_image_sizes' );
		// Remove default image sizes
		function remove_default_image_sizes( $sizes ) {
			unset( $sizes['small']); // 150px
			unset( $sizes['medium']); // 300px
			unset( $sizes['large']); // 1024px
			return $sizes;
		}

	}

	/* Register Menus for the Appearance UI */
	register_nav_menus( array( 'primary'    => 'Primary Navigation' ) );
	// Placeholder: register_nav_menus( array( 'side'       => 'Slide-out Side Navigation' ) );

	/* Load all theme library files */
	$theme_includes = array(
		//Front-End
		'/lib/helper-functions.php',                            // Basic reusable functions
		'/lib/script-and-style-helpers.php',                    // Helper functions to alter PW Core script and style enqueueing
		'/lib/relative-urls.php',                               // Enable relative URLs
		'/lib/images.php',                                      // Image sizes and thumbnail functions
		'/lib/no-comments.php',                                 // Disable Comments sitewide; admin menus, templates, feeds, links, registration, admin bar
		'/lib/no-emojis.php',                                   // Disable emoji styles, editor buttons, and DNS prefetch for assets
		'/lib/search.php',                                      // Use our custom search form, and create a [searchform] shortcode
		'/lib/nav.php',                                         // Alters the default WP nav to reflect the markup Bootstrap depends on
		'/lib/no-texturize-tags.php',                           // Filters out automatic WP tag texturization
		'/lib/restore-blog-page-content-editor.php',            // Restores Content Editor To Whichever Post is Marked 'Page For Posts'
		'/lib/disable-wpautop.php',                             // Forces WP to not auto-format line breaks with <p> tags unnecessarily
		'/lib/add-defer-preload-and-async-to-scripts.php',      // Intercepts normal HTMl output for scripts, adding the defer attribute
		'/lib/better-jquery.php',                               // Replaces the stock WP jquery and enqueues it with defer

		//Back-End
		'/lib/admin/admin-nav.php',                             // Moves dashboard and adds separator under our options page
		'/lib/admin/custom-post-types/custom-post-types.php',   // Manage custom post types
	);

	foreach( $theme_includes as $file){
		if( !$filepath = locate_template($file) ) {
			trigger_error("Error locating `$file` for inclusion!", E_USER_ERROR);
		}

		require_once $filepath;
	}
	unset($file, $filepath);

	$hook_result = apply_filters_deprecated( 'hello_elementor_theme_load_textdomain', [ true ], '2.0', 'hello_elementor_load_textdomain' );
	if ( apply_filters( 'hello_elementor_load_textdomain', $hook_result ) ) {
		load_theme_textdomain( 'hello-elementor', get_template_directory() . '/languages' );
	}

	$hook_result = apply_filters_deprecated( 'hello_elementor_theme_register_menus', [ true ], '2.0', 'hello_elementor_register_menus' );
	if ( apply_filters( 'hello_elementor_register_menus', $hook_result ) ) {
		register_nav_menus( array( 'menu-1' => __( 'Primary', 'hello-elementor' ) ) );
	}

	$hook_result = apply_filters_deprecated( 'hello_elementor_theme_add_theme_support', [ true ], '2.0', 'hello_elementor_add_theme_support' );
	if ( apply_filters( 'hello_elementor_add_theme_support', $hook_result ) ) {
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		add_theme_support( 'custom-logo', array(
			'height' => 100,
			'width' => 350,
			'flex-height' => true,
			'flex-width' => true,
		) );

		/*
		* Editor Style
		*/
		add_editor_style( 'editor-style.css' );

		/*
		* WooCommerce
		*/
		$hook_result = apply_filters_deprecated( 'hello_elementor_theme_add_woocommerce_support', [ true ], '2.0', 'hello_elementor_add_woocommerce_support' );
		if ( apply_filters( 'hello_elementor_add_woocommerce_support', $hook_result ) ) {
			// WooCommerce in general:
			add_theme_support( 'woocommerce' );
			// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0):
			// zoom:
			add_theme_support( 'wc-product-gallery-zoom' );
			// lightbox:
			add_theme_support( 'wc-product-gallery-lightbox' );
			// swipe:
			add_theme_support( 'wc-product-gallery-slider' );
		}
	}
}

/* ----------------------------------------------------------------------------------------------------------------- */
/* ----------------------------------- Front-end Scripts and Styles ------------------------------------------------ */
/* -------------Note this hooks to wp_enqueue_script but uses an in internal wrapper, wp_enqueue_script ----------- */
/* ----------------------------------------------------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'load_scripts' );
function load_scripts() {
	if ( ! isset( $theme_options) ) {
		$theme_options = get_option( 'theme_options' );
	}

	//Any scripts strictly necessary for the header

	//Load global scripts in the footer, as deferred:
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'match_height', get_template_directory_uri() . '/js/vendor/jquery.matchHeight.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/vendor/jquery.matchHeight.js' ), TRUE  );
	wp_enqueue_script( 'pfa_scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/scripts.js' ), TRUE  );

	//Load global site CSS, this will be SASS long term
	wp_enqueue_style( 'site-layout', get_template_directory_uri() . '/css/layout.css', array( ), filemtime( get_template_directory() . '/css/layout.css' ) );
	wp_enqueue_style( 'text', get_template_directory_uri() . '/css/text.css', array( ), filemtime( get_template_directory() . '/css/text.css' ) );
	wp_enqueue_style( 'action-network-embed', get_template_directory_uri() . '/css/better-action-network-embed.css', array( ), filemtime( get_template_directory() . '/css/text.css' ) );

	//Allow a few different colors stylesheets for testing
	//wp_enqueue_style( 'pfa-colors-default', get_template_directory_uri() . '/css/pfa-colors-default.css', array( ) );

	//Fonts
	wp_enqueue_style( 'fonts', get_template_directory_uri() . '/css/fonts.css', array( ), filemtime( get_template_directory() . '/css/fonts.css' ) );

	/* --------------- Conditional Script and Style Loading based on page template: ------------------ */

	//Example conditional enqueueing based on template; here 2020 before/after slider
	if ( is_page_template( 'placeholder-nonexistent-template.php' ) ) {
		//wp_enqueue_script( 'twenty-twenty', get_template_directory_uri().'/js/vendor/jquery.twentytwenty.js', array( 'jquery' ), NULL, TRUE );
		//wp_enqueue_script( 'twenty-twenty-activation', get_template_directory_uri().'/js/vendor/twenty-twenty-activation.js', array( 'jquery', 'twenty-twenty' ), NULL, TRUE );
	}

}

/* ----------------------------------------------------------------------------------------------------------------- */
/* ----------------------------------- Back-end Scripts and Styles ------------------------------------------------ */
/* ----------------------------------------------------------------------------------------------------------------- */

//Global styles and scripts for the WP Backend
add_action( 'admin_enqueue_scripts', 'enqueue_admin_style' );
function enqueue_admin_style() {
	wp_register_script( 'pfa_wp_admin_js', get_template_directory_uri() . '/lib/admin/admin-scripts.js', false, NULL );
	wp_enqueue_script('pfa_wp_admin_js' );
	wp_register_style( 'pfa_wp_admin_css', get_template_directory_uri() . '/lib/admin/admin-style.css', false, NULL );
	wp_enqueue_style( 'pfa_wp_admin_css' );
}


/* ----------------------------------------------------------------------------------------------------------------- */
/* ----------------------------------- Base Theme Functions: "Hello Elementor" ------------------------------------- */
/* ----------------------------------------------------------------------------------------------------------------- */
/*
* Editor Style
*/
add_editor_style( 'editor-style.css' );

$hook_result = apply_filters_deprecated( 'hello_elementor_theme_load_textdomain', [ true ], '2.0', 'hello_elementor_load_textdomain' );
if ( apply_filters( 'hello_elementor_load_textdomain', $hook_result ) ) {
	load_theme_textdomain( 'hello-elementor', get_template_directory() . '/languages' );
}

$hook_result = apply_filters_deprecated( 'hello_elementor_theme_register_menus', [ true ], '2.0', 'hello_elementor_register_menus' );
if ( apply_filters( 'hello_elementor_register_menus', $hook_result ) ) {
	register_nav_menus( array( 'menu-1' => __( 'Primary', 'hello-elementor' ) ) );
}

/*
* WooCommerce
*/
$hook_result = apply_filters_deprecated( 'hello_elementor_theme_add_woocommerce_support', [ true ], '2.0', 'hello_elementor_add_woocommerce_support' );
if ( apply_filters( 'hello_elementor_add_woocommerce_support', $hook_result ) ) {
	// WooCommerce in general:
	add_theme_support( 'woocommerce' );
	// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0):
	// zoom:
	add_theme_support( 'wc-product-gallery-zoom' );
	// lightbox:
	add_theme_support( 'wc-product-gallery-lightbox' );
	// swipe:
	add_theme_support( 'wc-product-gallery-slider' );
}

/*
* Theme Scripts & Styles
*/
if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	function hello_elementor_scripts_styles() {
		$hook_result = apply_filters_deprecated( 'hello_elementor_theme_enqueue_style', [ true ], '2.0', 'hello_elementor_enqueue_style' );
		if ( apply_filters( 'hello_elementor_enqueue_style', $hook_result ) ) {
			wp_enqueue_style( 'hello-elementor-style', get_stylesheet_uri() );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

/*
* Register Elementor Locations
*/
if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		$hook_result = apply_filters_deprecated( 'hello_elementor_theme_register_elementor_locations', [ true ], '2.0', 'hello_elementor_register_elementor_locations' );
		if ( apply_filters( 'hello_elementor_register_elementor_locations', $hook_result ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

/*
* Set default content width
*/
if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );
/* End base Theme Functionality -- "Hello Elementor" ------ */
