<?php
/**
 * Custom Query Block.
 *
 * @package wpmovies
 */

/**
 * Check that the block is a movie or a cast variation of the Query Loop block.
 *
 *  @param array $parsed_block The block being rendered.
 */
function wpmovies_is_demo_variation( $parsed_block ) {
	return isset( $parsed_block['attrs']['namespace'] )
		&& substr( $parsed_block['attrs']['namespace'], 0, 8 ) === 'wpmovies';
}

/**
 * Update the query of the Query Loop block, if the block is
 * a movie or a cast variation of the Query Loop block. So it loads
 * the movies or the actors depending if the user is in the actor or the movie page.
 *
 * @param string $pre_render The pre-rendered content. Default null.
 * @param array  $parsed_block The block being rendered.
 */
function wpmovies_update_demo_query( $pre_render, $parsed_block ) {
	if ( 'core/query' !== $parsed_block['blockName'] ) {
		return;
	}

	if ( wpmovies_is_demo_variation( $parsed_block ) ) {
		add_filter(
			'query_loop_block_query_vars',
			'wpmovies_build_query',
			10,
			1
		);
	}
};

/**
 * Return a new query, with the actors of the movie or
 * the movies of an actor, depending the post type rendered.
 *
 * @param array $query Parameters for WP_Query as parsed by the block context.
 * @return array $query  The new query, a movies or an actors one.
 */
function wpmovies_build_query( $query ) {
	// Order by popularity.
	$order_metafield = '';
	if ( 'movies' === $query['post_type'] ) {
		$order_metafield     = 'wpmovies_vote_average';
		$query['meta_query'] = array(
			array(
				'key'     => 'wpmovies_vote_count',
				'value'   => '3000',
				'type'    => 'NUMERIC',
				'compare' => '>',
			),
		);
	} elseif ( 'actors' === $query['post_type'] ) {
		$order_metafield = 'wpmovies_actors_popularity';
	};
	$query['meta_key'] = $order_metafield;
	$query['orderby']  = 'meta_value_num';
	$query['order']    = 'DESC';

	// Get correct taxonomy.
	$taxonomy_type = null;
	if ( is_category() ) {
		$taxonomy_type = 'category';
		$category      = get_category( get_query_var( 'cat' ) );
		$cat_id        = $category->cat_ID;
		$wp_term       = get_term_by( 'id', $cat_id, 'category' );
	} else {
		global $post;
		if ( 'movies' === $post->post_type ) {
			$taxonomy_type = 'movies_tax';
		}
		if ( 'actors' === $post->post_type ) {
			$taxonomy_type = 'actors_tax';
		}
		if ( null === $taxonomy_type ) {
			return $query;
		}
		$wp_term = get_term_by( 'slug', $post->post_name, $taxonomy_type );
	}
	if ( ! $wp_term ) { // Check this, could exist the case that we did not import the actor of the movie.
		return $query;
	}
	$replace_query = array(
		'taxonomy'         => $taxonomy_type,
		'terms'            => array( $wp_term->term_id ),
		'include_children' => false,
	);

	$new_query = array_replace( $query, array( 'tax_query' => array( $replace_query ) ) );
	return $new_query;
}

add_action( 'pre_render_block', 'wpmovies_update_demo_query', 10, 2 );

/**
 * Add the movie and the cast variations to the Query Loop block.
 */
function wpmovies_add_query_loop_variations() {
	if ( file_exists( plugin_dir_path( __DIR__ ) . 'build/query-loop-variations.asset.php' ) ) {
		$asset_file = include( plugin_dir_path( __DIR__ ) . 'build/query-loop-variations.asset.php' );
		wp_enqueue_script(
			'query-loop-variations',
			plugin_dir_url( __DIR__ ) . 'build/query-loop-variations.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'wpmovies_add_query_loop_variations' );
