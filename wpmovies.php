<?php

/**
 * Plugin Name:       WP Movies
 * Version:           0.1.26
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

add_action( 'init', 'auto_register_block_types' );

// Register all blocks found in the `build/blocks` folder.
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

function add_script_dependency( $handle, $dep, $in_footer ) {
	global $wp_scripts;

	$script = $wp_scripts->query( $handle, 'registered' );
	if ( ! $script ) {
		return false;
	}

	if ( ! in_array( $dep, $script->deps, true ) ) {
		$script->deps[] = $dep;

		if ( $in_footer ) {
			// move script to the footer
			$wp_scripts->add_data( $handle, 'group', 1 );
		}
	}

	return true;
}

add_action( 'wp_enqueue_scripts', 'auto_inject_interactivity_dependency' );

function auto_inject_interactivity_dependency() {
	$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

	foreach ( $registered_blocks as $name => $block ) {
		$has_interactivity_support = $block->supports['interactivity'] ?? false;

		if ( ! $has_interactivity_support ) {
			continue;
		}
		foreach ( $block->view_script_handles as $handle ) {
			add_script_dependency( $handle, 'wp-directive-runtime', true );
		}
	}
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

function wp_directives_prefetch_page_numbers_false( $block_content ) {
	$site_url = parse_url( get_site_url() );
	$w        = new WP_HTML_Tag_Processor( $block_content );
	while ( $w->next_tag( 'a' ) ) {
		if ( $w->get_attribute( 'target' ) === '_blank' ) {
			break;
		}

		$link = parse_url( $w->get_attribute( 'href' ) );
		if ( ! isset( $link['host'] ) || $link['host'] === $site_url['host'] ) {
			$classes = $w->get_attribute( 'class' );
			if (
				str_contains( $classes, 'page-numbers' )
			) {
				$w->set_attribute(
					'data-wp-link',
					'{ "prefetch": false }'
				);
			}
		}
	}
	return (string) $w;
}
// We go only through the Query Loops and the template parts until we find a better solution.
add_filter(
	'render_block_core/query',
	'wp_directives_prefetch_page_numbers_false',
	20,
	1
);

function add_defer_attribute( $tag, $handle ) {
	if ( 'wp-directive-runtime' === $handle || 'wp-directive-vendors' === $handle ) {
		return str_replace( ' src', ' defer src', $tag );
	} else {
		return $tag;
	}

}
add_filter( 'script_loader_tag', 'add_defer_attribute', 10, 2 );
