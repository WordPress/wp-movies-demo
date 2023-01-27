<?php

function setup_theme() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'setup_theme' );
