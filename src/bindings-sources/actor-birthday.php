<?php

/**
 * Register the actor birthday source.
 */
function wp_movies_register_block_bindings_actor_birthday() {
	register_block_bindings_source(
		'wpmovies/actor-birthday',
		array(
			'label'              => __( 'Actor Birthday', 'wp-movies' ),
			'uses_context'       => array( 'postId' ),
			'get_value_callback' => function( $source_args, $block_instance ) {
				$birthday = get_post_meta( $block_instance->context['postId'], '_wpmovies_actors_birthday', true );
				return gmdate( 'jS F Y', strtotime( $birthday ) );
			},
		)
	);
}

add_action( 'init', 'wp_movies_register_block_bindings_actor_birthday' );
