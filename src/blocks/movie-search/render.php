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
	>
</div>
