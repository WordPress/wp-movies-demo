<?php

/**
 * Register the scripts - Temporary until we have the bundling of the Interactivity API ready as a package.
 */

function wp_directives_loader() {
	// Load the Admin page.
	require_once __DIR__ . '/settings-page.php';
}
add_action( 'plugins_loaded', 'wp_directives_loader' );

/**
 * Add default settings upon activation.
 */
function wp_directives_activate() {
	add_option(
		'wp_directives_plugin_settings',
		array(
			'client_side_transitions' => false,
		)
	);
}
register_activation_hook( __FILE__, 'wp_directives_activate' );

/**
 * Delete settings on uninstall.
 */
function wp_directives_uninstall() {
	delete_option( 'wp_directives_plugin_settings' );
}
register_uninstall_hook( __FILE__, 'wp_directives_uninstall' );

/**
 * Register the scripts
 */
function wp_directives_register_scripts()
{
	wp_register_script(
		'wp-directive-vendors',
		plugins_url('../build/vendors.js', __DIR__),
		[],
		'1.0.0',
		true
	);
	wp_register_script(
		'wp-directive-runtime',
		plugins_url('../build/runtime.js', __DIR__),
		['wp-directive-vendors'],
		'1.0.0',
		true
	);

	// For now we can always enqueue the runtime. We'll figure out how to
	// conditionally enqueue directives later.
	wp_enqueue_script('wp-directive-runtime');

	wp_register_style(
		'transition-styles',
		plugin_dir_url(__DIR__) . '../transition-styles.css'
	);
	wp_enqueue_style('transition-styles');
}
add_action('wp_enqueue_scripts', 'wp_directives_register_scripts');

require_once __DIR__ . '/client-side-transitions.php';
require_once __DIR__ . '/settings-page.php';
