<?php

function wpmovies_register_cpts() {

	/**
	 * Post Type: Movies.
	 */

	$labels = array(
		'name'          => esc_html__( 'Movies', 'wp-movies-demo' ),
		'singular_name' => esc_html__( 'Movie', 'wp-movies-demo' ),
	);

	$args = array(
		'label'                 => esc_html__( 'Movies', 'wp-movies-demo' ),
		'labels'                => $labels,
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'rest_namespace'        => 'wp/v2',
		'has_archive'           => 'movies',
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'delete_with_user'      => false,
		'exclude_from_search'   => false,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'can_export'            => true,
		'rewrite'               => array(
			'slug'       => 'movies',
			'with_front' => true,
		),
		'query_var'             => true,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'taxonomies'            => array( 'category' ),
		'show_in_graphql'       => false,
	);

	register_post_type( 'movies', $args );

	/**
	 * Post Type: Actors.
	 */

	$labels = array(
		'name'          => esc_html__( 'Actors', 'wp-movies-demo' ),
		'singular_name' => esc_html__( 'Actor', 'wp-movies-demo' ),
	);

	$args = array(
		'label'                 => esc_html__( 'Actors', 'wp-movies-demo' ),
		'labels'                => $labels,
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'rest_namespace'        => 'wp/v2',
		'has_archive'           => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'delete_with_user'      => false,
		'exclude_from_search'   => false,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'can_export'            => true,
		'rewrite'               => array(
			'slug'       => 'actors',
			'with_front' => true,
		),
		'query_var'             => true,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'taxonomies'            => array( 'movies' ),
		'show_in_graphql'       => false,
	);

	register_post_type( 'actors', $args );
}

	add_action( 'init', 'wpmovies_register_cpts' );

