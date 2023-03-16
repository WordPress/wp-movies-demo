<?php
$post        = get_post();
$score       = get_post_meta( $post->ID, '_wpmovies_vote_average', true );
$score_color = '#5EFD26'; // Green
if ( $score < 7 && $score >= 3 ) {
	$score_color = '#fad900'; // Yellow
} elseif ( $score < 3 ) {
	$score_color = '#de1600'; // Red
};
$degrees_css = $score * 180 / 10;

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'wpmovies-score-wrap',
		'style' => 'background-color: ' . $score_color . '22',
	)
);
?>

<div <?php echo $wrapper_attributes; ?>>
   <div class="wpmovies-score-circle">
	  <div class="wpmovies-score-mask wpmovies-score-full" <?php echo 'style="transform: rotate(' . $degrees_css . 'deg)"'; ?>>
		 <div class="wpmovies-score-fill" <?php echo 'style="background-color: ' . $score_color . ';transform: rotate(' . $degrees_css . 'deg)"'; ?>></div>
	  </div>
	  <div class="wpmovies-score-mask wpmovies-score-half">
		 <div class="wpmovies-score-fill" <?php echo 'style="background-color: ' . $score_color . ';transform: rotate(' . $degrees_css . 'deg)"'; ?>></div>
	  </div>
	  <div class="wpmovies-score-inside-circle">
		 <span><?php echo round( $score * 10 ) . '%'; ?></span>
	  </div>
   </div>
</div>
