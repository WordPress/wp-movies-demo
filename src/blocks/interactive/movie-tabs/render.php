<?php
/**
 * Server-side rendering for the `wpmovies/movie-tabs` block.
 *
 * @package wpmovies
 */

$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'wpmovies-tabs' )
);

$images = json_decode( get_post_meta( $post->ID, '_wpmovies_images', true ), true );
$videos = json_decode( get_post_meta( $post->ID, '_wpmovies_videos', true ), true );
$context            = array( 'tab' => 'images' );

wp_interactivity_state(
	'wpmovies',
	array(
		'isImagesTab' => true,
		'isVideosTab' => false,
	),
);
?>

<div
	data-wp-interactive="wpmovies"
	<?php echo $wrapper_attributes; ?>
	<?php echo wp_interactivity_data_wp_context( $context ); ?>
>
	<ul role="tablist">
		<li class="wpmovies-tabs-title">
			<button
				id="wpmovies-images-tab"
				data-wp-on--click="actions.showImagesTab"
				data-wp-class--wpmovies-active-tab="state.isImagesTab"
				data-wp-bind--aria-selected="state.isImagesTab"
				role="tab"
				class="wpmovies-tab-button"
			>
			Images
			</button>
		</li>
		<li class="wpmovies-tabs-title">
			<button
				id="wpmovies-videos-tab"
				data-wp-on--click="actions.showVideosTab"
				data-wp-class--wpmovies-active-tab="state.isVideosTab"
				data-wp-bind--aria-selected="state.isVideosTab"
				role="tab"
				class="wpmovies-tab-button"
			>
			Videos
			</button>
		</li>
	</ul>

	<div 
		role="tabpanel" 
		data-wp-bind--hidden="state.isVideosTab" 
		data-wp-bind--aria-hidden="state.isVideosTab" 
		aria-labelledby="wpmovies-images-tab"
	>
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

	<div 
		role="tabpanel" 
		data-wp-bind--hidden="state.isImagesTab" 
		data-wp-bind--aria-hidden="state.isImagesTab" 
		aria-labelledby="wpmovies-videos-tab"
	>
		<div class="wpmovies-media-scroller wpmovies-videos-tab">
			<?php
			foreach ( $videos as $video ) {
				$video_id = substr( $video['url'], strpos( $video['url'], '?v=' ) + 3 );
				$context            = array( 'videoId' => $video_id );
				?>
				<div class="wpmovies-tabs-video-wrapper" <?php echo wp_interactivity_data_wp_context( $context ); ?>>
					<div data-wp-on--click="actions.setVideo" aria-controls="wp-movies-video-player">
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
