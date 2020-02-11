
<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_template_list_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_template_list_theme_setup', 1 );
	function axiom_template_list_theme_setup() {
		axiom_add_template(array(
			'layout' => 'list_1',
			'template' => 'list',
			'mode'   => 'blogger',
			'title'  => __('Blogger layout: List / layout 1 /', 'axiom')
			));
		axiom_add_template(array(
			'layout' => 'list_2',
			'template' => 'list',
			'mode'   => 'blogger',
			'title'  => __('Blogger layout: List / layout 2 /', 'axiom')
			));
		axiom_add_template(array(
			'layout' => 'list_3',
			'template' => 'list',
			'mode'   => 'blogger',
			'title'  => __('Blogger layout: List / layout 3 /', 'axiom'),
			'thumb_title'  => __('Small image (crop)', 'axiom'),
			'w'		 => 145,
			'h'		 => 90
			));
	}
}


// Template output
if ( !function_exists( 'axiom_template_list_output' ) ) {
	function axiom_template_list_output($post_options, $post_data) {
	
	
		$parts = explode('_', $post_options['layout']);
		$layout = max(1, min(4, empty($parts[1]) ? $post_options['columns_count'] : (int) $parts[1]));

	 
		if ($layout == 1) {
			$title = '<div class="post_item sc_blogger_item post_title sc_title sc_blogger_title list_style_'.esc_attr($layout).'_item">'
				. '<div class="post_title sc_title sc_blogger_title">'
			    . '<h5>'
				. (!isset($post_options['links']) || $post_options['links'] ? '<a href="' . esc_url($post_data['post_link']) . '">' : '')
			    . ($post_data['post_title'])
				. (!isset($post_options['links']) || $post_options['links'] ? '</a>' : '')
		        . '</h5>'
		        . '<div class="post_info">'
		        . '<span>by <a href="' . esc_url($post_data['post_author_url']) . '">' . $post_data['post_author'] . '</a></span>'
		        . '<span>Views <span>' . esc_html($post_data['post_views']) . '</span></span>'
			    . '</div>'
				. '</div>'
				. '</div>';
			echo ($title);
		} else if ($layout == 2) {
			$title = '<div class="post_item sc_blogger_item post_title sc_title sc_blogger_title list_style_'.esc_attr($layout).'_item">'
		         . '<div class="post_title sc_title sc_blogger_title">'
		         .  the_date('Y-m-d', '<span>', '</span>') 
		        
		         . '<h4>'
		         . (!isset($post_options['links']) || $post_options['links'] ? '<a href="' . esc_url($post_data['post_link']) . '">' : '')
		         . ($post_data['post_title'])
		         . (!isset($post_options['links']) || $post_options['links'] ? '</a>' : '')
		         . '</h4>'
			     #. '<div class="post_descr">' . balanceTags($post_data['post_excerpt']) . '</div>'
			     . '<div class="post_info">'
		         . '<span><span>' . balanceTags($post_data['post_terms'][$post_data['post_taxonomy']]->terms_links[0]) . '</span></span>'
		         . '</div>'
		         . '</div>'
		         . '</div>';
			echo ($title);
		} else if ($layout == 3) {
			$title = '<div class="post_item sc_blogger_item post_title sc_title sc_blogger_title list_style_'.esc_attr($layout).'_item">';
			echo ($title);
            if ($post_data['post_thumb']) {
				require(axiom_get_file_dir('templates/parts/post-featured.php'));
			}
			$title = '';
			$title .= '<div class="post_title sc_title sc_blogger_title">'
			         . '<h5>'
			         . (!isset($post_options['links']) || $post_options['links'] ? '<a href="' . esc_url($post_data['post_link']) . '">' : '')
			         . ($post_data['post_title'])
			         . (!isset($post_options['links']) || $post_options['links'] ? '</a>' : '')
			         . '</h5>'
			         . '<div class="post_descr">' . substr($post_data['post_excerpt'],0,100) . '...</div>'
			         . '</div>'
			         . '</div>';
			echo ($title);
		}
	}
}
?>
