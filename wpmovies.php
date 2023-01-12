<?php
/**
 * Plugin Name:       WP Movies
 * Version:           0.1.0
 * Requires at least: 6.0
 * Requires PHP:      5.6
 * Description:       Plugin to demonstrate a WordPress site with the Interactivity API.
 * Author:            WordPress Team
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-movies-demo
 */

require_once __DIR__ . '/lib/interactivity-api/init.php';
require_once __DIR__ . '/src/custom-post-types.php';
require_once __DIR__ . '/src/custom-taxonomies.php';
require_once __DIR__ . '/src/custom-query-block.php';


 // Check if Gutenberg plugin is active
if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
if ( ! is_plugin_active( 'gutenberg/gutenberg.php' ) ) {
	// Show an error message
	add_action(
		'admin_notices',
		function() {
			echo sprintf( '<div class="error"><p>%s</p></div>', __( 'This plugin requires the Gutenberg plugin to be installed and activated.', 'wp-movies-demo' ) );
		}
	);

	// Deactivate the plugin
	deactivate_plugins( plugin_basename( __FILE__ ) );
	return;
}

add_action('init', function () {
	register_block_type(__DIR__ . '/build/blocks/post-favorite');
	register_block_type(__DIR__ . '/build/blocks/favorites-number');
	register_block_type(__DIR__ . '/build/blocks/movie-data');
});


add_filter('render_block_wpmovies/favorites-number', function ($content) {
	wp_enqueue_script(
		'wpmovies/favorites-number',
		plugin_dir_url(__FILE__) . 'build/blocks/favorites-number/view.js'
	);
	return $content;
});