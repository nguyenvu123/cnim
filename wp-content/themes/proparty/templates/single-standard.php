<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_template_single_standard_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_template_single_standard_theme_setup', 1 );
	function axiom_template_single_standard_theme_setup() {
		axiom_add_template(array(
			'layout' => 'single-standard',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => __('Single standard', 'axiom'),
			'thumb_title'  => __('Fullwidth image', 'axiom'),
			'w'		 => 1150,
			'h'		 => 647
		));
	}
}

// Template output
if ( !function_exists( 'axiom_template_single_standard_output' ) ) {
	function axiom_template_single_standard_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && axiom_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = axiom_get_custom_option('show_post_title')=='yes' && (axiom_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));
		$title_tag = axiom_get_custom_option('show_page_top')=='yes' && axiom_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		axiom_open_wrapper('<article   class="'
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		$post_icon = axiom_get_custom_option('show_post_icon')=='yes' ? '<span class="post_icon '. esc_attr($post_data['post_icon']) .'"></span>':'';

		if ($show_title && $post_options['location'] == 'center' && (axiom_get_custom_option('show_page_top')=='no' || axiom_get_custom_option('show_page_title')=='no')) {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo ($avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'name'); ?>" class="post_title entry-title"><?php echo balanceTags($post_icon)?><?php echo ($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
		<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(axiom_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])	// && $post_data['post_format']!='gallery' && $post_data['post_format']!='image')
		)) {
			?>
			<section class="post_featured" >
			<?php
			if (!empty($post_options['dedicated'])) {
				echo ($post_options['dedicated']);
			} else {
				axiom_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php echo ($post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}
			
		
		if ($show_title && $post_options['location'] != 'center' && (axiom_get_custom_option('show_page_top')=='no' || axiom_get_custom_option('show_page_title')=='no')) {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo ($avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'name'); ?>" class="post_title entry-title"><?php echo balanceTags($post_icon)?><?php echo ($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}

		if (!$post_data['post_protected'] && axiom_get_custom_option('show_post_info')=='yes') {
			$info_parts = array('snippets'=>true);
			require(axiom_get_file_dir('templates/parts/post-info.php'));
		}
		
		require(axiom_get_file_dir('templates/parts/reviews-block.php'));
			
		axiom_open_wrapper('<section class="post_content" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'" style="'.(is_singular('post')?esc_attr('margin-top:50px;'): '').'" >');
			
		// Post content
		if ($post_data['post_protected']) { 
			echo ($post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
			global $AXIOM_GLOBALS;
			if (axiom_strpos($post_data['post_content'], axiom_sc_reviews_placeholder())===false) $post_data['post_content'] = do_shortcode('[trx_reviews]') . ($post_data['post_content']);
			echo trim(axiom_sc_gap_wrapper(axiom_sc_reviews_wrapper($post_data['post_content'])));
			require(axiom_get_file_dir('templates/parts/single-pagination.php'));
			if ( axiom_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info post_info_bottom">
					<span class="post_info_item post_info_tags"><?php _e('Tags:', 'axiom'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
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
		}

		$sidebar_present = !axiom_sc_param_is_off(axiom_get_custom_option('show_sidebar_main'));
		if (!$sidebar_present) axiom_close_wrapper();	// .post_item
		require(axiom_get_file_dir('templates/parts/related-posts.php'));
		if ($sidebar_present) axiom_close_wrapper();		// .post_item

		if (!$post_data['post_protected']) {
			require(axiom_get_file_dir('templates/parts/comments.php'));
		}

		require(axiom_get_file_dir('templates/parts/views-counter.php'));
	}
}
?>