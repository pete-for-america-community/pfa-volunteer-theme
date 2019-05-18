<?php
namespace PFA;
if ( current_theme_supports('make-relative-urls-in-content-absolute') ) {

    $abs_filters = array(
        'the_content'
    );

    add_filters($abs_filters, '\PFA\output_relative_urls_as_absolute_urls');
}

/**
 * Replace relative URLs with absolute URLs  
  */
function output_relative_urls_as_absolute_urls($input) {
    $absolute_replacement = site_url() . '$1';
    $relative_pattern = '/[\'"](?!https?:\/\/)(\/.*?\/?)[\'"]/i';
    return preg_replace( $relative_pattern, $absolute_replacement, $input );
}
