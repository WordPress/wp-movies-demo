<?php

/**
 * Register the actor birthday source.
 */
function wp_movies_register_block_bindings_actor_birth_place() {
	register_block_bindings_source(
		'wpmovies/actor-birth-place',
		array(
			'label'              => __( 'Actor Birth Place', 'wp-movies' ),
			'uses_context'       => array( 'postId' ),
			'get_value_callback' => function( $source_args, $block_instance ) {
				return get_post_meta( $block_instance->context['postId'], '_wpmovies_actors_place_of_birth', true );
			},
		)
	);
}

add_action( 'init', 'wp_movies_register_block_bindings_actor_birth_place' );
