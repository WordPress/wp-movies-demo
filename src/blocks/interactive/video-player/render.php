<?php
$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'wpmovies-video-player' )
);

wp_store(
	array(
		'state' => array(
			'currentVideo' => '',
			'isPlaying'    => false,
		),
	),
);
?>

<div
	id="wp-movies-video-player"
	data-wp-interactive=\'{"namespace":"wpmovies"}\'
	data-wp-bind--hidden="!selectors.isPlaying"
	<?php echo $wrapper_attributes; ?>
>
	<div class="wpmovies-video-wrapper">
		<div class="wpmovies-video-close">
			<button class="close-button" data-wp-on--click="actions.closeVideo">
				<?php _e( 'Close' ); ?>
			</button>
		</div>
		<iframe
			width="420"
			height="315"
			allow="autoplay"
			allowfullscreen
			data-wp-bind--src="state.currentVideo"
		></iframe>
	</div>
</div>
