<?php
/**
 * Actor Birthday Block render.
 *
 * @package wpmovies
 */

$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$birthday           = get_post_meta( $post->ID, '_wpmovies_actors_birthday', true );
?>

<span <?php echo $wrapper_attributes; ?>>
	<?php echo gmdate( 'jS F Y', strtotime( $birthday ) ); ?>
</span>
