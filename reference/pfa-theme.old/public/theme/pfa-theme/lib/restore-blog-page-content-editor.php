<?php
namespace PFA;

	/**
	 * Add the default wp-editor to the page designated 'Page for Posts' in the customizer
	 *
	 * @param $post
	 * @return void
	 */
	function restore_editor_to_page_for_posts($post)
	{
		if( $post->ID != get_option('page_for_posts') ) { return; }

		remove_action('edit_form_after_title', '_wp_posts_page_notice');
		add_post_type_support('page', 'editor');
	}
	add_action('edit_form_after_title', '\PFA\restore_editor_to_page_for_posts', 0);


	/**
	 * Automatically output the content of the Page for Posts when the template is redered, prepended to a custom marker
	 * @param  string $buffered_post_content the entire page's markup, captured between wp_head and wp_footer calls
	 * @return string                        modified page markup
	 */
	function output_page_for_posts_content( $buffered_post_content ) {

		//Insert the post's content before the end of our content (comment) delimiter
		$match = '<!-- /.content -->';
		$pos = strpos( $buffered_post_content, $match );
		$insert = get_post( get_option('page_for_posts') )->post_content;

		if ( $pos && $pos > 0) {
			$buffered_post_content = substr_replace( $buffered_post_content, $insert, $pos, 0 );
		}

		return $buffered_post_content;

	}
