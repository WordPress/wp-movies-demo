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
		class="wpmovies-page-button-parent"
		data-wp-on--click="actions.wpmovies.toggleMovie"
	>
		<div
			class="wpmovies-page-button-child"
			data-wp-class--wpmovies-liked="selectors.wpmovies.isMovieIncluded"
		>
			<?php echo $play_icon; ?>
			<span>
				<?php _e( 'Like' ); ?>
			</span>
		</div>
	</div>
</div>
