<?php

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'movie-search',
	)
);

store(
	array(
		'state' => array(
			'wpmovies' => array(
				'searchValue' => '',
			),
		),
	)
)
?>

<div <?php echo $wrapper_attributes; ?>>
	<input type="search" name="s" inputmode="search" placeholder="Search for a movie..." required="" wp-bind:value="state.wpmovies.searchValue" wp-on:input="actions.wpmovies.updateSearch">
</div>
