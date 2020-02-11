<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'axiom_shortcodes_is_used' ) ) {
	function axiom_shortcodes_is_used() {
		return axiom_options_is_used() 														// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action'])
			&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))  // AJAX query when save post/page
			|| axiom_vc_is_frontend();														// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'axiom_shortcodes_width' ) ) {
	function axiom_shortcodes_width($w="") {
		return array(
			"title" => __("Width", "axiom"),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'axiom_shortcodes_height' ) ) {
	function axiom_shortcodes_height($h='') {
		return array(
			"title" => __("Height", "axiom"),
			"desc" => __("Width (in pixels or percent) and height (only in pixels) of element", "axiom"),
			"value" => $h,
			"type" => "text"
		);
	}
}

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_shortcodes_settings_theme_setup' ) ) {
//	if ( axiom_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'axiom_action_before_init_theme', 'axiom_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'axiom_action_after_init_theme', 'axiom_shortcodes_settings_theme_setup' );
	function axiom_shortcodes_settings_theme_setup() {
		if (axiom_shortcodes_is_used()) {
			global $AXIOM_GLOBALS;

			// Prepare arrays
			$AXIOM_GLOBALS['sc_params'] = array(

				// Current element id
				'id' => array(
					"title" => __("Element ID", "axiom"),
					"desc" => __("ID for current element", "axiom"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),

				// Current element class
				'class' => array(
					"title" => __("Element CSS class", "axiom"),
					"desc" => __("CSS class for current element (optional)", "axiom"),
					"value" => "",
					"type" => "text"
				),

				// Current element style
				'css' => array(
					"title" => __("CSS styles", "axiom"),
					"desc" => __("Any additional CSS rules (if need)", "axiom"),
					"value" => "",
					"type" => "text"
				),

				// Margins params
				'top' => array(
					"title" => __("Top margin", "axiom"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),

				'bottom' => array(
					"title" => __("Bottom margin", "axiom"),
					"value" => "",
					"type" => "text"
				),

				'left' => array(
					"title" => __("Left margin", "axiom"),
					"value" => "",
					"type" => "text"
				),

				'right' => array(
					"title" => __("Right margin", "axiom"),
					"desc" => __("Margins around list (in pixels).", "axiom"),
					"value" => "",
					"type" => "text"
				),

				// Switcher choises
				'list_styles' => array(
					'ul'	=> __('Unordered', 'axiom'),
					'ol'	=> __('Ordered', 'axiom'),
					'iconed'=> __('Iconed', 'axiom')
				),
				'yes_no'	=> axiom_get_list_yesno(),
				'on_off'	=> axiom_get_list_onoff(),
				'dir' 		=> axiom_get_list_directions(),
				'align'		=> axiom_get_list_alignments(),
				'float'		=> axiom_get_list_floats(),
				'show_hide'	=> axiom_get_list_showhide(),
				'sorting' 	=> axiom_get_list_sortings(),
				'ordering' 	=> axiom_get_list_orderings(),
				'sliders'	=> axiom_get_list_sliders(),
				'users'		=> axiom_get_list_users(),
				'members'	=> axiom_get_list_posts(false, array('post_type'=>'team', 'orderby'=>'title', 'order'=>'asc', 'return'=>'title')),
				'categories'=> axiom_get_list_categories(),
				'testimonials_groups'=> axiom_get_list_terms(false, 'testimonial_group'),
				'team_groups'=> axiom_get_list_terms(false, 'team_group'),
				'columns'	=> axiom_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), axiom_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), axiom_get_list_icons()),
				'locations'	=> axiom_get_list_dedicated_locations(),
				'filters'	=> axiom_get_list_portfolio_filters(),
				'formats'	=> axiom_get_list_post_formats_filters(),
				'hovers'	=> axiom_get_list_hovers(),
				'hovers_dir'=> axiom_get_list_hovers_directions(),
				'tint'		=> axiom_get_list_bg_tints(),
				'size'		=> axiom_get_list_bg_sizes(),
				'animations'=> axiom_get_list_animations_in(),
				'blogger_styles'	=> axiom_get_list_templates_blogger(),
				'posts_types'		=> axiom_get_list_posts_types(),
				'button_styles'		=> axiom_get_list_button_styles(),
				'googlemap_styles'	=> axiom_get_list_googlemap_styles(),
				'field_types'		=> axiom_get_list_field_types(),
				'label_positions'	=> axiom_get_list_label_positions()
			);
			$AXIOM_GLOBALS['sc_params']['animation'] = array(
				"title" => __("Animation",  'axiom'),
				"desc" => __('Select animation while object enter in the visible area of page',  'axiom'),
				"value" => "none",
				"type" => "select",
				"options" => $AXIOM_GLOBALS['sc_params']['animations']
			);

			// Shortcodes list
			//------------------------------------------------------------------
			$AXIOM_GLOBALS['shortcodes'] = array(

				// Accordion
				"trx_accordion" => array(
					"title" => __("Accordion", "axiom"),
					"desc" => __("Accordion items", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Accordion style", "axiom"),
							"desc" => __("Select style for display accordion", "axiom"),
							"value" => 1,
							"options" => array(
								1 => __('Style 1', 'axiom'),
								2 => __('Style 2', 'axiom')
							),
							"type" => "radio"
						),
						"counter" => array(
							"title" => __("Counter", "axiom"),
							"desc" => __("Display counter before each accordion title", "axiom"),
							"value" => "off",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['on_off']
						),
						"initial" => array(
							"title" => __("Initially opened item", "axiom"),
							"desc" => __("Number of initially opened item", "axiom"),
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"icon_closed" => array(
							"title" => __("Icon while closed",  'axiom'),
							"desc" => __('Select icon for the closed accordion item from Fontello icons set',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => __("Icon while opened",  'axiom'),
							"desc" => __('Select icon for the opened accordion item from Fontello icons set',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_accordion_item",
						"title" => __("Item", "axiom"),
						"desc" => __("Accordion item", "axiom"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => __("Accordion item title", "axiom"),
								"desc" => __("Title for current accordion item", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"icon_closed" => array(
								"title" => __("Icon while closed",  'axiom'),
								"desc" => __('Select icon for the closed accordion item from Fontello icons set',  'axiom'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOM_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => __("Icon while opened",  'axiom'),
								"desc" => __('Select icon for the opened accordion item from Fontello icons set',  'axiom'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOM_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => __("Accordion item content", "axiom"),
								"desc" => __("Current accordion item content", "axiom"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOM_GLOBALS['sc_params']['id'],
							"class" => $AXIOM_GLOBALS['sc_params']['class'],
							"css" => $AXIOM_GLOBALS['sc_params']['css']
						)
					)
				),




				// Anchor
				"trx_anchor" => array(
					"title" => __("Anchor", "axiom"),
					"desc" => __("Insert anchor for the TOC (table of content)", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => __("Anchor's icon",  'axiom'),
							"desc" => __('Select icon for the anchor from Fontello icons set',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"title" => array(
							"title" => __("Short title", "axiom"),
							"desc" => __("Short title of the anchor (for the table of content)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Long description", "axiom"),
							"desc" => __("Description for the popup (then hover on the icon). You can use '{' and '}' - make the text italic, '|' - insert line break", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"url" => array(
							"title" => __("External URL", "axiom"),
							"desc" => __("External URL for this TOC item", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"separator" => array(
							"title" => __("Add separator", "axiom"),
							"desc" => __("Add separator under item in the TOC", "axiom"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"id" => $AXIOM_GLOBALS['sc_params']['id']
					)
				),


				// Audio
				"trx_audio" => array(
					"title" => __("Audio", "axiom"),
					"desc" => __("Insert audio player", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => __("URL for audio file", "axiom"),
							"desc" => __("URL for audio file", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose audio', 'axiom'),
								'action' => 'media_upload',
								'type' => 'audio',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array(
									'choose' => __('Choose audio file', 'axiom'),
									'update' => __('Select audio file', 'axiom')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"image" => array(
							"title" => __("Cover image", "axiom"),
							"desc" => __("Select or upload image or write URL from other site for audio cover", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => __("Title", "axiom"),
							"desc" => __("Title of the audio file", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"author" => array(
							"title" => __("Author", "axiom"),
							"desc" => __("Author of the audio file", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => __("Show controls", "axiom"),
							"desc" => __("Show controls in audio player", "axiom"),
							"divider" => true,
							"size" => "medium",
							"value" => "show",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['show_hide']
						),
						"autoplay" => array(
							"title" => __("Autoplay audio", "axiom"),
							"desc" => __("Autoplay audio on page load", "axiom"),
							"value" => "off",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => __("Align", "axiom"),
							"desc" => __("Select block alignment", "axiom"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),




				// Block
				"trx_block" => array(
					"title" => __("Block container", "axiom"),
					"desc" => __("Container for any block ([section] analog - to enable nesting)", "axiom"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => __("Dedicated", "axiom"),
							"desc" => __("Use this block as dedicated content - show it before post title on single page", "axiom"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => __("Align", "axiom"),
							"desc" => __("Select block alignment", "axiom"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => __("Columns emulation", "axiom"),
							"desc" => __("Select width for columns emulation", "axiom"),
							"value" => "none",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['columns']
						),
						"pan" => array(
							"title" => __("Use pan effect", "axiom"),
							"desc" => __("Use pan effect to show section content", "axiom"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => __("Use scroller", "axiom"),
							"desc" => __("Use scroller to show section content", "axiom"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => __("Scroll direction", "axiom"),
							"desc" => __("Scroll direction (if Use scroller = yes)", "axiom"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => __("Scroll controls", "axiom"),
							"desc" => __("Show scroll controls (if Use scroller = yes)", "axiom"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"color" => array(
							"title" => __("Fore color", "axiom"),
							"desc" => __("Any color for objects in this section", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => __("Background tint", "axiom"),
							"desc" => __("Main background tint: dark or light", "axiom"),
							"value" => "",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['tint']
						),
						"bg_size" => array(
							"title" => __("Background size", "axiom"),
							"desc" => __("Main background size: auto, cover or contain", "axiom"),
							"value" => "",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['size']
						),
						"bg_color" => array(
							"title" => __("Background color", "axiom"),
							"desc" => __("Any background color for this section", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "axiom"),
							"desc" => __("Select or upload image or write URL from other site for the background", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "axiom"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "axiom"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "axiom"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "axiom"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => __("Font size", "axiom"),
							"desc" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => __("Font weight", "axiom"),
							"desc" => __("Font weight of the text", "axiom"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'axiom'),
								'300' => __('Light (300)', 'axiom'),
								'400' => __('Normal (400)', 'axiom'),
								'700' => __('Bold (700)', 'axiom')
							)
						),
						"_content_" => array(
							"title" => __("Container content", "axiom"),
							"desc" => __("Content for section container", "axiom"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),




				// Blogger
				"trx_blogger" => array(
					"title" => __("Blogger", "axiom"),
					"desc" => __("Insert posts (pages) in many styles from desired categories or directly from ids", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Posts output style", "axiom"),
							"desc" => __("Select desired style for posts output", "axiom"),
							"value" => "regular",
							"type" => "select",
							"options" => $AXIOM_GLOBALS['sc_params']['blogger_styles']
						),
						"filters" => array(
							"title" => __("Show filters", "axiom"),
							"desc" => __("Use post's tags or categories as filter buttons", "axiom"),
							"value" => "no",
							"dir" => "horizontal",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['filters']
						),
						"hover" => array(
							"title" => __("Hover effect", "axiom"),
							"desc" => __("Select hover effect (only if style=Portfolio)", "axiom"),
							"dependency" => array(
								'style' => array('portfolio','grid','square')
							),
							"value" => "",
							"type" => "select",
							"options" => $AXIOM_GLOBALS['sc_params']['hovers']
						),
						"hover_dir" => array(
							"title" => __("Hover direction", "axiom"),
							"desc" => __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "axiom"),
							"dependency" => array(
								'style' => array('portfolio','grid','square'),
								'hover' => array('square','circle')
							),
							"value" => "left_to_right",
							"type" => "select",
							"options" => $AXIOM_GLOBALS['sc_params']['hovers_dir']
						),
						"dir" => array(
							"title" => __("Posts direction", "axiom"),
							"desc" => __("Display posts in horizontal or vertical direction", "axiom"),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['dir']
						),
						"post_type" => array(
							"title" => __("Post type", "axiom"),
							"desc" => __("Select post type to show", "axiom"),
							"value" => "post",
							"type" => "select",
							"options" => $AXIOM_GLOBALS['sc_params']['posts_types']
						),
						"ids" => array(
							"title" => __("Post IDs list", "axiom"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"cat" => array(
							"title" => __("Categories list", "axiom"),
							"desc" => __("Select the desired categories. If not selected - show posts from any category or from IDs list", "axiom"),
							"dependency" => array(
								'ids' => array('is_empty'),
								'post_type' => array('refresh')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $AXIOM_GLOBALS['sc_params']['categories']
						),
						"count" => array(
							"title" => __("Total posts to show", "axiom"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiom"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns number", "axiom"),
							"desc" => __("How many columns used to show posts? If empty or 0 - equal to posts number", "axiom"),
							"dependency" => array(
								'dir' => array('horizontal')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Offset before select posts", "axiom"),
							"desc" => __("Skip posts before select next part.", "axiom"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Post order by", "axiom"),
							"desc" => __("Select desired posts sorting method", "axiom"),
							"value" => "date",
							"type" => "select",
							"options" => $AXIOM_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Post order", "axiom"),
							"desc" => __("Select desired posts order", "axiom"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						),
						"only" => array(
							"title" => __("Select posts only", "axiom"),
							"desc" => __("Select posts only with reviews, videos, audios, thumbs or galleries", "axiom"),
							"value" => "no",
							"type" => "select",
							"options" => $AXIOM_GLOBALS['sc_params']['formats']
						),
						"scroll" => array(
							"title" => __("Use scroller", "axiom"),
							"desc" => __("Use scroller to show all posts", "axiom"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => __("Show slider controls", "axiom"),
							"desc" => __("Show arrows to control scroll slider", "axiom"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"location" => array(
							"title" => __("Dedicated content location", "axiom"),
							"desc" => __("Select position for dedicated content (only for style=excerpt)", "axiom"),
							"divider" => true,
							"dependency" => array(
								'style' => array('excerpt')
							),
							"value" => "default",
							"type" => "select",
							"options" => $AXIOM_GLOBALS['sc_params']['locations']
						),
						"rating" => array(
							"title" => __("Show rating stars", "axiom"),
							"desc" => __("Show rating stars under post's header", "axiom"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"info" => array(
							"title" => __("Show post info block", "axiom"),
							"desc" => __("Show post info block (author, date, tags, etc.)", "axiom"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"links" => array(
							"title" => __("Allow links on the post", "axiom"),
							"desc" => __("Allow links on the post from each blogger item", "axiom"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"descr" => array(
							"title" => __("Description length", "axiom"),
							"desc" => __("How many characters are displayed from post excerpt? If 0 - don't show description", "axiom"),
							"value" => 0,
							"min" => 0,
							"step" => 10,
							"type" => "spinner"
						),
						"readmore" => array(
							"title" => __("More link text", "axiom"),
							"desc" => __("Read more link text. If empty - show 'More', else - used as link text", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),





				// Br
				"trx_br" => array(
					"title" => __("Break", "axiom"),
					"desc" => __("Line break with clear floating (if need)", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"clear" => 	array(
							"title" => __("Clear floating", "axiom"),
							"desc" => __("Clear floating (if need)", "axiom"),
							"value" => "",
							"type" => "checklist",
							"options" => array(
								'none' => __('None', 'axiom'),
								'left' => __('Left', 'axiom'),
								'right' => __('Right', 'axiom'),
								'both' => __('Both', 'axiom')
							)
						)
					)
				),




				// Button
				"trx_button" => array(
					"title" => __("Button", "axiom"),
					"desc" => __("Button with link", "axiom"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Caption", "axiom"),
							"desc" => __("Button caption", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"type" => array(
							"title" => __("Button's shape", "axiom"),
							"desc" => __("Select button's shape", "axiom"),
							"value" => "square",
							"size" => "medium",
							"options" => array(
								'square' => __('Square', 'axiom'),
								'round' => __('Round', 'axiom')
							),
							"type" => "switch"
						),
						"style" => array(
							"title" => __("Button's style", "axiom"),
							"desc" => __("Select button's style", "axiom"),
							"value" => "default",
							"dir" => "horizontal",
							"options" => array(
								'filled' => __('Filled', 'axiom'),
								'border' => __('Border', 'axiom')
							),
							"type" => "checklist"
						),
						"size" => array(
							"title" => __("Button's size", "axiom"),
							"desc" => __("Select button's size", "axiom"),
							"value" => "small",
							"dir" => "horizontal",
							"options" => array(
								'small' => __('Small', 'axiom'),
								'medium' => __('Medium', 'axiom'),
								'large' => __('Large', 'axiom')
							),
							"type" => "checklist"
						),
						"icon" => array(
							"title" => __("Button's icon",  'axiom'),
							"desc" => __('Select icon for the title from Fontello icons set',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"bg_style" => array(
							"title" => __("Button's color scheme", "axiom"),
							"desc" => __("Select button's color scheme", "axiom"),
							"value" => "custom",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['button_styles']
						),
						"color" => array(
							"title" => __("Button's text color", "axiom"),
							"desc" => __("Any color for button's caption", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Button's backcolor", "axiom"),
							"desc" => __("Any color for button's background", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"align" => array(
							"title" => __("Button's alignment", "axiom"),
							"desc" => __("Align button to left, center or right", "axiom"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"link" => array(
							"title" => __("Link URL", "axiom"),
							"desc" => __("URL for link on button click", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"target" => array(
							"title" => __("Link target", "axiom"),
							"desc" => __("Target for link on button click", "axiom"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"popup" => array(
							"title" => __("Open link in popup", "axiom"),
							"desc" => __("Open link target in popup window", "axiom"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"rel" => array(
							"title" => __("Rel attribute", "axiom"),
							"desc" => __("Rel attribute for button's link (if need)", "axiom"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),



				// Chat
				"trx_chat" => array(
					"title" => __("Chat", "axiom"),
					"desc" => __("Chat message", "axiom"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => __("Item title", "axiom"),
							"desc" => __("Chat item title", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"photo" => array(
							"title" => __("Item photo", "axiom"),
							"desc" => __("Select or upload image or write URL from other site for the item photo (avatar)", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"link" => array(
							"title" => __("Item link", "axiom"),
							"desc" => __("Chat item link", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Chat item content", "axiom"),
							"desc" => __("Current chat item content", "axiom"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),


				// Columns
				"trx_columns" => array(
					"title" => __("Columns", "axiom"),
					"desc" => __("Insert up to 5 columns in your page (post)", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"fluid" => array(
							"title" => __("Fluid columns", "axiom"),
							"desc" => __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "axiom"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_column_item",
						"title" => __("Column", "axiom"),
						"desc" => __("Column item", "axiom"),
						"container" => true,
						"params" => array(
							"span" => array(
								"title" => __("Merge columns", "axiom"),
								"desc" => __("Count merged columns from current", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"align" => array(
								"title" => __("Alignment", "axiom"),
								"desc" => __("Alignment text in the column", "axiom"),
								"value" => "",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $AXIOM_GLOBALS['sc_params']['align']
							),
							"color" => array(
								"title" => __("Fore color", "axiom"),
								"desc" => __("Any color for objects in this column", "axiom"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => __("Background color", "axiom"),
								"desc" => __("Any background color for this column", "axiom"),
								"value" => "",
								"type" => "color"
							),
							"bg_image" => array(
								"title" => __("URL for background image file", "axiom"),
								"desc" => __("Select or upload image or write URL from other site for the background", "axiom"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => __("Column item content", "axiom"),
								"desc" => __("Current column item content", "axiom"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOM_GLOBALS['sc_params']['id'],
							"class" => $AXIOM_GLOBALS['sc_params']['class'],
							"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
							"css" => $AXIOM_GLOBALS['sc_params']['css']
						)
					)
				),




				// Contact form
				"trx_contact_form" => array(
					"title" => __("Contact form", "axiom"),
					"desc" => __("Insert contact form", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"custom" => array(
							"title" => __("Custom", "axiom"),
							"desc" => __("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", "axiom"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"action" => array(
							"title" => __("Action", "axiom"),
							"desc" => __("Contact form action (URL to handle form data). If empty - use internal action", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Align", "axiom"),
							"desc" => __("Select form alignment", "axiom"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"title" => array(
							"title" => __("Title", "axiom"),
							"desc" => __("Contact form title", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Description", "axiom"),
							"desc" => __("Short description for contact form", "axiom"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiom_shortcodes_width(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_form_item",
						"title" => __("Field", "axiom"),
						"desc" => __("Custom field", "axiom"),
						"container" => false,
						"params" => array(
							"type" => array(
								"title" => __("Type", "axiom"),
								"desc" => __("Type of the custom field", "axiom"),
								"value" => "text",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $AXIOM_GLOBALS['sc_params']['field_types']
							),
							"name" => array(
								"title" => __("Name", "axiom"),
								"desc" => __("Name of the custom field", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => __("Default value", "axiom"),
								"desc" => __("Default value of the custom field", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"label" => array(
								"title" => __("Label", "axiom"),
								"desc" => __("Label for the custom field", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"label_position" => array(
								"title" => __("Label position", "axiom"),
								"desc" => __("Label position relative to the field", "axiom"),
								"value" => "top",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $AXIOM_GLOBALS['sc_params']['label_positions']
							),
							"top" => $AXIOM_GLOBALS['sc_params']['top'],
							"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
							"left" => $AXIOM_GLOBALS['sc_params']['left'],
							"right" => $AXIOM_GLOBALS['sc_params']['right'],
							"id" => $AXIOM_GLOBALS['sc_params']['id'],
							"class" => $AXIOM_GLOBALS['sc_params']['class'],
							"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
							"css" => $AXIOM_GLOBALS['sc_params']['css']
						)
					)
				),




				// Content block on fullscreen page
				"trx_content" => array(
					"title" => __("Content block", "axiom"),
					"desc" => __("Container for main content block with desired class and style (use it only on fullscreen pages)", "axiom"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Container content", "axiom"),
							"desc" => __("Content for section container", "axiom"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),





				// Countdown
				"trx_countdown" => array(
					"title" => __("Countdown", "axiom"),
					"desc" => __("Insert countdown object", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"date" => array(
							"title" => __("Date", "axiom"),
							"desc" => __("Upcoming date (format: yyyy-mm-dd)", "axiom"),
							"value" => "",
							"format" => "yy-mm-dd",
							"type" => "date"
						),
						"time" => array(
							"title" => __("Time", "axiom"),
							"desc" => __("Upcoming time (format: HH:mm:ss)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => __("Style", "axiom"),
							"desc" => __("Countdown style", "axiom"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								1 => __('Style 1', 'axiom'),
								2 => __('Style 2', 'axiom')
							)
						),
						"align" => array(
							"title" => __("Alignment", "axiom"),
							"desc" => __("Align counter to left, center or right", "axiom"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),




				// Dropcaps
				"trx_dropcaps" => array(
					"title" => __("Dropcaps", "axiom"),
					"desc" => __("Make first letter as dropcaps", "axiom"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => __("Style", "axiom"),
							"desc" => __("Dropcaps style", "axiom"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								1 => __('Style 1', 'axiom'),
								2 => __('Style 2', 'axiom'),
								3 => __('Style 3', 'axiom'),
								4 => __('Style 4', 'axiom')
							)
						),
						"_content_" => array(
							"title" => __("Paragraph content", "axiom"),
							"desc" => __("Paragraph with dropcaps content", "axiom"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),





				// Emailer
				"trx_emailer" => array(
					"title" => __("E-mail collector", "axiom"),
					"desc" => __("Collect the e-mail address into specified group", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"group" => array(
							"title" => __("Group", "axiom"),
							"desc" => __("The name of group to collect e-mail address", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"open" => array(
							"title" => __("Open", "axiom"),
							"desc" => __("Initially open the input field on show object", "axiom"),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => __("Alignment", "axiom"),
							"desc" => __("Align object to left, center or right", "axiom"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),





				// Events
				"trx_events" => array(
					"title" => __("Events", "axiom"),
					"desc" => __("Insert posts (events) from desired categories or directly from ids", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Posts output style", "axiom"),
							"desc" => __("Select desired style for event output", "axiom"),
							"value" => "regular",
							"type" => "select",
							"options" => array(
								'list' => __('Horizontal List', 'axiom'),
								'classic' => __('Classic Layout', 'axiom'),
								'classic2' => __('Classic 2 Layout', 'axiom')
							)
						),
						"ids" => array(
							"title" => __("Post IDs list", "axiom"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"cat" => array(
							"title" => __("Category ID", "axiom"),
							"desc" => __("Category ID", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"count" => array(
							"title" => __("Total posts to show", "axiom"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored. (max - 4)", "axiom"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 3,
							"min" => 1,
							"max" => 4,
							"type" => "spinner"
						),
						"col" => array(
							"title" => __("Column", "axiom"),
							"desc" => __("How many columns will be displayed? (max - 4)", "axiom"),
							"value" => 3,
							"min" => 1,
							"max" => 4,
							"type" => "spinner"
						),
						"order" => array(
							"title" => __("Post order", "axiom"),
							"desc" => __("Select desired posts order", "axiom"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						),

						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right']
					)
				),




				// Gap
				"trx_gap" => array(
					"title" => __("Gap", "axiom"),
					"desc" => __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "axiom"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Gap content", "axiom"),
							"desc" => __("Gap inner content", "axiom"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						)
					)
				),
			
			
			
			
			
				// Google map
				"trx_googlemap" => array(
					"title" => __("Google map", "axiom"),
					"desc" => __("Insert Google map with desired address or coordinates", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"address" => array(
							"title" => __("Address", "axiom"),
							"desc" => __("Address to show in map center", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"latlng" => array(
							"title" => __("Latitude and Longtitude", "axiom"),
							"desc" => __("Comma separated map center coorditanes (instead Address)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"zoom" => array(
							"title" => __("Zoom", "axiom"),
							"desc" => __("Map zoom factor", "axiom"),
							"divider" => true,
							"value" => 16,
							"min" => 1,
							"max" => 20,
							"type" => "spinner"
						),
						"style" => array(
							"title" => __("Map style", "axiom"),
							"desc" => __("Select map style", "axiom"),
							"value" => "default",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['googlemap_styles']
						),
						"width" => axiom_shortcodes_width('100%'),
						"height" => axiom_shortcodes_height(240),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Hexagon
				"trx_hexagon" => array(
					"title" => __("Hexagon", "axiom"),
					"desc" => __("Hexagon with content", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"text" => array(
							"title" => __("Hexagon text", "axiom"),
							"desc" => __("Text, if needed, that would be shown inside of hexagon.", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => __('Icon',  'axiom'),
							"desc" => __('Hexagon icon, if needed, from the Fontello icons set',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"link" => array(
							"title" => __("Link URL", "axiom"),
							"desc" => __("Link URL, if needed, from this icon (if not empty) and text (if entered)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation']
					)
				),



				// Hide or show any block
				"trx_hide" => array(
					"title" => __("Hide/Show any block", "axiom"),
					"desc" => __("Hide or Show any block with desired CSS-selector", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"selector" => array(
							"title" => __("Selector", "axiom"),
							"desc" => __("Any block's CSS-selector", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"hide" => array(
							"title" => __("Hide or Show", "axiom"),
							"desc" => __("New state for the block: hide or show", "axiom"),
							"value" => "yes",
							"size" => "small",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						)
					)
				),
			
			
			
				// Highlght text
				"trx_highlight" => array(
					"title" => __("Highlight text", "axiom"),
					"desc" => __("Highlight text with selected color, background color and other styles", "axiom"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"type" => array(
							"title" => __("Type", "axiom"),
							"desc" => __("Highlight type", "axiom"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								0 => __('Custom', 'axiom'),
								1 => __('Type 1', 'axiom'),
								2 => __('Type 2', 'axiom'),
								3 => __('Type 3', 'axiom')
							)
						),
						"color" => array(
							"title" => __("Color", "axiom"),
							"desc" => __("Color for the highlighted text", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "axiom"),
							"desc" => __("Background color for the highlighted text", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => __("Font size", "axiom"),
							"desc" => __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Highlighting content", "axiom"),
							"desc" => __("Content for highlight", "axiom"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Icon
				"trx_icon" => array(
					"title" => __("Icon", "axiom"),
					"desc" => __("Insert icon", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => __('Icon',  'axiom'),
							"desc" => __('Select font icon from the Fontello icons set',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => __("Icon's color", "axiom"),
							"desc" => __("Icon's color", "axiom"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "color"
						),
						"bg_shape" => array(
							"title" => __("Background shape", "axiom"),
							"desc" => __("Shape of the icon background", "axiom"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "none",
							"type" => "radio",
							"options" => array(
								'none' => __('None', 'axiom'),
								'round' => __('Round', 'axiom'),
								'square' => __('Square', 'axiom')
							)
						),
						"bg_style" => array(
							"title" => __("Background style", "axiom"),
							"desc" => __("Select icon's color scheme", "axiom"),
							"value" => "custom",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['button_styles']
						), 
						"bg_color" => array(
							"title" => __("Icon's background color", "axiom"),
							"desc" => __("Icon's background color", "axiom"),
							"dependency" => array(
								'icon' => array('not_empty'),
								'background' => array('round','square')
							),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => __("Font size", "axiom"),
							"desc" => __("Icon's font size", "axiom"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "spinner",
							"min" => 8,
							"max" => 240
						),
						"font_weight" => array(
							"title" => __("Font weight", "axiom"),
							"desc" => __("Icon font weight", "axiom"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'axiom'),
								'300' => __('Light (300)', 'axiom'),
								'400' => __('Normal (400)', 'axiom'),
								'700' => __('Bold (700)', 'axiom')
							)
						),
						"align" => array(
							"title" => __("Alignment", "axiom"),
							"desc" => __("Icon text alignment", "axiom"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => __("Link URL", "axiom"),
							"desc" => __("Link URL from this icon (if not empty)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Image
				"trx_image" => array(
					"title" => __("Image", "axiom"),
					"desc" => __("Insert image into your post (page)", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => __("URL for image file", "axiom"),
							"desc" => __("Select or upload image or write URL from other site", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => __("Title", "axiom"),
							"desc" => __("Image title (if need)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => __("Icon before title",  'axiom'),
							"desc" => __('Select icon for the title from Fontello icons set',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"align" => array(
							"title" => __("Float image", "axiom"),
							"desc" => __("Float image to left or right side", "axiom"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['float']
						), 
						"shape" => array(
							"title" => __("Image Shape", "axiom"),
							"desc" => __("Shape of the image: square (rectangle) or round", "axiom"),
							"value" => "square",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"square" => __('Square', 'axiom'),
								"round" => __('Round', 'axiom')
							)
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Infobox
				"trx_infobox" => array(
					"title" => __("Infobox", "axiom"),
					"desc" => __("Insert infobox into your post (page)", "axiom"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => __("Style", "axiom"),
							"desc" => __("Infobox style", "axiom"),
							"value" => "regular",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'regular' => __('Regular', 'axiom'),
								'info' => __('Info', 'axiom'),
								'success' => __('Success', 'axiom'),
								'error' => __('Error', 'axiom')
							)
						),
						"closeable" => array(
							"title" => __("Closeable box", "axiom"),
							"desc" => __("Create closeable box (with close button)", "axiom"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"icon" => array(
							"title" => __("Custom icon",  'axiom'),
							"desc" => __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => __("Text color", "axiom"),
							"desc" => __("Any color for text and headers", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "axiom"),
							"desc" => __("Any background color for this infobox", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"_content_" => array(
							"title" => __("Infobox content", "axiom"),
							"desc" => __("Content for infobox", "axiom"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Line
				"trx_line" => array(
					"title" => __("Line", "axiom"),
					"desc" => __("Insert Line into your post (page)", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Style", "axiom"),
							"desc" => __("Line style", "axiom"),
							"value" => "solid",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'solid' => __('Solid', 'axiom'),
								'dashed' => __('Dashed', 'axiom'),
								'dotted' => __('Dotted', 'axiom'),
								'double' => __('Double', 'axiom')
							)
						),
						"color" => array(
							"title" => __("Color", "axiom"),
							"desc" => __("Line color", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// List
				"trx_list" => array(
					"title" => __("List", "axiom"),
					"desc" => __("List items with specific bullets", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Bullet's style", "axiom"),
							"desc" => __("Bullet's style for each list item", "axiom"),
							"value" => "ul",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['list_styles']
						), 
						"color" => array(
							"title" => __("Color", "axiom"),
							"desc" => __("List items color", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => __('List icon',  'axiom'),
							"desc" => __("Select list icon from Fontello icons set (only for style=Iconed)",  'axiom'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"icon_color" => array(
							"title" => __("Icon color", "axiom"),
							"desc" => __("List icons color", "axiom"),
							"value" => "",
							"dependency" => array(
								'style' => array('iconed')
							),
							"type" => "color"
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_list_item",
						"title" => __("Item", "axiom"),
						"desc" => __("List item with specific bullet", "axiom"),
						"decorate" => false,
						"container" => true,
						"params" => array(
							"_content_" => array(
								"title" => __("List item content", "axiom"),
								"desc" => __("Current list item content", "axiom"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"title" => array(
								"title" => __("List item title", "axiom"),
								"desc" => __("Current list item title (show it as tooltip)", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"color" => array(
								"title" => __("Color", "axiom"),
								"desc" => __("Text color for this item", "axiom"),
								"value" => "",
								"type" => "color"
							),
							"icon" => array(
								"title" => __('List icon',  'axiom'),
								"desc" => __("Select list item icon from Fontello icons set (only for style=Iconed)",  'axiom'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOM_GLOBALS['sc_params']['icons']
							),
							"icon_color" => array(
								"title" => __("Icon color", "axiom"),
								"desc" => __("Icon color for this item", "axiom"),
								"value" => "",
								"type" => "color"
							),
							"link" => array(
								"title" => __("Link URL", "axiom"),
								"desc" => __("Link URL for the current list item", "axiom"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"target" => array(
								"title" => __("Link target", "axiom"),
								"desc" => __("Link target for the current list item", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"id" => $AXIOM_GLOBALS['sc_params']['id'],
							"class" => $AXIOM_GLOBALS['sc_params']['class'],
							"css" => $AXIOM_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
				// Number
				"trx_number" => array(
					"title" => __("Number", "axiom"),
					"desc" => __("Insert number or any word as set separate characters", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"value" => array(
							"title" => __("Value", "axiom"),
							"desc" => __("Number or any word", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Align", "axiom"),
							"desc" => __("Select block alignment", "axiom"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Parallax
				"trx_parallax" => array(
					"title" => __("Parallax", "axiom"),
					"desc" => __("Create the parallax container (with asinc background image)", "axiom"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"gap" => array(
							"title" => __("Create gap", "axiom"),
							"desc" => __("Create gap around parallax container", "axiom"),
							"value" => "no",
							"size" => "small",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						), 
						"dir" => array(
							"title" => __("Dir", "axiom"),
							"desc" => __("Scroll direction for the parallax background", "axiom"),
							"value" => "up",
							"size" => "medium",
							"options" => array(
								'up' => __('Up', 'axiom'),
								'down' => __('Down', 'axiom')
							),
							"type" => "switch"
						), 
						"speed" => array(
							"title" => __("Speed", "axiom"),
							"desc" => __("Image motion speed (from 0.0 to 1.0)", "axiom"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0.3",
							"type" => "spinner"
						),
						"color" => array(
							"title" => __("Text color", "axiom"),
							"desc" => __("Select color for text object inside parallax block", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => __("Bg tint", "axiom"),
							"desc" => __("Select tint of the parallax background (for correct font color choise)", "axiom"),
							"value" => "light",
							"size" => "medium",
							"options" => array(
								'light' => __('Light', 'axiom'),
								'dark' => __('Dark', 'axiom')
							),
							"type" => "switch"
						), 
						"bg_color" => array(
							"title" => __("Background color", "axiom"),
							"desc" => __("Select color for parallax background", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image", "axiom"),
							"desc" => __("Select or upload image or write URL from other site for the parallax background", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image_x" => array(
							"title" => __("Image X position", "axiom"),
							"desc" => __("Image horizontal position (as background of the parallax block) - in percent", "axiom"),
							"min" => "0",
							"max" => "100",
							"value" => "50",
							"type" => "spinner"
						),
						"bg_video" => array(
							"title" => __("Video background", "axiom"),
							"desc" => __("Select video from media library or paste URL for video file from other site to show it as parallax background", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose video', 'axiom'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose video file', 'axiom'),
									'update' => __('Select video file', 'axiom')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"bg_video_ratio" => array(
							"title" => __("Video ratio", "axiom"),
							"desc" => __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "axiom"),
							"value" => "16:9",
							"type" => "text"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "axiom"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "axiom"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "axiom"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "axiom"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"_content_" => array(
							"title" => __("Content", "axiom"),
							"desc" => __("Content for the parallax container", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Popup
				"trx_popup" => array(
					"title" => __("Popup window", "axiom"),
					"desc" => __("Container for any html-block with desired class and style for popup window", "axiom"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Container content", "axiom"),
							"desc" => __("Content for section container", "axiom"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Price
				"trx_price" => array(
					"title" => __("Price", "axiom"),
					"desc" => __("Insert price with decoration", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"money" => array(
							"title" => __("Money", "axiom"),
							"desc" => __("Money value (dot or comma separated)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => __("Currency", "axiom"),
							"desc" => __("Currency character", "axiom"),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => __("Period", "axiom"),
							"desc" => __("Period text (if need). For example: monthly, daily, etc.", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Alignment", "axiom"),
							"desc" => __("Align price to left or right side", "axiom"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['float']
						), 
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Price block
				"trx_price_block" => array(
					"title" => __("Price block", "axiom"),
					"desc" => __("Insert price block with title, price and description", "axiom"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => __("Title", "axiom"),
							"desc" => __("Block title", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => __("Link URL", "axiom"),
							"desc" => __("URL for link from button (at bottom of the block)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"link_text" => array(
							"title" => __("Link text", "axiom"),
							"desc" => __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => __("Icon",  'axiom'),
							"desc" => __('Select icon from Fontello icons set (placed before/instead price)',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"money" => array(
							"title" => __("Money", "axiom"),
							"desc" => __("Money value (dot or comma separated)", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => __("Currency", "axiom"),
							"desc" => __("Currency character", "axiom"),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => __("Period", "axiom"),
							"desc" => __("Period text (if need). For example: monthly, daily, etc.", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Alignment", "axiom"),
							"desc" => __("Align price to left or right side", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['float']
						), 
						"_content_" => array(
							"title" => __("Description", "axiom"),
							"desc" => __("Description for this price block", "axiom"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Quote
				"trx_quote" => array(
					"title" => __("Quote", "axiom"),
					"desc" => __("Quote text", "axiom"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"cite" => array(
							"title" => __("Quote cite", "axiom"),
							"desc" => __("URL for quote cite", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"title" => array(
							"title" => __("Title (author)", "axiom"),
							"desc" => __("Quote title (author name)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Quote content", "axiom"),
							"desc" => __("Quote content", "axiom"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiom_shortcodes_width(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Reviews
				"trx_reviews" => array(
					"title" => __("Reviews", "axiom"),
					"desc" => __("Insert reviews block in the single post", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"align" => array(
							"title" => __("Alignment", "axiom"),
							"desc" => __("Align counter to left, center or right", "axiom"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						), 
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Search
				"trx_search" => array(
					"title" => __("Search", "axiom"),
					"desc" => __("Show search form", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"ajax" => array(
							"title" => __("Style", "axiom"),
							"desc" => __("Select style to display search field", "axiom"),
							"value" => "regular",
							"options" => array(
								"regular" => __('Regular', 'axiom'),
								"flat" => __('Flat', 'axiom')
							),
							"type" => "checklist"
						),
						"title" => array(
							"title" => __("Title", "axiom"),
							"desc" => __("Title (placeholder) for the search field", "axiom"),
							"value" => __("Search &hellip;", 'axiom'),
							"type" => "text"
						),
						"ajax" => array(
							"title" => __("AJAX", "axiom"),
							"desc" => __("Search via AJAX or reload page", "axiom"),
							"value" => "yes",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Section
				"trx_section" => array(
					"title" => __("Section container", "axiom"),
					"desc" => __("Container for any block with desired class and style", "axiom"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => __("Dedicated", "axiom"),
							"desc" => __("Use this block as dedicated content - show it before post title on single page", "axiom"),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => __("Align", "axiom"),
							"desc" => __("Select block alignment", "axiom"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => __("Columns emulation", "axiom"),
							"desc" => __("Select width for columns emulation", "axiom"),
							"value" => "none",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => __("Use pan effect", "axiom"),
							"desc" => __("Use pan effect to show section content", "axiom"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => __("Use scroller", "axiom"),
							"desc" => __("Use scroller to show section content", "axiom"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => __("Scroll and Pan direction", "axiom"),
							"desc" => __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "axiom"),
							"dependency" => array(
								'pan' => array('yes'),
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => __("Scroll controls", "axiom"),
							"desc" => __("Show scroll controls (if Use scroller = yes)", "axiom"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"color" => array(
							"title" => __("Fore color", "axiom"),
							"desc" => __("Any color for objects in this section", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => __("Background tint", "axiom"),
							"desc" => __("Main background tint: dark or light", "axiom"),
							"value" => "",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => __("Background color", "axiom"),
							"desc" => __("Any background color for this section", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "axiom"),
							"desc" => __("Select or upload image or write URL from other site for the background", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "axiom"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "axiom"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "axiom"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "axiom"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => __("Font size", "axiom"),
							"desc" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => __("Font weight", "axiom"),
							"desc" => __("Font weight of the text", "axiom"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'axiom'),
								'300' => __('Light (300)', 'axiom'),
								'400' => __('Normal (400)', 'axiom'),
								'700' => __('Bold (700)', 'axiom')
							)
						),
						"_content_" => array(
							"title" => __("Container content", "axiom"),
							"desc" => __("Content for section container", "axiom"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Skills
				"trx_skills" => array(
					"title" => __("Skills", "axiom"),
					"desc" => __("Insert skills diagramm in your page (post)", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"max_value" => array(
							"title" => __("Max value", "axiom"),
							"desc" => __("Max value for skills items", "axiom"),
							"value" => 100,
							"min" => 1,
							"type" => "spinner"
						),
						"type" => array(
							"title" => __("Skills type", "axiom"),
							"desc" => __("Select type of skills block", "axiom"),
							"value" => "bar",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'bar' => __('Bar', 'axiom'),
								'pie' => __('Pie chart', 'axiom'),
								'counter' => __('Counter', 'axiom'),
								'arc' => __('Arc', 'axiom')
							)
						), 
						"layout" => array(
							"title" => __("Skills layout", "axiom"),
							"desc" => __("Select layout of skills block", "axiom"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "rows",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'rows' => __('Rows', 'axiom'),
								'columns' => __('Columns', 'axiom')
							)
						),
						"dir" => array(
							"title" => __("Direction", "axiom"),
							"desc" => __("Select direction of skills block", "axiom"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "horizontal",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['dir']
						), 
						"style" => array(
							"title" => __("Counters style", "axiom"),
							"desc" => __("Select style of skills items (only for type=counter)", "axiom"),
							"dependency" => array(
								'type' => array('counter')
							),
							"value" => 1,
							"min" => 1,
							"max" => 4,
							"type" => "spinner"
						), 
						// "columns" - autodetect, not set manual
						"color" => array(
							"title" => __("Skills items color", "axiom"),
							"desc" => __("Color for all skills items", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => __("Background color", "axiom"),
							"desc" => __("Background color for all skills items (only for type=pie)", "axiom"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"border_color" => array(
							"title" => __("Border color", "axiom"),
							"desc" => __("Border color for all skills items (only for type=pie)", "axiom"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"title" => array(
							"title" => __("Skills title", "axiom"),
							"desc" => __("Skills block title", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => __("Skills subtitle", "axiom"),
							"desc" => __("Skills block subtitle - text in the center (only for type=arc)", "axiom"),
							"dependency" => array(
								'type' => array('arc')
							),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Align skills block", "axiom"),
							"desc" => __("Align skills block to left or right side", "axiom"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['float']
						), 
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_skills_item",
						"title" => __("Skill", "axiom"),
						"desc" => __("Skills item", "axiom"),
						"container" => false,
						"params" => array(
							"title" => array(
								"title" => __("Title", "axiom"),
								"desc" => __("Current skills item title", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => __('Icon',  'axiom'),
								"desc" => __('Select font icon from the Fontello icons set',  'axiom'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOM_GLOBALS['sc_params']['icons']
							),
							"value" => array(
								"title" => __("Value", "axiom"),
								"desc" => __("Current skills level", "axiom"),
								"value" => 50,
								"min" => 0,
								"step" => 1,
								"type" => "spinner"
							),
							"color" => array(
								"title" => __("Color", "axiom"),
								"desc" => __("Current skills item color", "axiom"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => __("Background color", "axiom"),
								"desc" => __("Current skills item background color (only for type=pie)", "axiom"),
								"value" => "",
								"type" => "color"
							),
							"border_color" => array(
								"title" => __("Border color", "axiom"),
								"desc" => __("Current skills item border color (only for type=pie)", "axiom"),
								"value" => "",
								"type" => "color"
							),
							"style" => array(
								"title" => __("Counter tyle", "axiom"),
								"desc" => __("Select style for the current skills item (only for type=counter)", "axiom"),
								"value" => 1,
								"min" => 1,
								"max" => 4,
								"type" => "spinner"
							), 
							"id" => $AXIOM_GLOBALS['sc_params']['id'],
							"class" => $AXIOM_GLOBALS['sc_params']['class'],
							"css" => $AXIOM_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Slider
				"trx_slider" => array(
					"title" => __("Slider", "axiom"),
					"desc" => __("Insert slider into your post (page)", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array_merge(array(
						"engine" => array(
							"title" => __("Slider engine", "axiom"),
							"desc" => __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "axiom"),
							"value" => "swiper",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['sliders']
						),
						"align" => array(
							"title" => __("Float slider", "axiom"),
							"desc" => __("Float slider to left or right side", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['float']
						),
						"custom" => array(
							"title" => __("Custom slides", "axiom"),
							"desc" => __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "axiom"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						)
						),
						axiom_exists_revslider() || axiom_exists_royalslider() ? array(
						"alias" => array(
							"title" => __("Revolution slider alias or Royal Slider ID", "axiom"),
							"desc" => __("Alias for Revolution slider or Royal slider ID", "axiom"),
							"dependency" => array(
								'engine' => array('revo','royal')
							),
							"divider" => true,
							"value" => "",
							"type" => "text"
						)) : array(), array(
						"cat" => array(
							"title" => __("Swiper: Category list", "axiom"),
							"desc" => __("Comma separated list of category slugs. If empty - select posts from any category or from IDs list", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $AXIOM_GLOBALS['sc_params']['categories']
						),
						"count" => array(
							"title" => __("Swiper: Number of posts", "axiom"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Swiper: Offset before select posts", "axiom"),
							"desc" => __("Skip posts before select next part.", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Swiper: Post order by", "axiom"),
							"desc" => __("Select desired posts sorting method", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "date",
							"type" => "select",
							"options" => $AXIOM_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Swiper: Post order", "axiom"),
							"desc" => __("Select desired posts order", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => __("Swiper: Post IDs list", "axiom"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => __("Swiper: Show slider controls", "axiom"),
							"desc" => __("Show arrows inside slider", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"pagination" => array(
							"title" => __("Swiper: Show slider pagination", "axiom"),
							"desc" => __("Show bullets for switch slides", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "checklist",
							"options" => array(
								'yes'  => __('Dots', 'axiom'),
								'full' => __('Side Titles', 'axiom'),
								'over' => __('Over Titles', 'axiom'),
								'no'   => __('None', 'axiom')
							)
						),
						"titles" => array(
							"title" => __("Swiper: Show titles section", "axiom"),
							"desc" => __("Show section with post's title and short post's description", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"options" => array(
								"no"    => __('Not show', 'axiom'),
								"slide" => __('Show/Hide info', 'axiom'),
								"fixed" => __('Fixed info', 'axiom')
							)
						),
						"descriptions" => array(
							"title" => __("Swiper: Post descriptions", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"desc" => __("Show post's excerpt max length (characters)", "axiom"),
							"value" => 0,
							"min" => 0,
							"max" => 1000,
							"step" => 10,
							"type" => "spinner"
						),
						"links" => array(
							"title" => __("Swiper: Post's title as link", "axiom"),
							"desc" => __("Make links from post's titles", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"crop" => array(
							"title" => __("Swiper: Crop images", "axiom"),
							"desc" => __("Crop images in each slide or live it unchanged", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"autoheight" => array(
							"title" => __("Swiper: Autoheight", "axiom"),
							"desc" => __("Change whole slider's height (make it equal current slide's height)", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => __("Swiper: Slides change interval", "axiom"),
							"desc" => __("Slides change interval (in milliseconds: 1000ms = 1s)", "axiom"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 5000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)),
					"children" => array(
						"name" => "trx_slider_item",
						"title" => __("Slide", "axiom"),
						"desc" => __("Slider item", "axiom"),
						"container" => false,
						"params" => array(
							"src" => array(
								"title" => __("URL (source) for image file", "axiom"),
								"desc" => __("Select or upload image or write URL from other site for the current slide", "axiom"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"id" => $AXIOM_GLOBALS['sc_params']['id'],
							"class" => $AXIOM_GLOBALS['sc_params']['class'],
							"css" => $AXIOM_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Socials
				"trx_socials" => array(
					"title" => __("Social icons", "axiom"),
					"desc" => __("List of social icons (with hovers)", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"size" => array(
							"title" => __("Icon's size", "axiom"),
							"desc" => __("Size of the icons", "axiom"),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								"tiny" => __('Tiny', 'axiom'),
								"small" => __('Small', 'axiom'),
								"large" => __('Large', 'axiom')
							)
						), 
						"socials" => array(
							"title" => __("Manual socials list", "axiom"),
							"desc" => __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"quantity" => array(
							"title" => __("Socials icons quantity", "axiom"),
							"desc" => __("Limit quantity of shown socials icons. Leave blank to show all.", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"custom" => array(
							"title" => __("Custom socials", "axiom"),
							"desc" => __("Make custom icons from inner shortcodes (prepare it on tabs)", "axiom"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_social_item",
						"title" => __("Custom social item", "axiom"),
						"desc" => __("Custom social item: name, profile url and icon url", "axiom"),
						"decorate" => false,
						"container" => false,
						"params" => array(
							"name" => array(
								"title" => __("Social name", "axiom"),
								"desc" => __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"url" => array(
								"title" => __("Your profile URL", "axiom"),
								"desc" => __("URL of your profile in specified social network", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => __("URL (source) for icon file", "axiom"),
								"desc" => __("Select or upload image or write URL from other site for the current social icon", "axiom"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							)
						)
					)
				),
			
			
			
			
				// Table
				"trx_table" => array(
					"title" => __("Table", "axiom"),
					"desc" => __("Insert a table into post (page). ", "axiom"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"align" => array(
							"title" => __("Content alignment", "axiom"),
							"desc" => __("Select alignment for each table cell", "axiom"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"_content_" => array(
							"title" => __("Table content", "axiom"),
							"desc" => __("Content, created with any table-generator", "axiom"),
							"divider" => true,
							"rows" => 8,
							"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
							"type" => "textarea"
						),
						"width" => axiom_shortcodes_width(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Tabs
				"trx_tabs" => array(
					"title" => __("Tabs", "axiom"),
					"desc" => __("Insert tabs in your page (post)", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Tabs style", "axiom"),
							"desc" => __("Select style for tabs items", "axiom"),
							"value" => 1,
							"options" => array(
								1 => __('Style 1', 'axiom'),
								2 => __('Style 2', 'axiom')
							),
							"type" => "radio"
						),
						"initial" => array(
							"title" => __("Initially opened tab", "axiom"),
							"desc" => __("Number of initially opened tab", "axiom"),
							"divider" => true,
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"scroll" => array(
							"title" => __("Use scroller", "axiom"),
							"desc" => __("Use scroller to show tab content (height parameter required)", "axiom"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_tab",
						"title" => __("Tab", "axiom"),
						"desc" => __("Tab item", "axiom"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => __("Tab title", "axiom"),
								"desc" => __("Current tab title", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => __("Tab content", "axiom"),
								"desc" => __("Current tab content", "axiom"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOM_GLOBALS['sc_params']['id'],
							"class" => $AXIOM_GLOBALS['sc_params']['class'],
							"css" => $AXIOM_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Team
				"trx_team" => array(
					"title" => __("Team", "axiom"),
					"desc" => __("Insert team in your page (post)", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Team style", "axiom"),
							"desc" => __("Select style to display team members", "axiom"),
							"value" => "1",
							"type" => "select",
							"options" => array(
								1 => __('Style 1', 'axiom'),
								2 => __('Style 2', 'axiom')
							)
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns use to show team members", "axiom"),
							"value" => 3,
							"min" => 2,
							"max" => 5,
							"step" => 1,
							"type" => "spinner"
						),
						"custom" => array(
							"title" => __("Custom", "axiom"),
							"desc" => __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "axiom"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => __("Categories", "axiom"),
							"desc" => __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", "axiom"),
							"dependency" => array(
								'custom' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $AXIOM_GLOBALS['sc_params']['team_groups']
						),
						"count" => array(
							"title" => __("Number of posts", "axiom"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiom"),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Offset before select posts", "axiom"),
							"desc" => __("Skip posts before select next part.", "axiom"),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Post order by", "axiom"),
							"desc" => __("Select desired posts sorting method", "axiom"),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => "title",
							"type" => "select",
							"options" => $AXIOM_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Post order", "axiom"),
							"desc" => __("Select desired posts order", "axiom"),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => __("Post IDs list", "axiom"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiom"),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => "",
							"type" => "text"
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_team_item",
						"title" => __("Member", "axiom"),
						"desc" => __("Team member", "axiom"),
						"container" => true,
						"params" => array(
							"user" => array(
								"title" => __("Registerd user", "axiom"),
								"desc" => __("Select one of registered users (if present) or put name, position, etc. in fields below", "axiom"),
								"value" => "",
								"type" => "select",
								"options" => $AXIOM_GLOBALS['sc_params']['users']
							),
							"member" => array(
								"title" => __("Team member", "axiom"),
								"desc" => __("Select one of team members (if present) or put name, position, etc. in fields below", "axiom"),
								"value" => "",
								"type" => "select",
								"options" => $AXIOM_GLOBALS['sc_params']['members']
							),
							"link" => array(
								"title" => __("Link", "axiom"),
								"desc" => __("Link on team member's personal page", "axiom"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"name" => array(
								"title" => __("Name", "axiom"),
								"desc" => __("Team member's name", "axiom"),
								"divider" => true,
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => __("Position", "axiom"),
								"desc" => __("Team member's position", "axiom"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => __("E-mail", "axiom"),
								"desc" => __("Team member's e-mail", "axiom"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => __("Photo", "axiom"),
								"desc" => __("Team member's photo (avatar)", "axiom"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"socials" => array(
								"title" => __("Socials", "axiom"),
								"desc" => __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "axiom"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => __("Description", "axiom"),
								"desc" => __("Team member's short description", "axiom"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOM_GLOBALS['sc_params']['id'],
							"class" => $AXIOM_GLOBALS['sc_params']['class'],
							"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
							"css" => $AXIOM_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Testimonials
				"trx_testimonials" => array(
					"title" => __("Testimonials", "axiom"),
					"desc" => __("Insert testimonials into post (page)", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"controls" => array(
							"title" => __("Show arrows", "axiom"),
							"desc" => __("Show control buttons", "axiom"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"controls_top" => array(
							"title" => __("Arrows at top position", "axiom"),
							"desc" => __("Show control buttons at top position? Default - middle position", "axiom"),
							"dependency" => array(
								'controls' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => __("Testimonials change interval", "axiom"),
							"desc" => __("Testimonials change interval (in milliseconds: 1000ms = 1s)", "axiom"),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"align" => array(
							"title" => __("Alignment", "axiom"),
							"desc" => __("Alignment of the testimonials block", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => __("Autoheight", "axiom"),
							"desc" => __("Change whole slider's height (make it equal current slide's height)", "axiom"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"custom" => array(
							"title" => __("Custom", "axiom"),
							"desc" => __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "axiom"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => __("Categories", "axiom"),
							"desc" => __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "axiom"),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $AXIOM_GLOBALS['sc_params']['testimonials_groups']
						),
						"count" => array(
							"title" => __("Number of posts", "axiom"),
							"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiom"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => __("Offset before select posts", "axiom"),
							"desc" => __("Skip posts before select next part.", "axiom"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Post order by", "axiom"),
							"desc" => __("Select desired posts sorting method", "axiom"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => $AXIOM_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => __("Post order", "axiom"),
							"desc" => __("Select desired posts order", "axiom"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => __("Post IDs list", "axiom"),
							"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiom"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_tint" => array(
							"title" => __("Background tint", "axiom"),
							"desc" => __("Main background tint: dark or light", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => __("Background color", "axiom"),
							"desc" => __("Any background color for this section", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "axiom"),
							"desc" => __("Select or upload image or write URL from other site for the background", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "axiom"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "axiom"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "axiom"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "axiom"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_testimonials_item",
						"title" => __("Item", "axiom"),
						"desc" => __("Testimonials item", "axiom"),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => __("Author", "axiom"),
								"desc" => __("Name of the testimonmials author", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => __("Link", "axiom"),
								"desc" => __("Link URL to the testimonmials author page", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => __("E-mail", "axiom"),
								"desc" => __("E-mail of the testimonmials author (to get gravatar)", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => __("Photo", "axiom"),
								"desc" => __("Select or upload photo of testimonmials author or write URL of photo from other site", "axiom"),
								"value" => "",
								"type" => "hidden" //media
							),
							"_content_" => array(
								"title" => __("Testimonials text", "axiom"),
								"desc" => __("Current testimonials text", "axiom"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOM_GLOBALS['sc_params']['id'],
							"class" => $AXIOM_GLOBALS['sc_params']['class'],
							"css" => $AXIOM_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Title
				"trx_title" => array(
					"title" => __("Title", "axiom"),
					"desc" => __("Create header tag (1-6 level) with many styles", "axiom"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => __("Title content", "axiom"),
							"desc" => __("Title content", "axiom"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"type" => array(
							"title" => __("Title type", "axiom"),
							"desc" => __("Title type (header level)", "axiom"),
							"divider" => true,
							"value" => "1",
							"type" => "select",
							"options" => array(
								'1' => __('Header 1', 'axiom'),
								'2' => __('Header 2', 'axiom'),
								'3' => __('Header 3', 'axiom'),
								'4' => __('Header 4', 'axiom'),
								'5' => __('Header 5', 'axiom'),
								'6' => __('Header 6', 'axiom'),
							)
						),
						"h1_styling" => array(
							"title" => __('H1 additional style.', "axiom"),
							"desc" => __("Select H1 additional style. Top, bottom or none.", "axiom"),
							"dependency" => array(
								'type' => array('1')
							),
							"readonly" => false,
							"value" => "0",
							"type" => "select",
							"options" => array(
								'0' => __('None', 'axiom'),
								'1' => __('Line on the left', 'axiom')
							)
						),
						"style" => array(
							"title" => __("Title style", "axiom"),
							"desc" => __("Title style", "axiom"),
							"value" => "regular",
							"type" => "select",
							"options" => array(
								'regular' => __('Regular', 'axiom'),
								'underline' => __('Underline', 'axiom'),
								'divider' => __('Divider', 'axiom'),
								'iconed' => __('With icon (image)', 'axiom')
							)
						),
						"align" => array(
							"title" => __("Alignment", "axiom"),
							"desc" => __("Title text alignment", "axiom"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						), 
						"font_size" => array(
							"title" => __("Font_size", "axiom"),
							"desc" => __("Custom font size. If empty - use theme default", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => __("Font weight", "axiom"),
							"desc" => __("Custom font weight. If empty or inherit - use theme default", "axiom"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'inherit' => __('Default', 'axiom'),
								'100' => __('Thin (100)', 'axiom'),
								'300' => __('Light (300)', 'axiom'),
								'400' => __('Normal (400)', 'axiom'),
								'600' => __('Semibold (600)', 'axiom'),
								'700' => __('Bold (700)', 'axiom'),
								'900' => __('Black (900)', 'axiom')
							)
						),
						"color" => array(
							"title" => __("Title color", "axiom"),
							"desc" => __("Select color for the title", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"link" => array(
							"title" => __('Title link',  'axiom'),
							"desc" => __("Insert title link if needed",  'axiom'),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => __('Title font icon',  'axiom'),
							"desc" => __("Select font icon for the title from Fontello icons set (if style=iconed)",  'axiom'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"image" => array(
							"title" => __('or image icon',  'axiom'),
							"desc" => __("Select image icon for the title instead icon above (if style=iconed)",  'axiom'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "images",
							"size" => "small",
							"options" => $AXIOM_GLOBALS['sc_params']['images']
						),
						"picture" => array(
							"title" => __('or URL for image file', "axiom"),
							"desc" => __("Select or upload image or write URL from other site (if style=iconed)", "axiom"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"image_size" => array(
							"title" => __('Image (picture) size', "axiom"),
							"desc" => __("Select image (picture) size (if style='iconed')", "axiom"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								'small' => __('Small', 'axiom'),
								'medium' => __('Medium', 'axiom'),
								'large' => __('Large', 'axiom')
							)
						),
						"position" => array(
							"title" => __('Icon (image) position', "axiom"),
							"desc" => __("Select icon (image) position (if style=iconed)", "axiom"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "left",
							"type" => "checklist",
							"options" => array(
								'top' => __('Top', 'axiom'),
								'left' => __('Left', 'axiom')
							)
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Toggles
				"trx_toggles" => array(
					"title" => __("Toggles", "axiom"),
					"desc" => __("Toggles items", "axiom"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => __("Toggles style", "axiom"),
							"desc" => __("Select style for display toggles", "axiom"),
							"value" => 1,
							"options" => array(
								1 => __('Style 1', 'axiom'),
								2 => __('Style 2', 'axiom')
							),
							"type" => "radio"
						),
						"counter" => array(
							"title" => __("Counter", "axiom"),
							"desc" => __("Display counter before each toggles title", "axiom"),
							"value" => "off",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['on_off']
						),
						"icon_closed" => array(
							"title" => __("Icon while closed",  'axiom'),
							"desc" => __('Select icon for the closed toggles item from Fontello icons set',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => __("Icon while opened",  'axiom'),
							"desc" => __('Select icon for the opened toggles item from Fontello icons set',  'axiom'),
							"value" => "",
							"type" => "icons",
							"options" => $AXIOM_GLOBALS['sc_params']['icons']
						),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_toggles_item",
						"title" => __("Toggles item", "axiom"),
						"desc" => __("Toggles item", "axiom"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => __("Toggles item title", "axiom"),
								"desc" => __("Title for current toggles item", "axiom"),
								"value" => "",
								"type" => "text"
							),
							"open" => array(
								"title" => __("Open on show", "axiom"),
								"desc" => __("Open current toggles item on show", "axiom"),
								"value" => "no",
								"type" => "switch",
								"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
							),
							"icon_closed" => array(
								"title" => __("Icon while closed",  'axiom'),
								"desc" => __('Select icon for the closed toggles item from Fontello icons set',  'axiom'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOM_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => __("Icon while opened",  'axiom'),
								"desc" => __('Select icon for the opened toggles item from Fontello icons set',  'axiom'),
								"value" => "",
								"type" => "icons",
								"options" => $AXIOM_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => __("Toggles item content", "axiom"),
								"desc" => __("Current toggles item content", "axiom"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $AXIOM_GLOBALS['sc_params']['id'],
							"class" => $AXIOM_GLOBALS['sc_params']['class'],
							"css" => $AXIOM_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Tooltip
				"trx_tooltip" => array(
					"title" => __("Tooltip", "axiom"),
					"desc" => __("Create tooltip for selected text", "axiom"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => __("Title", "axiom"),
							"desc" => __("Tooltip title (required)", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => __("Tipped content", "axiom"),
							"desc" => __("Highlighted content with tooltip", "axiom"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Twitter
				"trx_twitter" => array(
					"title" => __("Twitter", "axiom"),
					"desc" => __("Insert twitter feed into post (page)", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"user" => array(
							"title" => __("Twitter Username", "axiom"),
							"desc" => __("Your username in the twitter account. If empty - get it from Theme Options.", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"consumer_key" => array(
							"title" => __("Consumer Key", "axiom"),
							"desc" => __("Consumer Key from the twitter account", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"consumer_secret" => array(
							"title" => __("Consumer Secret", "axiom"),
							"desc" => __("Consumer Secret from the twitter account", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"token_key" => array(
							"title" => __("Token Key", "axiom"),
							"desc" => __("Token Key from the twitter account", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"token_secret" => array(
							"title" => __("Token Secret", "axiom"),
							"desc" => __("Token Secret from the twitter account", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"count" => array(
							"title" => __("Tweets number", "axiom"),
							"desc" => __("Tweets number to show", "axiom"),
							"divider" => true,
							"value" => 3,
							"max" => 20,
							"min" => 1,
							"type" => "spinner"
						),
						"controls" => array(
							"title" => __("Show arrows", "axiom"),
							"desc" => __("Show control buttons", "axiom"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => __("Tweets change interval", "axiom"),
							"desc" => __("Tweets change interval (in milliseconds: 1000ms = 1s)", "axiom"),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"align" => array(
							"title" => __("Alignment", "axiom"),
							"desc" => __("Alignment of the tweets block", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => __("Autoheight", "axiom"),
							"desc" => __("Change whole slider's height (make it equal current slide's height)", "axiom"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						),
						"bg_tint" => array(
							"title" => __("Background tint", "axiom"),
							"desc" => __("Main background tint: dark or light", "axiom"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"options" => $AXIOM_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => __("Background color", "axiom"),
							"desc" => __("Any background color for this section", "axiom"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => __("Background image URL", "axiom"),
							"desc" => __("Select or upload image or write URL from other site for the background", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => __("Overlay", "axiom"),
							"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "axiom"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => __("Texture", "axiom"),
							"desc" => __("Predefined texture style from 1 to 11. 0 - without texture.", "axiom"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Video
				"trx_video" => array(
					"title" => __("Video", "axiom"),
					"desc" => __("Insert video player", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => __("URL for video file", "axiom"),
							"desc" => __("Select video from media library or paste URL for video file from other site", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose video', 'axiom'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose video file', 'axiom'),
									'update' => __('Select video file', 'axiom')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"ratio" => array(
							"title" => __("Ratio", "axiom"),
							"desc" => __("Ratio of the video", "axiom"),
							"value" => "16:9",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"16:9" => __("16:9", 'axiom'),
								"4:3" => __("4:3", 'axiom')
							)
						),
						"autoplay" => array(
							"title" => __("Autoplay video", "axiom"),
							"desc" => __("Autoplay video on page load", "axiom"),
							"value" => "off",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => __("Align", "axiom"),
							"desc" => __("Select block alignment", "axiom"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['align']
						),
						"image" => array(
							"title" => __("Cover image", "axiom"),
							"desc" => __("Select or upload image or write URL from other site for video preview", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image" => array(
							"title" => __("Background image", "axiom"),
							"desc" => __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "axiom"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => __("Top offset", "axiom"),
							"desc" => __("Top offset (padding) inside background image to video block (in percent). For example: 3%", "axiom"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => __("Bottom offset", "axiom"),
							"desc" => __("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", "axiom"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => __("Left offset", "axiom"),
							"desc" => __("Left offset (padding) inside background image to video block (in percent). For example: 20%", "axiom"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => __("Right offset", "axiom"),
							"desc" => __("Right offset (padding) inside background image to video block (in percent). For example: 12%", "axiom"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Zoom
				"trx_zoom" => array(
					"title" => __("Zoom", "axiom"),
					"desc" => __("Insert the image with zoom/lens effect", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"effect" => array(
							"title" => __("Effect", "axiom"),
							"desc" => __("Select effect to display overlapping image", "axiom"),
							"value" => "lens",
							"size" => "medium",
							"type" => "switch",
							"options" => array(
								"lens" => __('Lens', 'axiom'),
								"zoom" => __('Zoom', 'axiom')
							)
						),
						"url" => array(
							"title" => __("Main image", "axiom"),
							"desc" => __("Select or upload main image", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"over" => array(
							"title" => __("Overlaping image", "axiom"),
							"desc" => __("Select or upload overlaping image", "axiom"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"align" => array(
							"title" => __("Float zoom", "axiom"),
							"desc" => __("Float zoom to left or right side", "axiom"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $AXIOM_GLOBALS['sc_params']['float']
						), 
						"bg_image" => array(
							"title" => __("Background image", "axiom"),
							"desc" => __("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", "axiom"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => __("Top offset", "axiom"),
							"desc" => __("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", "axiom"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => __("Bottom offset", "axiom"),
							"desc" => __("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", "axiom"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => __("Left offset", "axiom"),
							"desc" => __("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", "axiom"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => __("Right offset", "axiom"),
							"desc" => __("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", "axiom"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => axiom_shortcodes_width(),
						"height" => axiom_shortcodes_height(),
						"top" => $AXIOM_GLOBALS['sc_params']['top'],
						"bottom" => $AXIOM_GLOBALS['sc_params']['bottom'],
						"left" => $AXIOM_GLOBALS['sc_params']['left'],
						"right" => $AXIOM_GLOBALS['sc_params']['right'],
						"id" => $AXIOM_GLOBALS['sc_params']['id'],
						"class" => $AXIOM_GLOBALS['sc_params']['class'],
						"animation" => $AXIOM_GLOBALS['sc_params']['animation'],
						"css" => $AXIOM_GLOBALS['sc_params']['css']
					)
				)
			);
	
			// Woocommerce Shortcodes list
			//------------------------------------------------------------------
			if (axiom_exists_woocommerce()) {
				
				// WooCommerce - Cart
				$AXIOM_GLOBALS['shortcodes']["woocommerce_cart"] = array(
					"title" => __("Woocommerce: Cart", "axiom"),
					"desc" => __("WooCommerce shortcode: show Cart page", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Checkout
				$AXIOM_GLOBALS['shortcodes']["woocommerce_checkout"] = array(
					"title" => __("Woocommerce: Checkout", "axiom"),
					"desc" => __("WooCommerce shortcode: show Checkout page", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - My Account
				$AXIOM_GLOBALS['shortcodes']["woocommerce_my_account"] = array(
					"title" => __("Woocommerce: My Account", "axiom"),
					"desc" => __("WooCommerce shortcode: show My Account page", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Order Tracking
				$AXIOM_GLOBALS['shortcodes']["woocommerce_order_tracking"] = array(
					"title" => __("Woocommerce: Order Tracking", "axiom"),
					"desc" => __("WooCommerce shortcode: show Order Tracking page", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Shop Messages
				$AXIOM_GLOBALS['shortcodes']["shop_messages"] = array(
					"title" => __("Woocommerce: Shop Messages", "axiom"),
					"desc" => __("WooCommerce shortcode: show shop messages", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Product Page
				$AXIOM_GLOBALS['shortcodes']["product_page"] = array(
					"title" => __("Woocommerce: Product Page", "axiom"),
					"desc" => __("WooCommerce shortcode: display single product page", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => __("SKU", "axiom"),
							"desc" => __("SKU code of displayed product", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => __("ID", "axiom"),
							"desc" => __("ID of displayed product", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"posts_per_page" => array(
							"title" => __("Number", "axiom"),
							"desc" => __("How many products showed", "axiom"),
							"value" => "1",
							"min" => 1,
							"type" => "spinner"
						),
						"post_type" => array(
							"title" => __("Post type", "axiom"),
							"desc" => __("Post type for the WP query (leave 'product')", "axiom"),
							"value" => "product",
							"type" => "text"
						),
						"post_status" => array(
							"title" => __("Post status", "axiom"),
							"desc" => __("Display posts only with this status", "axiom"),
							"value" => "publish",
							"type" => "select",
							"options" => array(
								"publish" => __('Publish', 'axiom'),
								"protected" => __('Protected', 'axiom'),
								"private" => __('Private', 'axiom'),
								"pending" => __('Pending', 'axiom'),
								"draft" => __('Draft', 'axiom')
							)
						)
					)
				);
				
				// WooCommerce - Product
				$AXIOM_GLOBALS['shortcodes']["product"] = array(
					"title" => __("Woocommerce: Product", "axiom"),
					"desc" => __("WooCommerce shortcode: display one product", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => __("SKU", "axiom"),
							"desc" => __("SKU code of displayed product", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => __("ID", "axiom"),
							"desc" => __("ID of displayed product", "axiom"),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Best Selling Products
				$AXIOM_GLOBALS['shortcodes']["best_selling_products"] = array(
					"title" => __("Woocommerce: Best Selling Products", "axiom"),
					"desc" => __("WooCommerce shortcode: show best selling products", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiom"),
							"desc" => __("How many products showed", "axiom"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns per row use for products output", "axiom"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						)
					)
				);
				
				// WooCommerce - Recent Products
				$AXIOM_GLOBALS['shortcodes']["recent_products"] = array(
					"title" => __("Woocommerce: Recent Products", "axiom"),
					"desc" => __("WooCommerce shortcode: show recent products", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiom"),
							"desc" => __("How many products showed", "axiom"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns per row use for products output", "axiom"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiom'),
								"title" => __('Title', 'axiom')
							)
						),
						"order" => array(
							"title" => __("Order", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Related Products
				$AXIOM_GLOBALS['shortcodes']["related_products"] = array(
					"title" => __("Woocommerce: Related Products", "axiom"),
					"desc" => __("WooCommerce shortcode: show related products", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"posts_per_page" => array(
							"title" => __("Number", "axiom"),
							"desc" => __("How many products showed", "axiom"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns per row use for products output", "axiom"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiom'),
								"title" => __('Title', 'axiom')
							)
						)
					)
				);
				
				// WooCommerce - Featured Products
				$AXIOM_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => __("Woocommerce: Featured Products", "axiom"),
					"desc" => __("WooCommerce shortcode: show featured products", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiom"),
							"desc" => __("How many products showed", "axiom"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns per row use for products output", "axiom"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiom'),
								"title" => __('Title', 'axiom')
							)
						),
						"order" => array(
							"title" => __("Order", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Top Rated Products
				$AXIOM_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => __("Woocommerce: Top Rated Products", "axiom"),
					"desc" => __("WooCommerce shortcode: show top rated products", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiom"),
							"desc" => __("How many products showed", "axiom"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns per row use for products output", "axiom"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiom'),
								"title" => __('Title', 'axiom')
							)
						),
						"order" => array(
							"title" => __("Order", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Sale Products
				$AXIOM_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => __("Woocommerce: Sale Products", "axiom"),
					"desc" => __("WooCommerce shortcode: list products on sale", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiom"),
							"desc" => __("How many products showed", "axiom"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns per row use for products output", "axiom"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiom'),
								"title" => __('Title', 'axiom')
							)
						),
						"order" => array(
							"title" => __("Order", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product Category
				$AXIOM_GLOBALS['shortcodes']["product_category"] = array(
					"title" => __("Woocommerce: Products from category", "axiom"),
					"desc" => __("WooCommerce shortcode: list products in specified category(-ies)", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiom"),
							"desc" => __("How many products showed", "axiom"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns per row use for products output", "axiom"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiom'),
								"title" => __('Title', 'axiom')
							)
						),
						"order" => array(
							"title" => __("Order", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						),
						"category" => array(
							"title" => __("Categories", "axiom"),
							"desc" => __("Comma separated category slugs", "axiom"),
							"value" => '',
							"type" => "text"
						),
						"operator" => array(
							"title" => __("Operator", "axiom"),
							"desc" => __("Categories operator", "axiom"),
							"value" => "IN",
							"type" => "checklist",
							"size" => "medium",
							"options" => array(
								"IN" => __('IN', 'axiom'),
								"NOT IN" => __('NOT IN', 'axiom'),
								"AND" => __('AND', 'axiom')
							)
						)
					)
				);
				
				// WooCommerce - Products
				$AXIOM_GLOBALS['shortcodes']["products"] = array(
					"title" => __("Woocommerce: Products", "axiom"),
					"desc" => __("WooCommerce shortcode: list all products", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"skus" => array(
							"title" => __("SKUs", "axiom"),
							"desc" => __("Comma separated SKU codes of products", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => __("IDs", "axiom"),
							"desc" => __("Comma separated ID of products", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns per row use for products output", "axiom"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiom'),
								"title" => __('Title', 'axiom')
							)
						),
						"order" => array(
							"title" => __("Order", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product attribute
				$AXIOM_GLOBALS['shortcodes']["product_attribute"] = array(
					"title" => __("Woocommerce: Products by Attribute", "axiom"),
					"desc" => __("WooCommerce shortcode: show products with specified attribute", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => __("Number", "axiom"),
							"desc" => __("How many products showed", "axiom"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns per row use for products output", "axiom"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiom'),
								"title" => __('Title', 'axiom')
							)
						),
						"order" => array(
							"title" => __("Order", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						),
						"attribute" => array(
							"title" => __("Attribute", "axiom"),
							"desc" => __("Attribute name", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"filter" => array(
							"title" => __("Filter", "axiom"),
							"desc" => __("Attribute value", "axiom"),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Products Categories
				$AXIOM_GLOBALS['shortcodes']["product_categories"] = array(
					"title" => __("Woocommerce: Product Categories", "axiom"),
					"desc" => __("WooCommerce shortcode: show categories with products", "axiom"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"number" => array(
							"title" => __("Number", "axiom"),
							"desc" => __("How many categories showed", "axiom"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => __("Columns", "axiom"),
							"desc" => __("How many columns per row use for categories output", "axiom"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => __("Order by", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'axiom'),
								"title" => __('Title', 'axiom')
							)
						),
						"order" => array(
							"title" => __("Order", "axiom"),
							"desc" => __("Sorting order for products output", "axiom"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $AXIOM_GLOBALS['sc_params']['ordering']
						),
						"parent" => array(
							"title" => __("Parent", "axiom"),
							"desc" => __("Parent category slug", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => __("IDs", "axiom"),
							"desc" => __("Comma separated ID of products", "axiom"),
							"value" => "",
							"type" => "text"
						),
						"hide_empty" => array(
							"title" => __("Hide empty", "axiom"),
							"desc" => __("Hide empty categories", "axiom"),
							"value" => "yes",
							"type" => "switch",
							"options" => $AXIOM_GLOBALS['sc_params']['yes_no']
						)
					)
				);

			}
			
			do_action('axiom_action_shortcodes_list');

		}
	}
}
?>