<?php
function wpmovies_register_taxes() {

	/**
	 * Taxonomy: Actors.
	 */

	$labels = array(
		'name'          => esc_html__( 'Actors', 'wp-movies-demo' ),
		'singular_name' => esc_html__( 'Actor', 'wp-movies-demo' ),
	);

	$args = array(
		'label'                 => esc_html__( 'Actors', 'wp-movies-demo' ),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'query_var'             => true,
		'rewrite'               => array(
			'slug'       => 'actors_tax',
			'with_front' => true,
		),
		'show_admin_column'     => false,
		'show_in_rest'          => true,
		'show_tagcloud'         => false,
		'rest_base'             => 'actors_tax',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace'        => 'wp/v2',
		'show_in_quick_edit'    => false,
		'sort'                  => false,
		'show_in_graphql'       => false,
	);
	register_taxonomy( 'actors_tax', array( 'movies' ), $args );

	/**
	 * Taxonomy: Movies.
	 */

	$labels = array(
		'name'          => esc_html__( 'Movies', 'wp-movies-demo' ),
		'singular_name' => esc_html__( 'Movie', 'wp-movies-demo' ),
	);

	$args = array(
		'label'                 => esc_html__( 'Movies', 'wp-movies-demo' ),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'query_var'             => true,
		'rewrite'               => array(
			'slug'       => 'movies_tax',
			'with_front' => true,
		),
		'show_admin_column'     => false,
		'show_in_rest'          => true,
		'show_tagcloud'         => false,
		'rest_base'             => 'movies_tax',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace'        => 'wp/v2',
		'show_in_quick_edit'    => false,
		'sort'                  => false,
		'show_in_graphql'       => false,
	);
	register_taxonomy( 'movies_tax', array( 'actors' ), $args );
}

add_action( 'init', 'wpmovies_register_taxes', 0 );
