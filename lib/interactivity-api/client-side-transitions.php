<?php

/**
 * Add the directive on the link tag to prefetch and run the client side transition.
 *
 * @param $block_content The content of the block.
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
 * Add the meta tag 'wp-client-side-transitions' on the head.
 */
function wp_directives_client_site_transitions_meta_tag() {
	if ( apply_filters( 'client_side_transitions', false ) ) {
		echo '<meta itemprop="wp-client-side-transitions" content="active">';
	}
}
add_action( 'wp_head', 'wp_directives_client_site_transitions_meta_tag', 10, 0 );

/*
 * Return if client side transitions are enabled.
 * @return bool True if the client side transitions are enabled.
 */
function wp_directives_client_site_transitions_option() {
	$options = get_option( 'wp_directives_plugin_settings' );
	return is_array( $options ) ? $options['client_side_transitions'] : $options;
}
add_filter(
	'client_side_transitions',
	'wp_directives_client_site_transitions_option'
);
