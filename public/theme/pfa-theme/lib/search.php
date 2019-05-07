<?php 
namespace PFA;
add_filter( 'get_search_form', '\PFA\custom_search_form' );
// Allows for a custom search form, in a nonostandard location (normally in root WP directory)
function custom_search_form() {
	ob_start();
	$output = NULL;
	
	$form_path =  get_template_directory() .  '/partials/searchform.php';
	require( $form_path );

	$output = ob_get_contents();
	ob_end_clean();
	return $output;
	
}

// [searchform]
add_shortcode( 'searchform', '\PFA\custom_search_form_shortcode');
function custom_search_form_shortcode(){
        
	return custom_search_form( );
}
