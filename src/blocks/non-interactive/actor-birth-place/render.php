<?php
/**
 * Actor Birth Place Block render.
 *
 * @package wpmovies
 */

$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$birth_place        = get_post_meta( $post->ID, 'wpmovies_actors_place_of_birth', true );
?>

<span <?php echo $wrapper_attributes; ?>>
	<?php echo $birth_place; ?>
</span>
