<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'wpmovies-page-background',
	)
);
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php
	if ( get_post_type() === 'movies' ) {
		$background_image_id  = get_post_meta( $post->ID, '_wpmovies_backdrop_img_id', true );
		$background_image_url = wp_get_attachment_image_url( $background_image_id, '' );
		?>
		<div class="wpmovies-movie-background">
			<img src=<?php echo $background_image_url; ?> />
			<div class="wpmovies-movie-background-shadow"></div>
		</div>

	<?php } elseif ( get_post_type() === 'actors' ) { ?>
		<div class="wpmovies-actor-background-shadow"></div>
	<?php } ?>
</div>
