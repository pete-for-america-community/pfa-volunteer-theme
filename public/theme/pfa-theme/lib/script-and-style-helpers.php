<?php 
/**
*  Wrapper for wp_enqueue_script
*  Adds the script to be enqueued to the global $pfa_scripts variable for additional processing
*
* All @param match wp_enqueue_script
*/
function pfa_enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = false ) {
    global $pfa_scripts;
    $pfa_scripts[] = $handle;
    wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
}

/**
*  Wrapper for wp_enqueue_style
*  Adds the style to be enqueued to the global $pfa_styles variable for additional processing
*
* All @param match wp_enqueue_style
*/
function pfa_enqueue_style( $handle, $src = '', $deps = array(), $ver = false, $media = 'all' ) {
    global $pfa_styles;
    $pfa_styles[] = $handle;
    wp_enqueue_style( $handle, $src, $deps, $ver, $media );
}
