<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$release_date       = get_post_meta( $post->ID, '_wpmovies_release_date', true );
?>

<span <?php echo $wrapper_attributes; ?>>
	<?php echo date( 'jS F Y', strtotime( $release_date ) ); ?>
</span>
