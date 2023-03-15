<?php
$wrapper_attributes = get_block_wrapper_attributes();
$play_icon          = file_get_contents( get_template_directory() . '/assets/empty-heart.svg' );

store(
	array(
		'state'     => array(
			'wpmovies' => array(
				'likedMovies' => array(),
			),
		),
		'selectors' => array(
			'wpmovies' => array(
				'likesCount'            => 0,
				'isLikedMoviesNotEmpty' => false,
			),
		),
	)
)

?>

<div <?php echo $wrapper_attributes; ?> wp-class:wpmovies-liked="selectors.wpmovies.isLikedMoviesNotEmpty">
	<?php echo $play_icon; ?><span wp-text="selectors.wpmovies.likesCount"></span>
</div>
