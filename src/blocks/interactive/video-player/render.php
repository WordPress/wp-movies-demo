<?php
/**
 * Server rendering for the video player block.
 *
 * @package wpmovies
 */

$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'wpmovies-video-player' )
);

wp_interactivity_state(
	'wpmovies',
	array(
		'currentVideo' => '',
		'isPlaying'    => false,
	),
);
?>

<div
	<?php echo $wrapper_attributes; ?>
	id="wp-movies-video-player"
	data-wp-interactive="wpmovies"
	data-wp-bind--hidden="!state.isPlaying"
>
	<div class="wpmovies-video-wrapper">
		<div class="wpmovies-video-close">
			<button
				class="close-button"
				data-wp-on--click="actions.closeVideo"
			>
				<?php _e( 'Close', 'wp-movies-demo' ); ?>
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
