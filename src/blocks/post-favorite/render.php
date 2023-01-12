<?php
$post = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
?>

<wp-context data='{"post": {"id": <?= $post->ID ?>}}'>
    <img
        <?= $wrapper_attributes ?>
        wp-on:click="actions.favorites.togglePost"
        class="emoji"
        alt=":heart:"
        src="https://s.w.org/images/core/emoji/14.0.0/svg/1f90d.svg"
        wp-bind:src="selectors.favorites.isPostIncluded"
    />
</wp-context>