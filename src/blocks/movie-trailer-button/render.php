<?php
$post = get_post();
$wrapper_attributes = get_block_wrapper_attributes(
   [
      'class' => 'wpmovies-trailer-button'
   ]
);
$videos = get_post_meta($post->ID, '_wpmovies_videos', true);
if (!function_exists('get_trailers')) {
   function get_trailers($video)
   {
      return $video["type"] == "Trailer";
   }
}
$trailers = array_filter(json_decode($videos, true), "get_trailers");
$trailer_url = reset($trailers)["url"];
$trailer_id = substr($trailer_url, strpos($trailer_url, "?v=") + 3);
if (count($trailers) != 0) { ?>

   <div <?php echo $wrapper_attributes; ?>>
      <a wp-on:click="actions.wpmovies.setVideo" data-wpmovies-video-id=<? echo $trailer_id ?>>Play Trailer</a>
   </div>

<?php
}
?>