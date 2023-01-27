<?php

function setup_theme() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'setup_theme' );

function enqueue_editor_styles() {
	wp_enqueue_style( 'editor-style', get_theme_file_uri( '/editor-style.css' ) );
}
add_action( 'enqueue_block_editor_assets', 'enqueue_editor_styles' );
