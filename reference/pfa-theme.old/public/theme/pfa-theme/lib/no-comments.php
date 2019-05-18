<?php
namespace PFA;

/**
 * Remove all commenting functionality until we need it.
 * Basic safety practice
 */

//Turn off comments feed in the site feed
add_filter( 'feed_links_show_comments_feed', '__return_false' );

//Remove comments feed
function remove_single_comments_feed(){
	return;
}
add_filter( 'post_comments_feed_link', '\PFA\remove_single_comments_feed' );

//Disable automatic comments pages
function comments_feed_404( $object ) {
	if ( $object->is_comment_feed ) {
		wp_die( 'Page not found.', '', array(
			'response'  => 404,
			'back_link' => true, 
		));
	}
}
add_action( 'parse_query', '\PFA\comments_feed_404' );


// Disable support for comments and trackbacks in post types

function disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ( $post_types as $post_type ) {
		if ( post_type_supports( $post_type, 'comments' ) ) {
			remove_post_type_support( $post_type, 'comments' );
			remove_post_type_support( $post_type, 'trackbacks' );
		}
	}
}
add_action('admin_init', '\PFA\disable_comments_post_types_support');


// Close comments on the front-end

function disable_comments_status() {
	return FALSE;
}
add_filter('comments_open', '\PFA\disable_comments_status', 20, 2);
add_filter('pings_open', '\PFA\disable_comments_status', 20, 2);

// Hide existing comments
function disable_comments_hide_existing_comments( $comments ) {
	$comments = array();

	return $comments;
}
add_filter('comments_array', '\PFA\disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function disable_comments_admin_menu() {
	remove_menu_page( 'edit-comments.php' );
}
add_action('admin_menu', '\PFA\disable_comments_admin_menu');

// Redirect any user trying to access comments page
function disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ( $pagenow === 'edit-comments.php' ) {
		wp_redirect( admin_url() );
		exit;
	}
}
add_action('admin_init', '\PFA\disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function disable_comments_dashboard() {
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}
add_action('admin_init', '\PFA\disable_comments_dashboard');

// Remove comments links from admin bar
function disable_comments_admin_bar() {
	if ( is_admin_bar_showing() ) {
		remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
	}
}
add_action('init', '\PFA\disable_comments_admin_bar');
