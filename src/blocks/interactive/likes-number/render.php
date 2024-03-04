<?php
/**
 * Server rendering for the movie like button block.
 *
 * @package wpmovies
 */

$wrapper_attributes = get_block_wrapper_attributes();
$play_icon          = file_get_contents( get_template_directory() . '/assets/empty-heart.svg' );
$liked_movies       = array();

wp_interactivity_state(
	'wpmovies',
	array(
		'state'     => array(
			'likedMovies' => $liked_movies,
		),
		'selectors' => array(
			'likesCount'            => count( $liked_movies ),
			'isLikedMoviesNotEmpty' => count( $liked_movies ) > 0,
		),
	),
);
?>

<div 
	<?php echo $wrapper_attributes; ?>
	data-wp-class--wpmovies-liked="selectors.wpmovies.isLikedMoviesNotEmpty">
	<?php echo $play_icon; ?>
	<span data-wp-text="selectors.wpmovies.likesCount"></span>
</div>
