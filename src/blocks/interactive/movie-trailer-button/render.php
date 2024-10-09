<?php
/**
 * Server rendering for the movie trailer button block.
 *
 * @package wpmovies
 */

$wrapper_attributes = get_block_wrapper_attributes(
	array( 'class' => 'wpmovies-trailer-button' )
);

$post      = get_post();
$play_icon = file_get_contents( get_template_directory() . '/assets/play.svg' );
$videos    = get_post_meta( $post->ID, 'wpmovies_videos', true );
$trailers  = array_filter(
	json_decode( $videos, true ),
	function( $video ) {
		return 'Trailer' === $video['type'];
	}
);

if ( count( $trailers ) !== 0 ) {
	$trailer_url = reset( $trailers )['url'];
	$trailer_id  = substr( $trailer_url, strpos( $trailer_url, '?v=' ) + 3 );
	$context     = array( 'videoId' => $trailer_id );
	?>

	<div
		data-wp-interactive="wpmovies"
		<?php echo $wrapper_attributes; ?>
		<?php echo wp_interactivity_data_wp_context( $context ); ?>
	>
		<div
			class="wpmovies-page-button-parent"
			data-wp-on--click="actions.setVideo"
			aria-controls="wp-movies-video-player"
		>
			<div class="wpmovies-page-button-child">
				<?php echo $play_icon; ?>
				<span>Play trailer</span>
			</div>
		</div>
	</div>

<?php } ?>
