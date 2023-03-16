<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$genres_html        = '';
if ( count( wp_get_post_categories( $post->ID ) ) !== 0 ) {
	foreach ( wp_get_post_categories( $post->ID ) as $category_id ) {
		$genres_html .= '<a wp-link="{ \'prefetch\': true }" href="' . get_category_link( $category_id ) . '">' . get_cat_name( $category_id ) . '</a>';
	}
}

?>

<div <?php echo $wrapper_attributes; ?>>
	<?php echo $genres_html; ?>
</div>
