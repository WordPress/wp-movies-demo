<?php

/**
 * Plugin Name:       WP Movies
 * Version:           0.1.0
 * Requires at least: 6.0
 * Requires PHP:      5.6
 * Description:       Plugin that demoes the usage of the Interactivity API.
 * Author:            WordPress Team
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-movies-demo
 */

require_once __DIR__ . '/lib/custom-post-types.php';
require_once __DIR__ . '/lib/custom-taxonomies.php';
require_once __DIR__ . '/lib/custom-query-block.php';
require_once __DIR__ . '/lib/db-update/index.php';


// Check if Gutenberg plugin is active.
if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
if ( ! is_plugin_active( 'block-interactivity-experiments/wp-directives.php' ) ) {
	// Show an error message.
	add_action(
		'admin_notices',
		function () {
			echo sprintf( '<div class="error"><p>%s</p></div>', __( 'This plugin requires the WP Directives plugin to be installed and activated.', 'wp-movies-demo' ) );
		}
	);

	// Deactivate the plugin.
	deactivate_plugins( plugin_basename( __FILE__ ) );
	return;
}

add_action(
	'init',
	function () {
		register_block_type( __DIR__ . '/build/blocks/post-favorite' );
		register_block_type( __DIR__ . '/build/blocks/favorites-number' );
		register_block_type( __DIR__ . '/build/blocks/movie-data' );
		register_block_type( __DIR__ . '/build/blocks/movie-score' );
		register_block_type( __DIR__ . '/build/blocks/movie-search' );
		register_block_type( __DIR__ . '/build/blocks/movie-trailer-button' );
		register_block_type( __DIR__ . '/build/blocks/video-player' );
		register_block_type( __DIR__ . '/build/blocks/movie-tabs' );
		register_block_type( __DIR__ . '/build/blocks/page-background' );
	}
);


// We need these filters to ensure the view.js files can access the window.wp.interactivity
// Once the bundling is solved and we stop using window.wp.interactivity we can remove them.
add_filter(
	'render_block_wpmovies/favorites-number',
	function ( $content ) {
		wp_register_script(
			'wpmovies/favorites-number',
			plugin_dir_url( __FILE__ ) . 'build/blocks/favorites-number/view.js',
			array( 'wp-directive-runtime' ),
			'1.0.0',
			true
		);
		wp_enqueue_script( 'wpmovies/favorites-number' );
		return $content;
	}
);

add_filter(
	'render_block_wpmovies/movie-search',
	function ( $content ) {
		wp_register_script(
			'wpmovies/movie-search',
			plugin_dir_url( __FILE__ ) . 'build/blocks/movie-search/view.js',
			array( 'wp-directive-runtime' ),
			'1.0.0',
			true
		);
		wp_enqueue_script( 'wpmovies/movie-search' );
		return $content;
	}
);

add_filter(
	'render_block_wpmovies/post-favorite',
	function ( $content ) {
		wp_register_script(
			'wpmovies/post-favorite',
			plugin_dir_url( __FILE__ ) . 'build/blocks/post-favorite/view.js',
			array( 'wp-directive-runtime' ),
			'1.0.0',
			true
		);
		wp_enqueue_script( 'wpmovies/post-favorite' );
		return $content;
	}
);

add_filter(
	'render_block_wpmovies/video-player',
	function ( $content ) {
		wp_register_script(
			'wpmovies/video-player',
			plugin_dir_url( __FILE__ ) . 'build/blocks/video-player/view.js',
			array( 'wp-directive-runtime' ),
			'1.0.0',
			true
		);
		wp_enqueue_script( 'wpmovies/video-player' );
		return $content;
	}
);

add_filter(
	'render_block_wpmovies/movie-tabs',
	function ( $content ) {
		wp_register_script(
			'wpmovies/movie-tabs',
			plugin_dir_url( __FILE__ ) . 'build/blocks/movie-tabs/view.js',
			array( 'wp-directive-runtime' ),
			'1.0.0',
			true
		);
		wp_enqueue_script( 'wpmovies/movie-tabs' );
		return $content;
	}
);

// ADD CRON EVENTS TO IMPORT MOVIES DAILY
// Create the necessary hook
add_action( 'cron_wpmovies_add_movies', 'wpmovies_add_movies' );

// Start cron when plugin is activated
register_activation_hook( __FILE__, 'movies_demo_plugin_activation' );
function movies_demo_plugin_activation() {
	if ( ! wp_next_scheduled( 'cron_wpmovies_add_movies' ) ) {
		wp_schedule_event( time(), 'daily', 'cron_wpmovies_add_movies' );
	}
}

// Remove cron events when plugin is deactivated
register_deactivation_hook( __FILE__, 'movies_demo_plugin_deactivation' );
function movies_demo_plugin_deactivation() {
	$timestamp = wp_next_scheduled( 'cron_wpmovies_add_movies' );
	wp_unschedule_event( $timestamp, 'cron_wpmovies_add_movies' );
}
