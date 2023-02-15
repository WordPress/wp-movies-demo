<?php

function setup_theme() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'setup_theme' );

function enqueue_editor_styles() {
	wp_enqueue_style( 'editor-style', get_theme_file_uri( '/editor-style.css' ) );
}
add_action( 'enqueue_block_editor_assets', 'enqueue_editor_styles' );

function create_header_navigation() {

	$query = new WP_Query(
		array(
			'title'          => 'Header navigation',
			'post_type'      => 'wp_navigation',
			'posts_per_page' => 1,
		)
	);
	$pages = $query->posts;
	if ( ! empty( $pages ) ) {
		return null;
	}
	$header_navigation = array(
		'import_id'    => 123456789, // A magic number that is also used in the header.html file.
		'post_title'   => 'Header navigation',
		'post_status'  => 'publish',
		'post_type'    => 'wp_navigation',
		'post_name'    => 'header-navigation',
		'post_content' => '<!-- wp:navigation-link {"label":"Movies","url":"/","title":"Movies","kind":"custom","isTopLevelLink":true} /--> <!-- wp:navigation-link {"label":"Actors","url":"/actors","title":"Actors","kind":"custom","isTopLevelLink":true} /-->',
	);
	wp_insert_post( $header_navigation );

}
add_action( 'wp_loaded', 'create_header_navigation' );
