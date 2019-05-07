<?php 
namespace PFA;
/**
 * Image operations common to all site images
 * 
 * Useful for filtering to include srcsets, svg fallbacks, webp support, etc
 */

/**
* Removes width and height attributes from image tags
*
* @param string 	$html markup for an image tag
* @return string	image stripped of hard width and height values
*/
function remove_image_size_attributes( $html ) {
	return preg_replace( '/(width|height)="\d*"/', '', $html );
}

// Remove image size attributes from post thumbnails
add_filter( 'post_thumbnail_html', '\PFA\remove_image_size_attributes' );

// Remove image size attributes from images added to a WordPress post
add_filter( 'image_send_to_editor', '\PFA\remove_image_size_attributes' );


