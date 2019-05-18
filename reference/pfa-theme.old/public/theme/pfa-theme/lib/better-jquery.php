<?php 
namespace PFA;
/**
* Remove the default jquery WP core file in favor of a locally hosted one without needing jquery-migrate
*/
function replace_jquery_src() {
    if ( ! is_admin() ) {

        // Remove the default jQuery
        wp_deregister_script( 'jquery' );

        // Register our own under 'jquery' and enqueue it
        wp_register_script( 'jquery', get_template_directory_uri() . '/js/vendor/jquery-1.11.3-min.js', false, NULL, TRUE );
        wp_enqueue_script( 'jquery' );
    }
}
add_action( 'init', '\PFA\replace_jquery_src' );