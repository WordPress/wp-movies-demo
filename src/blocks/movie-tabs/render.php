<?php
$post = get_post();
$wrapper_attributes = get_block_wrapper_attributes(
   [
      'class' => 'wpmovies-tabs',
      'wp-context' => '{ "isImagesTab": true, "isVideoTab": false }'
   ]
);
$images = get_post_meta($post->ID, '_wpmovies_images', true);
$videos = get_post_meta($post->ID, '_wpmovies_videos', true);
?>

<div <?php echo $wrapper_attributes; ?>>
   <ul>
      <li wp-on:click="actions.wpmovies.showImagesTab" wp-class:wpmovies-active-tab="context.isImagesTab" class="wpmovies-tabs-title">Images</li>
      <li wp-on:click="actions.wpmovies.showVideosTab" wp-class:wpmovies-active-tab="context.isVideosTab" class=" wpmovies-tabs-title">Videos</li>
   </ul>
   <wp-show when="context.isImagesTab">
      <div class="wpmovies-media-scroller wpmovies-images-tab">
         <?
         foreach (json_decode($images, true) as $image_id) {
            $image_url = wp_get_attachment_image_url($image_id, '');
         ?>
            <img src="<? echo $image_url ?>">
         <?
         }
         ?>
      </div>
   </wp-show>
   <wp-show when="context.isVideosTab">
      <div class="wpmovies-media-scroller wpmovies-videos-tab">
         <?
         foreach (json_decode($videos, true) as $video) {
            $video_id = substr($video["url"], strpos($video["url"], "?v=") + 3);
         ?>
            <div class="wpmovies-tabs-video-wrapper">
               <div><span wp-on:click="actions.wpmovies.setVideo" data-wpmovies-video-id=<? echo $video_id ?>>Play</span></div>
               <img src="<? echo 'https://img.youtube.com/vi/' . $video_id . '/0.jpg' ?>">
            </div>
         <?
         }
         ?>
      </div>
   </wp-show>
</div>