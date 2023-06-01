<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$play_icon          = file_get_contents( get_template_directory() . '/assets/empty-heart.svg' );

wp_store(
	array(
		'selectors' => array(
			'wpmovies' => array(
				'isMovieIncluded' => false,
			),
		),
	),
);
?>

<div
	<?php echo $wrapper_attributes; ?>
	data-wp-context='{ "post": { "id": <?php echo $post->ID; ?> } }'
>
	<div
		data-wp-on--click="actions.wpmovies.toggleMovie"
		data-wp-class--wpmovies-liked="selectors.wpmovies.isMovieIncluded"
	>
		<?php echo $play_icon; ?>
	</div>
</div>
