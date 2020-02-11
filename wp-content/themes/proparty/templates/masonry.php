<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_template_masonry_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_template_masonry_theme_setup', 1 );
	function axiom_template_masonry_theme_setup() {
		axiom_add_template(array(
			'layout' => 'masonry_2',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Masonry tile (different height) /2 columns/', 'axiom'),
			'thumb_title'  => __('Large image', 'axiom'),
			'w'		 => 750,
			'h_crop' => 422,
			'h'      => null
		));
		axiom_add_template(array(
			'layout' => 'masonry_3',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Masonry tile /3 columns/', 'axiom'),
			'thumb_title'  => __('Medium image', 'axiom'),
			'w'		 => 350,
			'h_crop' => 235,
			'h'      => null
		));
		axiom_add_template(array(
			'layout' => 'masonry_4',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Masonry tile /4 columns/', 'axiom'),
			'thumb_title'  => __('Small image', 'axiom'),
			'w'		 => 260,
			'h_crop' => 140,
			'h'      => null
		));
		axiom_add_template(array(
			'layout' => 'classic_2',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Classic tile (equal height) /2 columns/', 'axiom'),
			'thumb_title'  => __('Large image (crop)', 'axiom'),
			'w'		 => 750,
			'h'		 => 422
		));
		axiom_add_template(array(
			'layout' => 'classic_3',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Classic tile /3 columns/', 'axiom'),
			'thumb_title'  => __('Medium image (crop)', 'axiom'),
			'w'		 => 400,
			'h'		 => 225
		));
		axiom_add_template(array(
			'layout' => 'classic_4',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => __('Classic tile /4 columns/', 'axiom'),
			'thumb_title'  => __('Small image (crop)', 'axiom'),
			'w'		 => 260,
			'h'		 => 140
		));
		// Add template specific scripts
		add_action('axiom_action_blog_scripts', 'axiom_template_masonry_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('axiom_template_masonry_add_scripts')) {
	//add_action('axiom_action_blog_scripts', 'axiom_template_masonry_add_scripts');
	function axiom_template_masonry_add_scripts($style) {
		if (in_array(axiom_substr($style, 0, 8), array('classic_', 'masonry_'))) {
			axiom_enqueue_script( 'isotope', axiom_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
		}
	}
}

// Template output
if ( !function_exists( 'axiom_template_masonry_output' ) ) {
	function axiom_template_masonry_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(4, empty($parts[1]) ? $post_options['columns_count'] : (int) $parts[1]));
		$tag = axiom_sc_in_shortcode_blogger(true) ? 'div' : 'article';
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
				 <?php echo ' post_format_'.esc_attr($post_data['post_format']) 
					. ($post_options['number']%2==0 ? ' even' : ' odd') 
					. ($post_options['number']==0 ? ' first' : '') 
					. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
				?>">
				
				<?php if ($post_data['post_video'] || $post_data['post_audio'] || $post_data['post_thumb'] ||  $post_data['post_gallery']) { ?>
					<div class="post_featured">
						<?php require(axiom_get_file_dir('templates/parts/post-featured.php')); ?>
					</div>
				<?php } ?>

				<div class="post_content isotope_item_content">
					
					<?php
					if ($show_title) {
						if (!isset($post_options['links']) || $post_options['links']) {
							?>
							<h4 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo ($post_data['post_title']); ?></a></h4>
							<?php
						} else {
							?>
							<h4 class="post_title"><?php echo ($post_data['post_title']); ?></h4>
							<?php
						}
					}
					?>

					<div class="post_descr">
						<?php
						if ($post_data['post_protected']) {
							echo ($post_data['post_excerpt']); 
						} else {
							if ($post_data['post_excerpt']) {
								echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(axiom_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : axiom_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
							}

						}
						?>
					</div>
					<?php
						if (!$post_data['post_protected'] && $post_options['info']) {
							$info_parts = array('counters'=>false, 'terms'=>false);
							require(axiom_get_file_dir('templates/parts/post-info.php'));
						}

						if (empty($post_options['readmore'])) $post_options['readmore'] = __('MORE', 'axiom');
						if (!axiom_sc_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {
							echo do_shortcode('[trx_button style="filled" bg_style="menu" link="'.esc_url($post_data['post_link']).'"]'.($post_options['readmore']).'[/trx_button] [trx_button link="'.esc_url($post_data['post_link']).'"]'.esc_html($post_data['post_views']).'[/trx_button]');
						}
					?>

				</div>				<!-- /.post_content -->
			</<?php echo ($tag); ?>>	<!-- /.post_item -->
		</div>						<!-- /.isotope_item -->
		<?php
	}
}
?>