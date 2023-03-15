<?php
$wrapper_attributes = get_block_wrapper_attributes();

store(
	array(
		'state'     => array(
			'wpmovies' => array(
				'favoriteMovies' => array(),
			),
		),
		'selectors' => array(
			'wpmovies' => array(
				'favCount'              => 0,
				'isFavoriteMoviesEmpty' => 'https://s.w.org/images/core/emoji/14.0.0/svg/1f90d.svg',
			),
		),
	)
)

?>

<div <?php echo $wrapper_attributes; ?>>
	<img class="emoji" alt=":heart:" wp-bind:src="selectors.wpmovies.isFavoriteMoviesEmpty" />
	<span wp-text="selectors.wpmovies.favCount"></span>
</div>
