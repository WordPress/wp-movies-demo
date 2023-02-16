<?php
$post = get_post();
$wrapper_attributes = get_block_wrapper_attributes();
$videos = get_post_meta($post->ID, '_wpmovies_videos', true);
if (!function_exists('get_trailers')) {
   function get_trailers($video)
   {
      return $video["type"] == "Trailer";
   }
}
$trailers = array_filter(json_decode($videos, true), "get_trailers");
if (count($trailers) != 0) { ?>
   <div <?php echo $wrapper_attributes; ?>>
      <a target="_blank" href="<?php echo reset($trailers)["url"] ?>">Play Trailer</a>
   </div>

<?php
}
?>