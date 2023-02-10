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
			'client_side_navigation' => false,
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
function wp_directives_register_scripts() {
	wp_register_script(
		'wp-directive-vendors',
		plugins_url( 'build/vendors.js', __DIR__ ),
		array(),
		'1.0.0',
		true
	);
	wp_register_script(
		'wp-directive-runtime',
		plugins_url( 'build/runtime.js', __DIR__ ),
		array( 'wp-directive-vendors' ),
		'1.0.0',
		true
	);

	// For now we can always enqueue the runtime. We'll figure out how to
	// conditionally enqueue directives later.
	wp_enqueue_script( 'wp-directive-runtime' );

}
add_action( 'wp_enqueue_scripts', 'wp_directives_register_scripts' );


/**
 * Add the directive on the link tag to prefetch and run the client side transition.
 *
 * @param string $block_content The content of the block.
 */
function wp_directives_add_wp_link_attribute( $block_content ) {
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
				str_contains( $classes, 'query-pagination' ) ||
				str_contains( $classes, 'page-numbers' )
			) {
				$w->set_attribute(
					'wp-link',
					'{ "prefetch": true, "scroll": false }'
				);
			} else {
				$w->set_attribute( 'wp-link', '{ "prefetch": true }' );
			}
		}
	}
	return (string) $w;
}
// We go only through the Query Loops and the template parts until we find a better solution.
add_filter(
	'render_block_core/query',
	'wp_directives_add_wp_link_attribute',
	10,
	1
);
add_filter(
	'render_block_core/template-part',
	'wp_directives_add_wp_link_attribute',
	10,
	1
);


/**
 * Check if client side navigation is enabled.
 */
function wp_directives_get_client_side_navigation() {
	static $client_side_navigation = null;
	if ( is_null( $client_side_navigation ) ) {
		$client_side_navigation = apply_filters( 'client_side_navigation', false );
	}
	return $client_side_navigation;
}


function wp_directives_add_client_side_navigation_meta_tag() {
	if ( wp_directives_get_client_side_navigation() ) {
		echo '<meta itemprop="wp-client-side-navigation" content="active">';
	}
}
add_action( 'wp_head', 'wp_directives_add_client_side_navigation_meta_tag' );


/**
 * Return true if client side navigation is enabled.
 *
 * @return bool True if the client side navigation is enabled.
 */
function wp_directives_client_site_navigation_option() {
	$options = get_option( 'wp_directives_plugin_settings' );
	return $options['client_side_navigation'];
}
add_filter(
	'client_side_navigation',
	'wp_directives_client_site_navigation_option',
	9
);


function wp_directives_mark_interactive_blocks( $block_content, $block, $instance ) {
	if ( wp_directives_get_client_side_navigation() ) {
		return $block_content;
	}

	// Append the `wp-ignore` attribute for inner blocks of interactive blocks.
	if ( isset( $instance->parsed_block['isolated'] ) ) {
		$w = new WP_HTML_Tag_Processor( $block_content );
		$w->next_tag();
		$w->set_attribute( 'wp-ignore', '' );
		$block_content = (string) $w;
	}

	// Return if it's not interactive.
	if ( ! block_has_support( $instance->block_type, array( 'interactivity' ) ) ) {
		return $block_content;
	}

	// Add the `wp-island` attribute if it's interactive.
	$w = new WP_HTML_Tag_Processor( $block_content );
	$w->next_tag();
	$w->set_attribute( 'wp-island', '' );

	return (string) $w;
}
add_filter( 'render_block', 'wp_directives_mark_interactive_blocks', 10, 3 );


/**
 * Add a flag to mark inner blocks of isolated interactive blocks.
 */
function bhe_inner_blocks( $parsed_block, $source_block, $parent_block ) {
	if (
		isset( $parent_block ) &&
		block_has_support(
			$parent_block->block_type,
			array(
				'interactivity',
				'isolated',
			)
		)
	) {
		$parsed_block['isolated'] = true;
	}
	return $parsed_block;
}
add_filter( 'render_block_data', 'bhe_inner_blocks', 10, 3 );

require_once __DIR__ . '/settings-page.php';
