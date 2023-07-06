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
	<form>
		<label class="search-label" for="movie-search">Search for a movie</label>
		<input
			id="movie-search"
			type="search"
			name="s"
			role="search"
			inputmode="search"
			placeholder="Search for a movie..."
			required=""
			autocomplete="off"
			data-wp-bind--value="state.wpmovies.searchValue"
			data-wp-on--input="actions.wpmovies.updateSearch"
			>
	</form>
</div>
