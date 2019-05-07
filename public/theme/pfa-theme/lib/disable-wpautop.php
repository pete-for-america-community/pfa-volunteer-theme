<?php 
namespace PFA;

// Removes the unnecessary auto formatting of paragraph tags onto line breaks in content blocks
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

//Handles CF7 autop 
add_filter('wpcf7_autop_or_not', '__return_false');
