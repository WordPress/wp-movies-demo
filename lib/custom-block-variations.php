<?php
/**
 * Registers movies block variations for the 'core/paragraph' block.
 *
 * @param array         $variations An array of block variations.
 * @param WP_Block_Type $block_type The block type object.
 *
 * @return array The updated array of block variations.
 */
function wpmovies_block_type_variations( $variations, $block_type ) {
	if ( 'core/paragraph' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'wpmovies/movie-runtime',
			'title'      => 'WP Movies - Movie Runtime',
			'icon'       => 'clock',
			'attributes' => array(
				'metadata' => array(
					'bindings' => array(
						'content' => array(
							'source' => 'core/post-meta',
							'args'   => array(
								'key' => 'wpmovies_runtime',
							),
						),
					),
				),
			),
		);
		$variations[] = array(
			'name'       => 'wpmovies/movie-release-date',
			'title'      => 'WP Movies - Movie Release Date',
			'icon'       => 'calendar',
			'attributes' => array(
				'metadata' => array(
					'bindings' => array(
						'content' => array(
							'source' => 'core/post-meta',
							'args'   => array(
								'key' => 'wpmovies_release_date',
							),
						),
					),
				),
			),
		);
	}
	return $variations;
}
add_filter( 'get_block_type_variations', 'wpmovies_block_type_variations', 10, 2 );
