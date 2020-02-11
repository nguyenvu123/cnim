<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_template_single_portfolio_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_template_single_portfolio_theme_setup', 1 );
	function axiom_template_single_portfolio_theme_setup() {
		axiom_add_template(array(
			'layout' => 'single-portfolio',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => __('Portfolio item', 'axiom'),
			'thumb_title'  => __('Fullsize image', 'axiom'),
			'w'		 => 1150,
			'h'		 => null,
			'h_crop' => 455
		));
	}
}

// Template output
if ( !function_exists( 'axiom_template_single_portfolio_output' ) ) {
	function axiom_template_single_portfolio_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && axiom_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = axiom_get_custom_option('show_post_title')=='yes' && (axiom_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));

		axiom_open_wrapper('<article class="'
				. join(' ', get_post_class('itemscope'
					. ' post_item'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		require(axiom_get_file_dir('templates/parts/prev-next-posts.php'));

		if ($show_title) {
			?>
			<h1 itemprop="<?php echo ($avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'name'); ?>" class="post_title entry-title"><?php echo ($post_data['post_title']); ?></h1>
			<?php
		}

		if (!$post_data['post_protected'] && axiom_get_custom_option('show_post_info')=='yes') {
			require(axiom_get_file_dir('templates/parts/post-info.php'));
		}

		require(axiom_get_file_dir('templates/parts/reviews-block.php'));

		axiom_open_wrapper('<section class="post_content" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'">');
			
		// Post content
		if ($post_data['post_protected']) { 
			echo ($post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
			if (axiom_strpos($post_data['post_content'], axiom_sc_reviews_placeholder())===false) $post_data['post_content'] = do_shortcode('[trx_reviews]') . ($post_data['post_content']);
			echo trim(axiom_sc_gap_wrapper(axiom_sc_reviews_wrapper($post_data['post_content'])));
			require(axiom_get_file_dir('templates/parts/single-pagination.php'));
			if ( axiom_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info">
					<span class="post_info_item post_info_tags"><?php _e('in', 'axiom'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
				</div>
				<?php
			} 
		}

		axiom_close_wrapper();	// .post_content

		if (!$post_data['post_protected']) {
			if ($post_data['post_edit_enable']) {
				require(axiom_get_file_dir('templates/parts/editor-area.php'));
			}
			require(axiom_get_file_dir('templates/parts/author-info.php'));
			require(axiom_get_file_dir('templates/parts/share.php'));
			require(axiom_get_file_dir('templates/parts/related-posts.php'));
			require(axiom_get_file_dir('templates/parts/comments.php'));
		}
	
		axiom_close_wrapper();	// .post_item

		require(axiom_get_file_dir('templates/parts/views-counter.php'));
	}
}
?>