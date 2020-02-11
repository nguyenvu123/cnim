<?php
/*
Template Name: Team member
*/

get_header(); 

$single_style = 'single-team';	//axiom_get_custom_option('single_style');

while ( have_posts() ) { the_post();

	// Move axiom_set_post_views to the javascript - counter will work under cache system
	if (axiom_get_custom_option('use_ajax_views_counter')=='no') {
		axiom_set_post_views(get_the_ID());
	}

	//axiom_sc_clear_dedicated_content();
	axiom_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !axiom_sc_param_is_off(axiom_get_custom_option('show_sidebar_main')),
			'content' => axiom_get_template_property($single_style, 'need_content'),
			'terms_list' => axiom_get_template_property($single_style, 'need_terms')
		)
	);

}

get_footer();
?>