<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$language           = get_post_meta( $post->ID, '_wpmovies_language', true );
$budget             = intval( get_post_meta( $post->ID, '_wpmovies_budget', true ) );
if ( $budget == 0 ) {
	$budget = '-';
} else {
	$budget = '$' . strval( number_format( $budget ) );
}
$revenue = intval( get_post_meta( $post->ID, '_wpmovies_revenue', true ) );
if ( $revenue == 0 ) {
	$revenue = '-';
} else {
	$revenue = '$' . strval( number_format( $revenue ) );
}
?>

<div <?php echo $wrapper_attributes; ?>>
	<ul class="wpmovies-data-list">
		<li class="wpmovies-data-list-item">
			<div class="wpmovies-data-list-key">Language</div>
			<div class="wpmovies-data-list-value"><?php echo ucfirst( $language ); ?></div>
		</li>
		<li class="wpmovies-data-list-item">
			<div class="wpmovies-data-list-key">Budget</div>
			<div class="wpmovies-data-list-value"><?php echo $budget; ?></div>
		</li>
		<li class="wpmovies-data-list-item">
			<div class="wpmovies-data-list-key">Revenue</div>
			<div class="wpmovies-data-list-value"><?php echo $revenue; ?></div>
		</li>
	</ul>
</div>
