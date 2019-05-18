<?php
namespace PFA;
/**
 * Intercepts enqueued script assets and adds the defer attribute
 */
add_filter('script_loader_tag', '\PFA\add_defer_attribute', 10, 2);
function add_defer_attribute($tag, $handle) {
    global $pfa_scripts;
    if ( ! in_array( $handle, $pfa_scripts ) ) {
        return $tag;
    }

    return str_replace( ' src', ' defer="defer" src', $tag );
}


/**
 * Intercepts enqueued stylesheets and adds the preload attribute
 */
function add_preload_attribute($tag, $handle) {
    global $pfa_styles;

    if ( ! in_array( $handle, $pfa_styles ) ) {
        return $tag;
    }
    return preg_replace( '/rel=\Wstylesheet\W/', 'rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"', $tag );
}
add_filter('style_loader_tag', '\PFA\add_preload_attribute', 10, 4);

