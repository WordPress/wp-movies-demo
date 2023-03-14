<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();

store(
	array(
		'selectors' => array(
			'wpmovies' => array(
				'isMovieIncluded' => 'https://s.w.org/images/core/emoji/14.0.0/svg/1f90d.svg',
			),
		),
	)
)
?>

<div wp-context='{"post": {"id": <?php echo $post->ID; ?>}}'>
	<img <?php echo $wrapper_attributes; ?> class="emoji" alt=":heart:" wp-on:click="actions.wpmovies.toggleMovie" wp-bind:src="selectors.wpmovies.isMovieIncluded" />
</div>
