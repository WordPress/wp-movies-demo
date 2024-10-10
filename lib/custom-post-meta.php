<?php
/**
 * Registers the custom meta field 'wpmovies_runtime' for movie posts.
 *
 * This function registers a meta field named 'wpmovies_runtime' for the 'movies'
 * post type. The meta field is configured to be shown in the REST API, is a single
 * value, and has a default value of '1h 36m'. The meta field is of type 'string'.
 *
 * @return void
 */
function wp_movies_register_meta() {
	register_meta(
		'post',
		'wpmovies_runtime',
		array(
			'show_in_rest'   => true,
			'single'         => true,
			'type'           => 'string',
			'object_subtype' => 'movies',
			'default'        => '1h 36m',
			'label'          => 'Movie Runtime',
		)
	);

	register_meta(
		'post',
		'wpmovies_release_date',
		array(
			'show_in_rest'   => true,
			'single'         => true,
			'type'           => 'string',
			'object_subtype' => 'movies',
			'default'        => '14th March 1972',
			'label'          => 'Movie Release Date',
		)
	);
}
add_action( 'init', 'wp_movies_register_meta' );


/**
 * Renders the runtime of a movie in hours and minutes.
 *
 * This function takes the runtime value in minutes and converts it to a
 * human-readable format of hours and minutes. If the runtime is not greater
 * than zero, it returns 'N/A'.
 *
 * @param mixed  $value       The original runtime value.
 * @param string $source_name The source of the meta data. This function only processes
 *                            values from 'core/post-meta'.
 * @param array  $source_args Additional arguments passed to the function.
 *
 * @return string The formatted runtime in 'Xh Ym' format or 'N/A' if the runtime is not valid.
 */
function wp_movies_render_movie_data( $value, $source_name, $source_args ) {
	if ( 'core/post-meta' !== $source_name ) {
		return $value;
	}
	switch ( $source_args['key'] ) {
		case 'wpmovies_runtime':
			$runtime_minutes = intval( $value );
			$value           = $runtime_minutes > 0 ? intdiv( $runtime_minutes, 60 ) . 'h ' . ( $runtime_minutes % 60 ) . 'm' : __( 'N/A', 'wp-movies-demo' );
			break;
		case 'wpmovies_release_date':
			$value = gmdate( 'jS F Y', strtotime( $value ) );
			break;
	}
	return $value;
};

add_filter( 'block_bindings_source_value', 'wp_movies_render_movie_data', 10, 3 );
