<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'wpmovies-tabs' )
);

$images = get_post_meta( $post->ID, '_wpmovies_images', true );
$videos = get_post_meta( $post->ID, '_wpmovies_videos', true );

wp_store(
	array(
		'selectors' => array(
			'wpmovies' => array(
				'isImagesTab' => true,
				'isVideosTab' => false,
			),
		),
	),
);
?>

<div
	<?php echo $wrapper_attributes; ?>
	data-wp-context='{ "tab": "images" }'
>
	<ul>
		<li
			data-wp-on.click="actions.wpmovies.showImagesTab"
			data-wp-class.wpmovies-active-tab="selectors.wpmovies.isImagesTab"
			class="wpmovies-tabs-title"
		>
			Images
		</li>
		<li
			data-wp-on.click="actions.wpmovies.showVideosTab"
			data-wp-class.wpmovies-active-tab="selectors.wpmovies.isVideosTab"
			class=" wpmovies-tabs-title"
		>
			Videos
		</li>
	</ul>

	<div data-wp-show="selectors.wpmovies.isImagesTab">
		<div class="wpmovies-media-scroller wpmovies-images-tab">
			<?php
			foreach ( $images as $image_id ) {
				$image_url = wp_get_attachment_image_url( $image_id, '' );
				?>
				<img src="<?php echo $image_url; ?>">
				<?php
			}
			?>
		</div>
	</div>

	<div data-wp-show="selectors.wpmovies.isVideosTab">
		<div class="wpmovies-media-scroller wpmovies-videos-tab">
			<?php
			foreach ( $videos as $video ) {
				$video_id = substr( $video['url'], strpos( $video['url'], '?v=' ) + 3 );
				?>
				<div class="wpmovies-tabs-video-wrapper" data-wp-context='{ "videoId": "<?php echo $video_id; ?>" }'>
					<div data-wp-on.click="actions.wpmovies.setVideo">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffffff" class="play-icon">
							<path d="M3 22v-20l18 10-18 10z" />
						</svg>
					</div>
					<img src="<?php echo 'https://img.youtube.com/vi/' . $video_id . '/0.jpg'; ?>">
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
