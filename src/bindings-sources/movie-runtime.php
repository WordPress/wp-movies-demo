<?php

/**
 * Register the move runtime source.
 */
function wp_movies_register_block_bindings_movie_runtime() {
	register_block_bindings_source(
		'wpmovies/movie-runtime',
		array(
			'label'              => __( 'Movie Runtime', 'wp-movies' ),
			'uses_context'       => array( 'postId' ),
			'get_value_callback' => function( $source_args, $block_instance ) {
				$runtime_minutes    = get_post_meta( $block_instance->context['postId'], '_wpmovies_runtime', true );
				return intdiv( $runtime_minutes, 60 ) . 'h ' . ( $runtime_minutes % 60 ) . 'm';
			},
		)
	);
}

add_action( 'init', 'wp_movies_register_block_bindings_movie_runtime' );
