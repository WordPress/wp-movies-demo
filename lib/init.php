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
function wp_directives_activate()
{
	add_option('wp_directives_plugin_settings', [
		'client_side_transitions' => false,
	]);
}
register_activation_hook(__FILE__, 'wp_directives_activate');

/**
 * Delete settings on uninstall.
 */
function wp_directives_uninstall()
{
	delete_option('wp_directives_plugin_settings');
}
register_uninstall_hook(__FILE__, 'wp_directives_uninstall');

require_once __DIR__ . '/client-side-transitions.php';