<?php
/**
 * Server rendering for the movie search block.
 *
 * @package wpmovies
 */

$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'movie-search' )
);

wp_interactivity_state(
	'wpmovies',
	array(
		'searchValue' => get_search_query(),
	),
);
?>

<div data-wp-interactive="wpmovies" <?php echo $wrapper_attributes; ?> >
	<form>
		<label class="search-label" for="movie-search">Search for a movie</label>
		<input
			id="movie-search"
			type="search"
			name="s"
			role="search"
			inputmode="search"
			placeholder=<?php _e( 'Search for a movie', 'wp-movies-demo' ); ?>
			required=""
			autocomplete="off"
			data-wp-bind--value="state.searchValue"
			data-wp-on--input="actions.updateSearch"
			>
	</form>
</div>
