<?php
$post                 = get_post();
$background_image_id  = get_post_meta( $post->ID, '_wpmovies_backdrop_img_id', true );
$background_image_url = wp_get_attachment_image_url( $background_image_id, '' );
$wrapper_attributes   = get_block_wrapper_attributes(
	array(
		'class' => 'wpmovies-movie-background-wrapper',
		'style' => 'background-image: url(' . $background_image_url . ');',
	)
);
$inner_blocks_html    = '';
foreach ( $block->inner_blocks as $inner_block ) {
	$inner_blocks_html .= $inner_block->render();
}
?>

<div <?php echo $wrapper_attributes; ?>>
   <div class="wpmovies-movie-background-inner-wrapper">
	  <?php echo $inner_blocks_html; ?>
   </div>
</div>
