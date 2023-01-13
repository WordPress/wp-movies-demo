<?php
$post = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$release_date = get_post_meta($post->ID, '_wpmovies_release_date', true);
?>

<div <?php echo $wrapper_attributes; ?>>
   Released: <?= $release_date ?>
</div>