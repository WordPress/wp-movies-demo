<?php
$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'movie-search' )
);

wp_store(
	array(
		'state' => array(
			'wpmovies' => array(
				'searchValue' => get_search_query(),
			),
		),
	),
);
?>

<div <?php echo $wrapper_attributes; ?>>
	<input
		type="search"
		name="s"
		inputmode="search"
		placeholder="Search for a movie..."
		required=""
		autocomplete="off"
		data-wp-bind.value="state.wpmovies.searchValue"
		data-wp-on.input="actions.wpmovies.updateSearch"
	>
</div>
