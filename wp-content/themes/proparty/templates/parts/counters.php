<?php
$show_all_counters = !isset($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';
 
if ($show_all_counters || axiom_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php echo ($counters_tag); ?> class="post_counters_item post_counters_views icon-eye-1" title="<?php echo sprintf(__('Views - %s', 'axiom'), $post_data['post_views']); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo ($post_data['post_views']); ?></<?php echo ($counters_tag); ?>>
	<?php
}

if ($show_all_counters || axiom_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments icon-comment-3" title="<?php echo sprintf(__('Comments - %s', 'axiom'), $post_data['post_comments']); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php echo ($post_data['post_comments']); ?></span></a>
	<?php 
}
 
$rating = $post_data['post_reviews_'.(axiom_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || axiom_strpos($post_options['counters'], 'rating')!==false)) {
	?>
	<<?php echo ($counters_tag); ?> class="post_counters_item post_counters_rating icon-star-1" title="<?php echo sprintf(__('Rating - %s', 'axiom'), $rating); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo ($rating); ?></span></<?php echo ($counters_tag); ?>>
	<?php
}

if ($show_all_counters || axiom_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	axiom_enqueue_messages();
	$likes = isset($_COOKIE['axiom_likes']) ? $_COOKIE['axiom_likes'] : '';
	$allow = axiom_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart-1 <?php echo ($allow ? 'enabled' : 'disabled'); ?>" title="<?php echo esc_attr($allow ? __('Like', 'axiom') : __('Dislike', 'axiom')); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php _e('Like', 'axiom'); ?>"
		data-title-dislike="<?php _e('Dislike', 'axiom'); ?>"><span class="post_counters_number"><?php echo ($post_data['post_likes']); ?></span></a>
	<?php
}

if (is_single() && axiom_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(axiom_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(axiom_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>