<?php
namespace PFA;
/**
 * Helper functions for common operations
 */

/**
 *  Bulk add an array of filters to a filter
 */
function add_filters($tags, $function) {
	foreach($tags as $tag) {
		add_filter($tag, $function);
	}
}

/**
 * Take an array of mixed associative and indexed values, converting any indexed values to new elements with the value as key and boolean TRUE as the value
 * Designed to process shortcode attributes with mixed parameter usages
 * Useful for third party shortcode operations
 *
 *      eg, allowing    [shortcode_name attrib_1="Has a parameter" attrib_2] 
 *      instead of      [shortcode_name attrib_1="Has a parameter" attrib_2="TRUE"]
 * 
 * @param $array
 * @return mixed
 */
function fill_parameterless_shortcodes( $array ) {
	foreach( $array as $key => $value ) {
		if ( is_numeric( $key ) ) {
			$array[$value] = TRUE;
			unset( $array[$key] );
		}
	}
	return $array;
}


/**
 * Returns a readable string from a delimited lowercase slug 
 * @param $str
 * @param string $delimiter
 *
 * @return string
 */
function slug_to_readable($str, $delimiter = '-'){
	return ucwords( str_replace( $delimiter, ' ', $str ) );
}


// Comparison function for reordering gallery arrays
function reorder_gallery_items( $a, $b ) {

	if ($a == $b) {
		return 0;
	}

	return ( $a['order'] < $b['order'] ) ? -1 : 1 ;
}


// Augments array_push to preserve associative key
function array_push_assoc($array, $key, $value){
    $array[ $key ] = $value;
    return $array;
}

// get_post_meta returns values as an array even if only one key exists. This flattens unnecessarily arrayed data 
function flatten_postmeta( $postmeta_array ){
    $return_array = array();
    foreach( $postmeta_array as $key => $val ){
        
        if( is_array( $val ) && count( $val ) == 1) {
            $return_array = array_push_assoc( $return_array, $key, $val[0]);
        } else {
            $return_array = array_push_assoc( $return_array, $key, $val);
        }
    }
    return $return_array;
}

//only returns a single value for any queried postmeta
function get_post_meta_single( $id ) { 
    if ( !$id ) { $id = get_the_id(); }
    
    $meta = (array) get_post_meta( $id );
    
    if ( $meta ) {
        $return_array = array();
        foreach ( $meta as $key => $val ) {
            $return_array = array_push_assoc( $return_array, $key, $val[0] );
        }
        return $return_array;
    }
}