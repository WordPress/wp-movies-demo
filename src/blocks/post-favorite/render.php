<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
?>

<wp-context data='{"post": {"id": <?php echo $post->ID; ?>}}'>
	<img
		<?php echo $wrapper_attributes; ?>
		class="emoji"
		alt=":heart:"
		src="https://s.w.org/images/core/emoji/14.0.0/svg/1f90d.svg"
		wp-on:click="actions.favorites.togglePost"
		wp-bind:src="selectors.favorites.isPostIncluded"
	/>
</wp-context>
