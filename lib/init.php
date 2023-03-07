<?php

/**
 * Register the scripts
 */
function wpmovies_register_scripts()
{
	wp_register_script(
		'wp-directive-vendors',
		plugins_url('build/vendors.js', __DIR__),
		array(),
		'1.0.0',
		true
	);
}
add_action('wp_enqueue_scripts', 'wpmovies_register_scripts');
