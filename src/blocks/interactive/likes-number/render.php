<?php
$wrapper_attributes = get_block_wrapper_attributes();
$play_icon          = file_get_contents( get_template_directory() . '/assets/empty-heart.svg' );
$likedMovies				= array();

wp_initial_state('wpmovies', array(
	'likedMovies' => $likedMovies,
	'likesCount'            => count( $likedMovies ),
	'isLikedMoviesNotEmpty' => count( $likedMovies ) > 0,
));
?>

<div 
	<?php echo $wrapper_attributes; ?>
	data-wp-class--wpmovies-liked="selectors.wpmovies.isLikedMoviesNotEmpty">
	<?php echo $play_icon; ?>
	<span data-wp-text="selectors.wpmovies.likesCount"></span>
</div>
