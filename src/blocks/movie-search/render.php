<?php

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'movie-search',
	)
);
?>

<div <?php echo $wrapper_attributes; ?> >
	<input
	  type="search" 
	  name="s" 
		inputmode="search"
	  placeholder="Search for a movie..." 
	  required=""
		wp-bind:value="state.search.value"
		wp-on:input="actions.search.update"
		wp-on:focusout="actions.search.focusout"
	>
	<!-- <button 
	  type="submit" 
	  aria-label="Search"
		class="search-button"
	>
	  <svg class="search-icon" viewBox="0 0 24 24" width="24" height="24">
		<path d="M13.5 6C10.5 6 8 8.5 8 11.5c0 1.1.3 2.1.9 3l-3.4 3 1 1.1 3.4-2.9c1 .9 2.2 1.4 3.6 1.4 3 0 5.5-2.5 5.5-5.5C19 8.5 16.5 6 13.5 6zm0 9.5c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4z"></path>
	  </svg>
	</button> -->
</div>
