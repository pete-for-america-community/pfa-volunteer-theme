<?php 
namespace PFA;

/**
 * Removes the default WP texturize tags
 */
add_filter( 'no_texturize_tags', '\PFA\no_texturzie_tags' );
function no_texturzie_tags( $tags ) {
    $tags[] = 'blockquote';
    $tags[] = 'p';
    return $tags;
}
