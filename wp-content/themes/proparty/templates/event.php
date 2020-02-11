<?php
/*
Template Name: Events steampage
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_template_event_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_template_event_setup', 1 );
	function axiom_template_event_setup() {
		axiom_add_template(array(
			'layout' => 'event_date_horizontal',
			'template' => 'event',
			'mode'   => 'event',
			'title'  => __('Events - date, horizontal', 'axiom')
		));
		axiom_add_template(array(
			'layout' => 'event_classic',
			'template' => 'event',
			'mode'   => 'event',
			'need_isotope' => true,
			'title'  => __('Events Classic tile /3 columns/', 'axiom'),
			'thumb_title'  => __('Medium image (crop)', 'axiom'),
			'w'		 => 400,
			'h'		 => 270
		));
		axiom_add_template(array(
			'layout' => 'event_classic2',
			'template' => 'event',
			'mode'   => 'event',
			'need_isotope' => true,
			'title'  => __('Events Classic 2 tile /3 columns/', 'axiom'),
			'thumb_title'  => __('Medium 2 image (crop)', 'axiom'),
			'w'		 => 345,
			'h'		 => 275
		));
		// Add template specific scripts
		add_action('axiom_action_blog_scripts', 'axiom_template_event_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('axiom_template_event_add_scripts')) {
	//add_action('axiom_action_blog_scripts', 'axiom_template_event_add_scripts');
	function axiom_template_event_add_scripts($style) {
		axiom_enqueue_script( 'isotope', axiom_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
	}
}

if ( !function_exists( 'axiom_template_event_output' ) ) {
	function axiom_template_event_output($post_options, $post_data) {

		$show_title = true;
		$columns = (int)$post_options['columns_count'];
		$style = $post_options['template_style'];
		$cust = get_post_custom($post_data['post_id']);
		$event_date_temp = strtotime($cust['_EventStartDate'][0]);
		$event_date = ($style == 'classic' ? date_i18n('F d', $event_date_temp) : date_i18n('d M Y', $event_date_temp) );

		?>
		<div class="isotope_item isotope_item_<?php echo esc_attr($style); ?> isotope_item_<?php echo esc_attr($post_options['layout']); ?> isotope_column_<?php echo esc_attr($columns); ?>">
			<div class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['layout']); ?>
		<?php echo ' post_format_'.esc_attr($post_data['post_format'])
		           . ($post_options['number']%2==0 ? ' even' : ' odd')
		           . ($post_options['number']==0 ? ' first' : '')
		           . ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
		?>">
				<?php if (($post_data['post_video'] || $post_data['post_audio'] || $post_data['post_thumb'] ||  $post_data['post_gallery']) && (($style == 'classic') || ($style == 'classic2'))) { ?>
					<div class="post_featured">
						<?php require(axiom_get_file_dir('templates/parts/post-featured.php')); ?>
					</div>
				<?php } ?>

				<div class="post_content isotope_item_content">
					<?php if ($style == 'list') { ?>
						<div class="startDate">
							<div><?php echo esc_html($event_date); ?></div>
						</div>
						<?php if ($show_title) { ?>
							<h5><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_title']); ?></a></h5>
						<?php } ?>
					<?php } ?>

					<?php if ($style == 'classic' || $style == 'classic2') { ?>
						<?php if ($show_title) { ?>
							<h5>
								<?php if($style == 'classic2')  echo "<a href='".esc_url($post_data['post_link'])."'>"; ?>
								<?php echo esc_html($post_data['post_title']); ?>
								<?php if($style == 'classic2')  echo "</a>"; ?>
							</h5>
						<?php } ?>
					<div class="startDate">
						<?php echo esc_html($event_date); ?>
					</div>
					<?php } ?>
					<?php if ($style == 'classic') { ?>
					<a href="<?php echo esc_url($post_data['post_link']); ?>" class="sc_button sc_button_style_accent_1 sc_button_size_big squareButton accent_1 big"><?php echo __('learn more', 'axiom'); ?></a>
					<?php } ?>
				</div>
			</div>

		</div>

<?php	}
}

?>