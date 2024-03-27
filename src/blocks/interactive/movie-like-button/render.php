<?php
/**
 * Server rendering for the movie like button block.
 *
 * @package wpmovies
 */

$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$play_icon          = file_get_contents( get_template_directory() . '/assets/empty-heart.svg' );

wp_interactivity_state(
	'wpmovies',
	array(
		'isMovieIncluded' => false,
	),
);
?>

<div
	data-wp-interactive="wpmovies"
	<?php echo $wrapper_attributes; ?>
	data-wp-context='{ "post": { "id": <?php echo $post->ID; ?> } }'
>
	<div
		class="wpmovies-page-button-parent"
		data-wp-on--click="actions.toggleMovie"
	>
		<div
			class="wpmovies-page-button-child"
			data-wp-class--wpmovies-liked="state.isMovieIncluded"
		>
			<?php echo $play_icon; ?>
			<span>
				<?php _e( 'Like', 'wp-movies-demo' ); ?>
			</span>
		</div>
	</div>
</div>
