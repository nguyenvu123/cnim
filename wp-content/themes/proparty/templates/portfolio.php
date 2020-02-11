<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_template_portfolio_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_template_portfolio_theme_setup', 1 );
	function axiom_template_portfolio_theme_setup() {
		axiom_add_template(array(
			'layout' => 'portfolio_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Portfolio tile (with hovers, different height) /2 columns/', 'axiom'),
			'thumb_title'  => __('Large image', 'axiom'),
			'w'		 => 750,
			'h_crop' => 422,
			'h'		 => null
		));
		axiom_add_template(array(
			'layout' => 'portfolio_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Portfolio tile /3 columns/', 'axiom'),
			'thumb_title'  => __('Medium image', 'axiom'),
			'w'		 => 350,
			'h_crop' => 235,
			'h'		 => null
		));
		axiom_add_template(array(
			'layout' => 'portfolio_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Portfolio tile /4 columns/', 'axiom'),
			'thumb_title'  => __('Small image', 'axiom'),
			'w'		 => 260,
			'h_crop' => 140,
			'h'		 => null
		));
		axiom_add_template(array(
			'layout' => 'grid_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Grid tile (with hovers, equal height) /2 columns/', 'axiom'),
			'thumb_title'  => __('Large image (crop)', 'axiom'),
			'w'		 => 750,
			'h' 	 => 422
		));
		axiom_add_template(array(
			'layout' => 'grid_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Grid tile /3 columns/', 'axiom'),
			'thumb_title'  => __('Medium image (crop)', 'axiom'),
			'w'		 => 350,
			'h'		 => 235
		));
		axiom_add_template(array(
			'layout' => 'grid_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Grid tile /4 columns/', 'axiom'),
			'thumb_title'  => __('Small image (crop)', 'axiom'),
			'w'		 => 260,
			'h'		 => 140
		));
		axiom_add_template(array(
			'layout' => 'square_2',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Square tile (with hovers, width=height) /2 columns/', 'axiom'),
			'thumb_title'  => __('Large square image (crop)', 'axiom'),
			'w'		 => 750,
			'h' 	 => 750
		));
		axiom_add_template(array(
			'layout' => 'square_3',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Square tile /3 columns/', 'axiom'),
			'thumb_title'  => __('Medium square image (crop)', 'axiom'),
			'w'		 => 400,
			'h'		 => 400
		));
		axiom_add_template(array(
			'layout' => 'square_4',
			'template' => 'portfolio',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Square tile /4 columns/', 'axiom'),
			'thumb_title'  => __('Small square image (crop)', 'axiom'),
			'w'		 => 250,
			'h'		 => 250
		));
		// Add template specific scripts
		add_action('axiom_action_blog_scripts', 'axiom_template_portfolio_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('axiom_template_portfolio_add_scripts')) {
	//add_action('axiom_action_blog_scripts', 'axiom_template_portfolio_add_scripts');
	function axiom_template_portfolio_add_scripts($style) {
	if (axiom_substr($style, 0, 10) == 'portfolio_' || axiom_substr($style, 0, 5) == 'grid_' || axiom_substr($style, 0, 7) == 'square_') {
			axiom_enqueue_script( 'isotope', axiom_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
			axiom_enqueue_script( 'hoverdir', axiom_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
			axiom_enqueue_style( 'axiom-portfolio-style', axiom_get_file_url('css/core.portfolio.css'), array(), null );
		}
	}
}

// Template output
if ( !function_exists( 'axiom_template_portfolio_output' ) ) {
	function axiom_template_portfolio_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(4, empty($parts[1]) ? 1 : (int) $parts[1]));
		$tag = axiom_sc_in_shortcode_blogger(true) ? 'div' : 'article';
		if ($post_options['hover']=='square effect4') $post_options['hover']='square effect5';
		$link_start = !isset($post_options['links']) || $post_options['links'] ? '<a href="'.esc_url($post_data['post_link']).'">' : '';
		$link_end = !isset($post_options['links']) || $post_options['links'] ? '</a>' : '';

			?>
			<div class="isotope_item isotope_item_<?php echo esc_attr($style); ?> isotope_item_<?php echo esc_attr($post_options['layout']); ?> isotope_column_<?php echo esc_attr($columns); ?>
						<?php
						if ($post_options['filters'] != '') {
							if ($post_options['filters']=='categories' && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids))
								echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids);
							else if ($post_options['filters']=='tags' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids))
								echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids);
						}
						?>">
				<<?php echo ($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['layout']); ?>
					<?php echo 'post_format_'.esc_attr($post_data['post_format']) 
						. ($post_options['number']%2==0 ? ' even' : ' odd') 
						. ($post_options['number']==0 ? ' first' : '') 
						. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
					?>">
	
					<div class="post_content isotope_item_content ih-item colored<?php
									echo ($post_options['hover'] ? ' '.esc_attr($post_options['hover']) : '')
										.($post_options['hover_dir'] ? ' '.esc_attr($post_options['hover_dir']) : ''); ?>">
						<?php
						if ($post_options['hover'] == 'circle effect1') {
							?><div class="spinner"></div><?php
						}
						if ($post_options['hover'] == 'square effect4') {
							?><div class="mask1"></div><div class="mask2"></div><?php
						}
						if ($post_options['hover'] == 'circle effect8') {
							?><div class="img-container"><?php
						}
						?>
						<div class="post_featured img">
							<?php 
							/*
							if ($post_data['post_video'] || $post_data['post_audio'] || $post_data['post_thumb'] ||  $post_data['post_gallery']) {
								require(axiom_get_file_dir('templates/parts/post-featured.php'));
							}
							*/
							echo ($link_start) . ($post_data['post_thumb']) . ($link_end);
							?>
						</div>
						<?php
						if ($post_options['hover'] == 'circle effect8') {
							?>
							</div>	<!-- .img-container -->
							<div class="info-container">
							<?php
						}
						?>
	
						<div class="post_info_wrap info"><div class="info-back">
	
							<?php
							if ($show_title) {
								?><h4 class="post_title"><?php echo ($link_start) . ($post_data['post_title']) . ($link_end); ?></h4><?php
							}
							
							/*
							if (!$post_data['post_protected'] && $post_options['info']) {
								$info_parts = array('counters'=>false, 'terms'=>false);
								require(axiom_get_file_dir('templates/parts/post-info.php'));
							}
							*/
							?>
	
							<div class="post_descr">
							<?php
								if ($post_data['post_protected']) {
									echo ($link_start) . ($post_data['post_excerpt']) . ($link_end);
								} else {
									if ($post_data['post_excerpt']) {
										echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) 
											? ( ($link_start) . ($post_data['post_excerpt']) . ($link_end) )
											: '<p>' . ($link_start) . trim(axiom_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : axiom_get_custom_option('post_excerpt_maxlength_masonry'))) . ($link_end) . '</p>';
									}
									echo ($link_start);
									?>
									<span class="hover_icon icon-plus-2"></span>
									<?php
									echo ($link_end);
								}
							?>
							</div>
						</div></div>	<!-- /.info-back /.info -->
						<?php if ($post_options['hover'] == 'circle effect8') { ?>
						</div>			<!-- /.info-container -->
						<?php } ?>
					</div>				<!-- /.post_content -->
				</<?php echo ($tag); ?>>	<!-- /.post_item -->
			</div>						<!-- /.isotope_item -->
			<?php
	}
}
?>