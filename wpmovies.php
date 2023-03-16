<?php

/**
 * Plugin Name:       WP Movies
 * Version:           0.1.2
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
		register_block_type( __DIR__ . '/build/blocks/interactive/movie-like-icon' );
		register_block_type( __DIR__ . '/build/blocks/interactive/likes-number' );
		register_block_type( __DIR__ . '/build/blocks/interactive/movie-search' );
		register_block_type( __DIR__ . '/build/blocks/interactive/movie-trailer-button' );
		register_block_type( __DIR__ . '/build/blocks/interactive/movie-like-button' );
		register_block_type( __DIR__ . '/build/blocks/interactive/video-player' );
		register_block_type( __DIR__ . '/build/blocks/interactive/movie-tabs' );
		register_block_type( __DIR__ . '/build/blocks/interactive/movie-genres' );
		register_block_type( __DIR__ . '/build/blocks/non-interactive/movie-data' );
		register_block_type( __DIR__ . '/build/blocks/non-interactive/movie-score' );
		register_block_type( __DIR__ . '/build/blocks/non-interactive/page-background' );
		register_block_type( __DIR__ . '/build/blocks/non-interactive/movie-release-date' );
		register_block_type( __DIR__ . '/build/blocks/non-interactive/movie-runtime' );
		register_block_type( __DIR__ . '/build/blocks/non-interactive/actor-birthday' );
		register_block_type( __DIR__ . '/build/blocks/non-interactive/actor-birth-place' );
	}
);

// We need these filters to ensure the view.js files can access the window.__experimentalInteractivity
// Once the bundling is solved and we stop using
// window.__experimentalInteractivity we can remove them.
enqueue_interactive_blocks_scripts( 'movie-like-icon' );
enqueue_interactive_blocks_scripts( 'likes-number' );
enqueue_interactive_blocks_scripts( 'movie-search' );
enqueue_interactive_blocks_scripts( 'movie-like-button' );
enqueue_interactive_blocks_scripts( 'video-player' );
enqueue_interactive_blocks_scripts( 'movie-tabs' );

/**
 * A helper function that enqueues scripts for the interactive blocks.
 *
 * @param string $block - The block name.
 * @return void
 */
function enqueue_interactive_blocks_scripts( $block ) {
	$interactive_block_filter = function ( $content ) use ( $block ) {
		wp_register_script(
			'wpmovies/' . $block,
			plugin_dir_url( __FILE__ ) . 'build/blocks/interactive/' . $block . '/view.js',
			array( 'wp-directive-runtime' ),
			'1.0.0',
			true
		);
		wp_enqueue_script( 'wpmovies/' . $block );
		return $content;
	};
	add_filter( 'render_block_wpmovies/' . $block, $interactive_block_filter );
}


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

// Avoid sending any JavaScript not related to the Interactivity API.
function dequeue_twemoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); // Emojis
}
add_action( 'wp_enqueue_scripts', 'dequeue_twemoji' );

/**
 * Add a unique key attribute to all images.
 *
 * TODO: Replace with `data-wp-key` once this is fixed:
 * https://github.com/WordPress/block-interactivity-experiments/issues/180
 *
 * @param $content The block content.
 * @return $content The block content with the added key attributes.
 */

function wpmovies_add_key_to_featured_image( $content ) {
	$p = new WP_HTML_Tag_Processor( $content );
	while ( $p->next_tag( array( 'tag_name' => 'img' ) ) ) {
		$src = $p->get_attribute( 'src' );
		if ( preg_match( '/\/([\w-]+)\.jpg$/', $src, $matches ) ) {
			$p->set_attribute( 'key', $matches[1] );
		}
	};
	return (string) $p;
}

add_filter( 'render_block', 'wpmovies_add_key_to_featured_image', 10, 1 );


/**
 * add aria-live region to query block so that the live
 * updating results are announced to screen readers.
 */
function wpmovies_add_aria_live_to_query_block( $content ) {
	$p = new WP_HTML_Tag_Processor( $content );
	$p->next_tag( array( 'class' => 'wp-block-query' ) );
	$p->set_attribute( 'aria-live', 'polite' );
	return (string) $p->get_updated_html();
}

add_filter( 'render_block_core/query', 'wpmovies_add_aria_live_to_query_block', 10, 1 );