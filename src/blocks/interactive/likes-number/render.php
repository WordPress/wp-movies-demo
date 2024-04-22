<?php
/**
 * Server rendering for the movie like button block.
 *
 * @package wpmovies
 */

$wrapper_attributes = get_block_wrapper_attributes();
$play_icon          = file_get_contents( get_template_directory() . '/assets/empty-heart.svg' );

wp_interactivity_state(
	'wpmovies',
	array(
		'likedMovies'           => array(),
		'likesCount'            => 0,
		'isLikedMoviesNotEmpty' => false,
	),
);
?>

<div 
	data-wp-interactive="wpmovies"
	<?php echo $wrapper_attributes; ?>
	data-wp-class--wpmovies-liked="state.isLikedMoviesNotEmpty">
	<?php echo $play_icon; ?>
	<span data-wp-text="state.likesCount"></span>
</div>
