<?php
/**
 * Movie Runtime Block render.
 *
 * @package wpmovies
 */

$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$runtime_minutes    = get_post_meta( $post->ID, '_wpmovies_runtime', true );
$runtime            = intdiv( $runtime_minutes, 60 ) . 'h ' . ( $runtime_minutes % 60 ) . 'm';
?>

<span <?php echo $wrapper_attributes; ?>>
	<?php echo $runtime; ?>
</span>
