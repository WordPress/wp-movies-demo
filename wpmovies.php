<?php
/**
 * Plugin Name:       WP Movies
 * Version:           0.1.36
 * Requires at least: 6.5
 * Requires PHP:      7.0
 * Description:       Plugin that demoes the usage of the Interactivity API.
 * Author:            WordPress Team
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-movies-demo
 * Requires Plugins:  gutenberg
 */

require_once __DIR__ . '/lib/custom-post-types.php';
require_once __DIR__ . '/lib/custom-taxonomies.php';
require_once __DIR__ . '/lib/custom-query-block.php';
require_once __DIR__ . '/lib/db-update/index.php';

add_action( 'init', 'auto_register_block_types' );

/**
 * Auto register all blocks found in the `build/blocks` folder.
 */
function auto_register_block_types() {
	if ( file_exists( __DIR__ . '/build/blocks/' ) ) {
		$interactive_block_json_files     = glob( __DIR__ . '/build/blocks/interactive/*/block.json' );
		$non_interactive_block_json_files = glob( __DIR__ . '/build/blocks/non-interactive/*/block.json' );
		$block_json_files                 = array_merge( $interactive_block_json_files, $non_interactive_block_json_files );

		// auto register all blocks that were found.
		foreach ( $block_json_files as $filename ) {
			$block_folder = dirname( $filename );
			register_block_type( $block_folder );
		};
	};
}

// ADD CRON EVENTS TO IMPORT MOVIES DAILY
// Create the necessary hook.
add_action( 'cron_wpmovies_add_movies', 'wpmovies_add_movies' );

// Start cron when plugin is activated.
register_activation_hook( __FILE__, 'movies_demo_plugin_activation' );
/**
 * Add cron events when plugin is activated.
 */
function movies_demo_plugin_activation() {
	if ( ! wp_next_scheduled( 'cron_wpmovies_add_movies' ) ) {
		wp_schedule_event( time(), 'daily', 'cron_wpmovies_add_movies' );
	}
}

register_deactivation_hook( __FILE__, 'movies_demo_plugin_deactivation' );
/**
 * Remove cron events when plugin is deactivated.
 */
function movies_demo_plugin_deactivation() {
	$timestamp = wp_next_scheduled( 'cron_wpmovies_add_movies' );
	wp_unschedule_event( $timestamp, 'cron_wpmovies_add_movies' );
}

// Avoid sending any JavaScript not related to the Interactivity API.
/**
 * Dequeue the Twemoji script.
 */
function dequeue_twemoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); // Emojis.
}
add_action( 'wp_enqueue_scripts', 'dequeue_twemoji' );
