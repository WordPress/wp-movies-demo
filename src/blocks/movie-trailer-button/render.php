<?php
$post               = get_post();
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'wpmovies-trailer-button',
	)
);
$videos             = get_post_meta( $post->ID, '_wpmovies_videos', true );
if ( ! function_exists( 'get_trailers' ) ) {
	function get_trailers( $video ) {
		return $video['type'] == 'Trailer';
	}
}
$trailers = array_filter( json_decode( $videos, true ), 'get_trailers' );

if ( count( $trailers ) != 0 ) {
	$trailer_url = reset( $trailers )['url'];
	$trailer_id  = substr( $trailer_url, strpos( $trailer_url, '?v=' ) + 3 );
	?>

   <div <?php echo $wrapper_attributes; ?> wp-context='{ "videoId": "<?php echo $trailer_id; ?>" }'>
	  <a wp-on:click="actions.wpmovies.setVideo">Play Trailer</a>
   </div>

	<?php
}
?>
