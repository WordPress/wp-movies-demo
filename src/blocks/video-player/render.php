<?php
$wrapper_attributes = get_block_wrapper_attributes(
   [
      'class' => 'wpmovies-video-player'
   ]
);
?>

<wp-show when="selectors.wpmovies.isPlaying" <?php echo $wrapper_attributes; ?>>
   <div class="wpmovies-video-wrapper">
      <span wp-on:click="actions.wpmovies.closeVideo">X</span>
      <iframe width="420" height="315" allow="autoplay" allowfullscreen wp-bind:src="state.wpmovies.currentVideo">
      </iframe>
   </div>
</wp-show>