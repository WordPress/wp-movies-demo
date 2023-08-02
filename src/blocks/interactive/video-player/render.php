<?php
$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'wpmovies-video-player' )
);

wp_store(
	array(
		'state'     => array(
			'wpmovies' => array(
				'currentVideo' => '',
			),
		),
		'selectors' => array(
			'wpmovies' => array(
				'isPlaying' => false,
			),
		),
	),
);
?>

<div id="wp-movies-video-player" data-wp-bind--hidden="!selectors.wpmovies.isPlaying" <?php echo $wrapper_attributes; ?>>
	<div class="wpmovies-video-wrapper">
		<div class="wpmovies-video-close">
			<button class="close-button" data-wp-on--click="actions.wpmovies.closeVideo">
				<?php _e( 'Close' ); ?>
			</button>
		</div>
		<iframe
			width="420"
			height="315"
			allow="autoplay"
			allowfullscreen
			data-wp-bind--src="state.wpmovies.currentVideo"
		></iframe>
	</div>
</div>
