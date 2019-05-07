<?php
namespace PFA;

/* Placeholders for now
add_action( 'admin_init', '\PFA\add_admin_menu_separator' );
add_action( 'admin_menu', '\PFA\set_admin_menu_separator' );
*/

// Insert a blank "separator" at the $position in nav menu
function add_admin_menu_separator( $position ) {

	// There aren't helper functions so we must scope this into ours
	global $menu;
	
	$menu[ $position ] = array(
		0	=>	'',
		1	=>	'read',
		2	=>	'separator' . $position,
		3	=>	'',
		4	=>	'wp-menu-separator'
	);

}

// Insert our separator at position 1 (between Options at 0 and Dashboard at 2)
function set_admin_menu_separator() {
	do_action( 'admin_init', 1 );   
}
