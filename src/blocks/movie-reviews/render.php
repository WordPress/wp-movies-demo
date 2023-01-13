<?php
$post = get_post();
$wrapper_attributes = get_block_wrapper_attributes(
  [
    'class' => 'empty-stars',
  ]
);
$reviews = get_post_meta($post->ID, '_wpmovies_vote_average', true);
$reviews_count = '' !== get_post_meta($post->ID, '_wpmovies_vote_count', true) ? get_post_meta($post->ID, '_wpmovies_vote_count', true) : '0';
?>

<div <?php echo $wrapper_attributes; ?>>
   <div class="filled-stars" style="width: <?= $reviews * 10 ?>%"></div>
</div>
<div><?= sprintf(__('%s Reviews', 'wp-movies'), $reviews_count )  ?></div>