<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( count( wp_get_post_categories( $post->ID ) ) !== 0 ) {
		foreach ( wp_get_post_categories( $post->ID ) as $category_id ) {
	?>
			<a
				data-wp-link="{ \'prefetch\': true }"
				href="<?php echo get_category_link( $category_id ); ?>"
			>
				<?php echo get_cat_name( $category_id ); ?>
			</a>
	<?php }
	} ?>
</div>
