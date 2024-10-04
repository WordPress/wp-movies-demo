<?php

/**
 * Register the actor birthday source.
 */
function wp_movies_register_block_bindings_movie_release_date() {
	register_block_bindings_source(
		'wpmovies/movie-release-date',
		array(
			'label'              => __( 'Movie Release Date', 'wp-movies' ),
			'uses_context'       => array( 'postId' ),
			'get_value_callback' => function( $source_args, $block_instance ) {
				$release_date = get_post_meta( $block_instance->context['postId'], '_wpmovies_release_date', true );
				return gmdate( 'jS F Y', strtotime( $release_date ) );
			},
		)
	);
}

add_action( 'init', 'wp_movies_register_block_bindings_movie_release_date' );
