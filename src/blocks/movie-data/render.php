<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$release_date       = get_post_meta( $post->ID, '_wpmovies_release_date', true );
$status             = get_post_meta( $post->ID, '_wpmovies_status', true );
$language           = get_post_meta( $post->ID, '_wpmovies_language', true );
$runtime_minutes    = get_post_meta( $post->ID, '_wpmovies_runtime', true );
$runtime            = $hours = intdiv( $runtime_minutes, 60 ) . 'h ' . ( $runtime_minutes % 60 ) . 'm';
$homepage_url       = get_post_meta( $post->ID, '_wpmovies_homepage', true );
if ( $homepage_url == '' ) {
	$homepage_html = '-';
} else {
	$homepage_html = '
   <a target="_blank" href="' . $homepage_url . '">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
         <path fill="#ffffff" d="M6.188 8.719c.439-.439.926-.801 1.444-1.087 2.887-1.591 6.589-.745 8.445 2.069l-2.246 2.245c-.644-1.469-2.243-2.305-3.834-1.949-.599.134-1.168.433-1.633.898l-4.304 4.306c-1.307 1.307-1.307 3.433 0 4.74 1.307 1.307 3.433 1.307 4.74 0l1.327-1.327c1.207.479 2.501.67 3.779.575l-2.929 2.929c-2.511 2.511-6.582 2.511-9.093 0s-2.511-6.582 0-9.093l4.304-4.306zm6.836-6.836l-2.929 2.929c1.277-.096 2.572.096 3.779.574l1.326-1.326c1.307-1.307 3.433-1.307 4.74 0 1.307 1.307 1.307 3.433 0 4.74l-4.305 4.305c-1.311 1.311-3.44 1.3-4.74 0-.303-.303-.564-.68-.727-1.051l-2.246 2.245c.236.358.481.667.796.982.812.812 1.846 1.417 3.036 1.704 1.542.371 3.194.166 4.613-.617.518-.286 1.005-.648 1.444-1.087l4.304-4.305c2.512-2.511 2.512-6.582.001-9.093-2.511-2.51-6.581-2.51-9.092 0z" />
      </svg>
   </a>
';
}
$budget = intval( get_post_meta( $post->ID, '_wpmovies_budget', true ) );
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

$categories_html = '';
if ( count( wp_get_post_categories( $post->ID ) ) == 0 ) {
	$categories_html = '-';
} else {
	foreach ( wp_get_post_categories( $post->ID ) as $category_id ) {
		$categories_html .= '<a wp-link="{ \'prefetch\': true }" href="' . get_category_link( $category_id ) . '" class="wpmovies-category-link">' . get_cat_name( $category_id ) . '</a>';
	}
}

if ( ! function_exists( 'wpmovies_get_item_html' ) ) {
	function wpmovies_get_item_html( $metafield, $key ) {
		if ( $metafield == '' ) {
			return;
		}
		return '
      <li class="wpmovies-data-list-item">
         <div class="wpmovies-data-list-key">' . $key . '</div>
         <div class="wpmovies-data-list-value">' . $metafield . '</div>
      </li>
   ';
	}
}
?>

<div <?php echo $wrapper_attributes; ?>>
   <ul class="wpmovies-data-list">
	  <?php echo wpmovies_get_item_html( $release_date, 'Released' ); ?>
	  <?php echo wpmovies_get_item_html( $status, 'Status' ); ?>
	  <?php echo wpmovies_get_item_html( $runtime, 'Runtime' ); ?>
	  <li class="wpmovies-data-list-item wpmovies-categories">
		 <div class="wpmovies-data-list-key">Categories</div>
		 <div class="wpmovies-data-list-value"><?php echo $categories_html; ?></div>
	  </li>
	  <?php echo wpmovies_get_item_html( $language, 'Language' ); ?>
	  <?php echo wpmovies_get_item_html( $budget, 'Budget' ); ?>
	  <?php echo wpmovies_get_item_html( $revenue, 'Revenue' ); ?>
	  <?php echo wpmovies_get_item_html( $homepage_html, 'Homepage' ); ?>
   </ul>
</div>
