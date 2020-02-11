<?php

// Width and height params
if ( !function_exists( 'axiom_vc_width' ) ) {
	function axiom_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => __("Width", "axiom"),
			"description" => __("Width (in pixels or percent) of the current element", "axiom"),
			"group" => __('Size &amp; Margins', 'axiom'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'axiom_vc_height' ) ) {
	function axiom_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => __("Height", "axiom"),
			"description" => __("Height (only in pixels) of the current element", "axiom"),
			"group" => __('Size &amp; Margins', 'axiom'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'axiom_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'axiom_shortcodes_vc_scripts_admin' );
	function axiom_shortcodes_vc_scripts_admin() {
		// Include CSS 
		axiom_enqueue_style ( 'shortcodes_vc-style', axiom_get_file_url('shortcodes/shortcodes_vc_admin.css'), array(), null );
		// Include JS
		axiom_enqueue_script( 'shortcodes_vc-script', axiom_get_file_url('shortcodes/shortcodes_vc_admin.js'), array(), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'axiom_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'axiom_shortcodes_vc_scripts_front' );
	function axiom_shortcodes_vc_scripts_front() {
		if (axiom_vc_is_frontend()) {
			// Include CSS 
			axiom_enqueue_style ( 'shortcodes_vc-style', axiom_get_file_url('shortcodes/shortcodes_vc_front.css'), array(), null );
			// Include JS
			axiom_enqueue_script( 'shortcodes_vc-script', axiom_get_file_url('shortcodes/shortcodes_vc_front.js'), array(), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'axiom_shortcodes_vc_add_init_script' ) ) {
	//add_filter('axiom_shortcode_output', 'axiom_shortcodes_vc_add_init_script', 10, 4);
	function axiom_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (axiom_strpos($output, 'axiom_vc_init_shortcodes')===false) {
				$id = "axiom_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							axiom_init_post_formats();
							axiom_init_shortcodes(jQuery("body").eq(0));
							axiom_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_shortcodes_vc_theme_setup' ) ) {
	//if ( axiom_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'axiom_action_before_init_theme', 'axiom_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'axiom_action_after_init_theme', 'axiom_shortcodes_vc_theme_setup' );
	function axiom_shortcodes_vc_theme_setup() {
		if (axiom_shortcodes_is_used()) {
			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'axiom_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'axiom_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('axiom_shortcode_output', 'axiom_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
			vc_remove_element("vc_button");
			vc_remove_element("vc_posts_slider");
			vc_remove_element("vc_gmaps");
			vc_remove_element("vc_teaser_grid");
			vc_remove_element("vc_progress_bar");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_tweetmeme");
			vc_remove_element("vc_googleplus");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_pinterest");
			vc_remove_element("vc_message");
			vc_remove_element("vc_posts_grid");
			vc_remove_element("vc_carousel");
			vc_remove_element("vc_flickr");
			vc_remove_element("vc_tour");
			vc_remove_element("vc_separator");
			vc_remove_element("vc_single_image");
			vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			vc_remove_element("vc_toggle");
			vc_remove_element("vc_tabs");
			vc_remove_element("vc_tab");
			vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			global $AXIOM_GLOBALS;
			
			$AXIOM_GLOBALS['vc_params'] = array(
				
				// Common arrays and strings
				'category' => __("AxiomThemes shortcodes", "axiom"),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => __("Element ID", "axiom"),
					"description" => __("ID for current element", "axiom"),
					"group" => __('Size &amp; Margins', 'axiom'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => __("Element CSS class", "axiom"),
					"description" => __("CSS class for current element", "axiom"),
					"group" => __('Size &amp; Margins', 'axiom'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => __("Animation", "axiom"),
					"description" => __("Select animation while object enter in the visible area of page", "axiom"),
					"class" => "",
					"value" => array_flip($AXIOM_GLOBALS['sc_params']['animations']),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => __("CSS styles", "axiom"),
					"description" => __("Any additional CSS rules (if need)", "axiom"),
					"group" => __('Size &amp; Margins', 'axiom'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => __("Top margin", "axiom"),
					"description" => __("Top margin (in pixels).", "axiom"),
					"group" => __('Size &amp; Margins', 'axiom'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => __("Bottom margin", "axiom"),
					"description" => __("Bottom margin (in pixels).", "axiom"),
					"group" => __('Size &amp; Margins', 'axiom'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => __("Left margin", "axiom"),
					"description" => __("Left margin (in pixels).", "axiom"),
					"group" => __('Size &amp; Margins', 'axiom'),
					"value" => "",
					"type" => "textfield"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => __("Right margin", "axiom"),
					"description" => __("Right margin (in pixels).", "axiom"),
					"group" => __('Size &amp; Margins', 'axiom'),
					"value" => "",
					"type" => "textfield"
				)
			);
	
	
	
			// Accordion
			//-------------------------------------------------------------------------------------
			vc_map( array(
				"base" => "trx_accordion",
				"name" => __("Accordion", "axiom"),
				"description" => __("Accordion items", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_accordion',
				"class" => "trx_sc_collection trx_sc_accordion",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Accordion style", "axiom"),
						"description" => __("Select style for display accordion", "axiom"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							__('Style 1', 'axiom') => 1,
							__('Style 2', 'axiom') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => __("Counter", "axiom"),
						"description" => __("Display counter before each accordion title", "axiom"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "initial",
						"heading" => __("Initially opened item", "axiom"),
						"description" => __("Number of initially opened item", "axiom"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "axiom"),
						"description" => __("Select icon for the closed accordion item from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "axiom"),
						"description" => __("Select icon for the opened accordion item from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_accordion_item title="' . __( 'Item 1 title', 'axiom' ) . '"][/trx_accordion_item]
					[trx_accordion_item title="' . __( 'Item 2 title', 'axiom' ) . '"][/trx_accordion_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", "axiom").'">'.__("Add item", "axiom").'</button>
					</div>
				',
				'js_view' => 'VcTrxAccordionView'
			) );
			
			
			vc_map( array(
				"base" => "trx_accordion_item",
				"name" => __("Accordion item", "axiom"),
				"description" => __("Inner accordion item", "axiom"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_accordion_item',
				"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_accordion'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiom"),
						"description" => __("Title for current accordion item", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "axiom"),
						"description" => __("Select icon for the closed accordion item from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "axiom"),
						"description" => __("Select icon for the opened accordion item from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxAccordionTabView'
			) );

			class WPBakeryShortCode_Trx_Accordion extends AXIOM_VC_ShortCodeAccordion {}
			class WPBakeryShortCode_Trx_Accordion_Item extends AXIOM_VC_ShortCodeAccordionItem {}
			
			
			
			
			
			
			// Anchor
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_anchor",
				"name" => __("Anchor", "axiom"),
				"description" => __("Insert anchor for the TOC (table of content)", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_anchor',
				"class" => "trx_sc_single trx_sc_anchor",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => __("Anchor's icon", "axiom"),
						"description" => __("Select icon for the anchor from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Short title", "axiom"),
						"description" => __("Short title of the anchor (for the table of content)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Long description", "axiom"),
						"description" => __("Description for the popup (then hover on the icon). You can use '{' and '}' - make the text italic, '|' - insert line break", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => __("External URL", "axiom"),
						"description" => __("External URL for this TOC item", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "separator",
						"heading" => __("Add separator", "axiom"),
						"description" => __("Add separator under item in the TOC", "axiom"),
						"class" => "",
						"value" => array("Add separator" => "yes" ),
						"type" => "checkbox"
					),
					$AXIOM_GLOBALS['vc_params']['id']
				),
			) );
			
			class WPBakeryShortCode_Trx_Anchor extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Audio
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_audio",
				"name" => __("Audio", "axiom"),
				"description" => __("Insert audio player", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_audio',
				"class" => "trx_sc_single trx_sc_audio",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("URL for audio file", "axiom"),
						"description" => __("Put here URL for audio file", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => __("Cover image", "axiom"),
						"description" => __("Select or upload image or write URL from other site for audio cover", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiom"),
						"description" => __("Title of the audio file", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "author",
						"heading" => __("Author", "axiom"),
						"description" => __("Author of the audio file", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Controls", "axiom"),
						"description" => __("Show/hide controls", "axiom"),
						"class" => "",
						"value" => array("Hide controls" => "hide" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoplay",
						"heading" => __("Autoplay", "axiom"),
						"description" => __("Autoplay audio on page load", "axiom"),
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Select block alignment", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Audio extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_block",
				"name" => __("Block container", "axiom"),
				"description" => __("Container for any block ([section] analog - to enable nesting)", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_block',
				"class" => "trx_sc_collection trx_sc_block",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => __("Dedicated", "axiom"),
						"description" => __("Use this block as dedicated content - show it before post title on single page", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Use as dedicated content', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Select block alignment", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns emulation", "axiom"),
						"description" => __("Select width for columns emulation", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => __("Use pan effect", "axiom"),
						"description" => __("Use pan effect to show section content", "axiom"),
						"group" => __('Scroll', 'axiom'),
						"class" => "",
						"value" => array(__('Content scroller', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", "axiom"),
						"description" => __("Use scroller to show section content", "axiom"),
						"group" => __('Scroll', 'axiom'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Content scroller', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => __("Scroll direction", "axiom"),
						"description" => __("Scroll direction (if Use scroller = yes)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"group" => __('Scroll', 'axiom'),
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => __("Scroll controls", "axiom"),
						"description" => __("Show scroll controls (if Use scroller = yes)", "axiom"),
						"class" => "",
						"group" => __('Scroll', 'axiom'),
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", "axiom"),
						"description" => __("Any color for objects in this section", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => __("Background tint", "axiom"),
						"description" => __("Main background tint: dark or light", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_size",
						"heading" => __("Background size", "axiom"),
						"description" => __("Main background size: auto, cover or contain", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['size']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiom"),
						"description" => __("Any background color for this section", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "axiom"),
						"description" => __("Select background image from library for this section", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "axiom"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "axiom"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "axiom"),
						"description" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "axiom"),
						"description" => __("Font weight of the text", "axiom"),
						"class" => "",
						"value" => array(
							__('Default', 'axiom') => 'inherit',
							__('Thin (100)', 'axiom') => '100',
							__('Light (300)', 'axiom') => '300',
							__('Normal (400)', 'axiom') => '400',
							__('Bold (700)', 'axiom') => '700'
						),
						"type" => "dropdown"
					),
/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "axiom"),
						"description" => __("Content for section container", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Block extends AXIOM_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Blogger
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_blogger",
				"name" => __("Blogger", "axiom"),
				"description" => __("Insert posts (pages) in many styles from desired categories or directly from ids", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_blogger',
				"class" => "trx_sc_single trx_sc_blogger",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Output style", "axiom"),
						"description" => __("Select desired style for posts output", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['blogger_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "filters",
						"heading" => __("Show filters", "axiom"),
						"description" => __("Use post's tags or categories as filter buttons", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['filters']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover",
						"heading" => __("Hover effect", "axiom"),
						"description" => __("Select hover effect (only if style=Portfolio)", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['hovers']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover_dir",
						"heading" => __("Hover direction", "axiom"),
						"description" => __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['hovers_dir']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "location",
						"heading" => __("Dedicated content location", "axiom"),
						"description" => __("Select position for dedicated content (only for style=excerpt)", "axiom"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('excerpt')
						),
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['locations']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Posts direction", "axiom"),
						"description" => __("Display posts in horizontal or vertical direction", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "rating",
						"heading" => __("Show rating stars", "axiom"),
						"description" => __("Show rating stars under post's header", "axiom"),
						"group" => __('Details', 'axiom'),
						"class" => "",
						"value" => array(__('Show rating', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "info",
						"heading" => __("Show post info block", "axiom"),
						"description" => __("Show post info block (author, date, tags, etc.)", "axiom"),
						"class" => "",
						"value" => array(__('Show info', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "descr",
						"heading" => __("Description length", "axiom"),
						"description" => __("How many characters are displayed from post excerpt? If 0 - don't show description", "axiom"),
						"group" => __('Details', 'axiom'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => __("Allow links to the post", "axiom"),
						"description" => __("Allow links to the post from each blogger item", "axiom"),
						"group" => __('Details', 'axiom'),
						"class" => "",
						"value" => array(__('Allow links', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "readmore",
						"heading" => __("More link text", "axiom"),
						"description" => __("Read more link text. If empty - show 'More', else - used as link text", "axiom"),
						"group" => __('Details', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => __("Post type", "axiom"),
						"description" => __("Select post type to show", "axiom"),
						"group" => __('Query', 'axiom'),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['posts_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Post IDs list", "axiom"),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiom"),
						"group" => __('Query', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => __("Categories list", "axiom"),
						"description" => __("Put here comma separated category slugs or ids. If empty - show posts from any category or from IDs list", "axiom"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => __('Query', 'axiom'),
						"class" => "",
						"value" => array_flip(axiom_array_merge(array(0 => __('- Select category -', 'axiom')), $AXIOM_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => __("Total posts to show", "axiom"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiom"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"group" => __('Query', 'axiom'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns number", "axiom"),
						"description" => __("How many columns used to display posts?", "axiom"),
						'dependency' => array(
							'element' => 'dir',
							'value' => 'horizontal'
						),
						"group" => __('Query', 'axiom'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Offset before select posts", "axiom"),
						"description" => __("Skip posts before select next part.", "axiom"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => __('Query', 'axiom'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Post order by", "axiom"),
						"description" => __("Select desired posts sorting method", "axiom"),
						"class" => "",
						"group" => __('Query', 'axiom'),
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Post order", "axiom"),
						"description" => __("Select desired posts order", "axiom"),
						"class" => "",
						"group" => __('Query', 'axiom'),
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "only",
						"heading" => __("Select posts only", "axiom"),
						"description" => __("Select posts only with reviews, videos, audios, thumbs or galleries", "axiom"),
						"class" => "",
						"group" => __('Query', 'axiom'),
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['formats']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", "axiom"),
						"description" => __("Use scroller to show all posts", "axiom"),
						"group" => __('Scroll', 'axiom'),
						"class" => "",
						"value" => array(__('Use scroller', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Show slider controls", "axiom"),
						"description" => __("Show arrows to control scroll slider", "axiom"),
						"group" => __('Scroll', 'axiom'),
						"class" => "",
						"value" => array(__('Show controls', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Blogger extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Br
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_br",
				"name" => __("Line break", "axiom"),
				"description" => __("Line break or Clear Floating", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_br',
				"class" => "trx_sc_single trx_sc_br",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "clear",
						"heading" => __("Clear floating", "axiom"),
						"description" => __("Select clear side (if need)", "axiom"),
						"class" => "",
						"value" => "",
						"value" => array(
							__('None', 'axiom') => 'none',
							__('Left', 'axiom') => 'left',
							__('Right', 'axiom') => 'right',
							__('Both', 'axiom') => 'both'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Br extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Button
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_button",
				"name" => __("Button", "axiom"),
				"description" => __("Button with link", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_button',
				"class" => "trx_sc_single trx_sc_button",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => __("Caption", "axiom"),
						"description" => __("Button caption", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => __("Button's shape", "axiom"),
						"description" => __("Select button's shape", "axiom"),
						"class" => "",
						"value" => array(
							__('Square', 'axiom') => 'square',
							__('Round', 'axiom') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Button's style", "axiom"),
						"description" => __("Select button's style", "axiom"),
						"class" => "",
						"value" => array(
							__('Filled', 'axiom') => 'filled',
							__('Border', 'axiom') => 'border'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => __("Button's size", "axiom"),
						"description" => __("Select button's size", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Small', 'axiom') => 'mini',
							__('Medium', 'axiom') => 'medium',
							__('Large', 'axiom') => 'big'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Button's icon", "axiom"),
						"description" => __("Select icon for the title from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_style",
						"heading" => __("Button's color scheme", "axiom"),
						"description" => __("Select button's color scheme", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['button_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Button's text color", "axiom"),
						"description" => __("Any color for button's caption", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Button's backcolor", "axiom"),
						"description" => __("Any color for button's background", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => __("Button's alignment", "axiom"),
						"description" => __("Align button to left, center or right", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "axiom"),
						"description" => __("URL for the link on button click", "axiom"),
						"class" => "",
						"group" => __('Link', 'axiom'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => __("Link target", "axiom"),
						"description" => __("Target for the link on button click", "axiom"),
						"class" => "",
						"group" => __('Link', 'axiom'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "popup",
						"heading" => __("Open link in popup", "axiom"),
						"description" => __("Open link target in popup window", "axiom"),
						"class" => "",
						"group" => __('Link', 'axiom'),
						"value" => array(__('Open in popup', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "rel",
						"heading" => __("Rel attribute", "axiom"),
						"description" => __("Rel attribute for the button's link (if need", "axiom"),
						"class" => "",
						"group" => __('Link', 'axiom'),
						"value" => "",
						"type" => "textfield"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Button extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Chat
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_chat",
				"name" => __("Chat", "axiom"),
				"description" => __("Chat message", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_chat',
				"class" => "trx_sc_container trx_sc_chat",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Item title", "axiom"),
						"description" => __("Title for current chat item", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => __("Item photo", "axiom"),
						"description" => __("Select or upload image or write URL from other site for the item photo (avatar)", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "axiom"),
						"description" => __("URL for the link on chat title click", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => __("Chat item content", "axiom"),
						"description" => __("Current chat item content", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			
			) );
			
			class WPBakeryShortCode_Trx_Chat extends AXIOM_VC_ShortCodeContainer {}
			
			
			
			
			
			
			// Columns
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_columns",
				"name" => __("Columns", "axiom"),
				"description" => __("Insert columns with margins", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_columns',
				"class" => "trx_sc_columns",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_column_item'),
				"params" => array(
					array(
						"param_name" => "count",
						"heading" => __("Columns count", "axiom"),
						"description" => __("Number of the columns in the container.", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "fluid",
						"heading" => __("Fluid columns", "axiom"),
						"description" => __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "axiom"),
						"class" => "",
						"value" => array(__('Fluid columns', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_column_item][/trx_column_item]
					[trx_column_item][/trx_column_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_column_item",
				"name" => __("Column", "axiom"),
				"description" => __("Column item", "axiom"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_column_item',
				"as_child" => array('only' => 'trx_columns'),
				"as_parent" => array('except' => 'trx_columns'),
				"params" => array(
					array(
						"param_name" => "span",
						"heading" => __("Merge columns", "axiom"),
						"description" => __("Count merged columns from current", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Alignment text in the column", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", "axiom"),
						"description" => __("Any color for objects in this column", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiom"),
						"description" => __("Any background color for this column", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("URL for background image file", "axiom"),
						"description" => __("Select or upload image or write URL from other site for the background", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
/*
					array(
						"param_name" => "content",
						"heading" => __("Column's content", "axiom"),
						"description" => __("Content of the current column", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
			class WPBakeryShortCode_Trx_Columns extends AXIOM_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Column_Item extends AXIOM_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Contact form
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_contact_form",
				"name" => __("Contact form", "axiom"),
				"description" => __("Insert contact form", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_contact_form',
				"class" => "trx_sc_collection trx_sc_contact_form",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_form_item'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "axiom"),
						"description" => __("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", "axiom"),
						"class" => "",
						"value" => array(__('Create custom form', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "action",
						"heading" => __("Action", "axiom"),
						"description" => __("Contact form action (URL to handle form data). If empty - use internal action", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Select form alignment", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiom"),
						"description" => __("Title above contact form", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => __("Description (under the title)", "axiom"),
						"description" => __("Contact form description", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					axiom_vc_width(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_form_item",
				"name" => __("Form item (custom field)", "axiom"),
				"description" => __("Custom field for the contact form", "axiom"),
				"class" => "trx_sc_item trx_sc_form_item",
				'icon' => 'icon_trx_form_item',
				"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_contact_form'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => __("Type", "axiom"),
						"description" => __("Select type of the custom field", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['field_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "name",
						"heading" => __("Name", "axiom"),
						"description" => __("Name of the custom field", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => __("Default value", "axiom"),
						"description" => __("Default value of the custom field", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label",
						"heading" => __("Label", "axiom"),
						"description" => __("Label for the custom field", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label_position",
						"heading" => __("Label position", "axiom"),
						"description" => __("Label position relative to the field", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['label_positions']),
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Contact_Form extends AXIOM_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Form_Item extends AXIOM_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Content block on fullscreen page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_content",
				"name" => __("Content block", "axiom"),
				"description" => __("Container for main content block (use it only on fullscreen pages)", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_content',
				"class" => "trx_sc_collection trx_sc_content",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "axiom"),
						"description" => __("Content for section container", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Content extends AXIOM_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Countdown
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_countdown",
				"name" => __("Countdown", "axiom"),
				"description" => __("Insert countdown object", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_countdown',
				"class" => "trx_sc_single trx_sc_countdown",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "date",
						"heading" => __("Date", "axiom"),
						"description" => __("Upcoming date (format: yyyy-mm-dd)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "time",
						"heading" => __("Time", "axiom"),
						"description" => __("Upcoming time (format: HH:mm:ss)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiom"),
						"description" => __("Countdown style", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Style 1', 'axiom') => 1,
							__('Style 2', 'axiom') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Align counter to left, center or right", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Countdown extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Dropcaps
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_dropcaps",
				"name" => __("Dropcaps", "axiom"),
				"description" => __("Make first letter of the text as dropcaps", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_dropcaps',
				"class" => "trx_sc_single trx_sc_dropcaps",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiom"),
						"description" => __("Dropcaps style", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Style 1', 'axiom') => 1,
							__('Style 2', 'axiom') => 2,
							__('Style 3', 'axiom') => 3,
							__('Style 4', 'axiom') => 4
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Paragraph text", "axiom"),
						"description" => __("Paragraph with dropcaps content", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_Dropcaps extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Emailer
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_emailer",
				"name" => __("E-mail collector", "axiom"),
				"description" => __("Collect e-mails into specified group", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_emailer',
				"class" => "trx_sc_single trx_sc_emailer",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "group",
						"heading" => __("Group", "axiom"),
						"description" => __("The name of group to collect e-mail address", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => __("Opened", "axiom"),
						"description" => __("Initially open the input field on show object", "axiom"),
						"class" => "",
						"value" => array(__('Initially opened', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Align field to left, center or right", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Emailer extends AXIOM_VC_ShortCodeSingle {}





			// Blogger
//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_events",
				"name" => __("Events", "axiom"),
				"description" => __("Insert posts (events) from desired categories or directly from ids", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_events',
				"class" => "trx_sc_single trx_sc_events",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Output style", "axiom"),
						"description" => __("Select desired style for event output", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Horizontal List', 'axiom') => 'list',
							__('Classic Layout', 'axiom') => 'classic',
							__('Classic 2 Layout', 'axiom') => 'classic2'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Post IDs list", "axiom"),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiom"),
						"group" => __('Query', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => __("Category ID", "axiom"),
						"description" => __("Category ID", "axiom"),
						"group" => __('Query', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => __("Total posts to show", "axiom"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored. (max - 4)", "axiom"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"group" => __('Query', 'axiom'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "col",
						"heading" => __("Column", "axiom"),
						"description" => __("How many columns will be displayed? (max - 4)", "axiom"),
						"admin_label" => true,
						"group" => __('Query', 'axiom'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "order",
						"heading" => __("Post order", "axiom"),
						"description" => __("Select desired posts order", "axiom"),
						"class" => "",
						"group" => __('Query', 'axiom'),
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation']
				),
			) );

//class WPBakeryShortCode_Trx_Events extends WPBakeryShortCodesContainer {}
			class WPBakeryShortCode_Trx_Events extends AXIOM_VC_ShortCodeSingle {}







			// Gap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_gap",
				"name" => __("Gap", "axiom"),
				"description" => __("Insert gap (fullwidth area) in the post content", "axiom"),
				"category" => __('Structure', 'js_composer'),
				'icon' => 'icon_trx_gap',
				"class" => "trx_sc_collection trx_sc_gap",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"params" => array(
/*
					array(
						"param_name" => "content",
						"heading" => __("Gap content", "axiom"),
						"description" => __("Gap inner content", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					)
*/
				)
			) );
			
			class WPBakeryShortCode_Trx_Gap extends AXIOM_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Googlemap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_googlemap",
				"name" => __("Google map", "axiom"),
				"description" => __("Insert Google map with desired address or coordinates", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_googlemap',
				"class" => "trx_sc_single trx_sc_googlemap",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "address",
						"heading" => __("Address", "axiom"),
						"description" => __("Address to show in map center", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "latlng",
						"heading" => __("Latitude and Longtitude", "axiom"),
						"description" => __("Comma separated map center coorditanes (instead Address)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "zoom",
						"heading" => __("Zoom", "axiom"),
						"description" => __("Map zoom factor", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "16",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiom"),
						"description" => __("Map custom style", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['googlemap_styles']),
						"type" => "dropdown"
					),
					axiom_vc_width('100%'),
					axiom_vc_height(240),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Googlemap extends AXIOM_VC_ShortCodeSingle {}






			// Hexagon
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_hexagon",
				"name" => __("Hexagon", "axiom"),
				"description" => __("Hexagon with content", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_hexagon', ////////////////////////////
				"class" => "trx_sc_container trx_sc_hexagon",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "text",
						"heading" => __("Hexagon text", "axiom"),
						"description" => __("Text, if needed, that would be shown inside of hexagon.", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Icon", "axiom"),
						"description" => __("Hexagon icon, if needed, from the Fontello icons set", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "axiom"),
						"description" => __("Link URL, if needed, from this icon (if not empty) and text (if entered)", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );

			class WPBakeryShortCode_Trx_Hexagon extends AXIOM_VC_ShortCodeContainer {}







			// Highlight
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_highlight",
				"name" => __("Highlight text", "axiom"),
				"description" => __("Highlight text with selected color, background color and other styles", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_highlight',
				"class" => "trx_sc_single trx_sc_highlight",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => __("Type", "axiom"),
						"description" => __("Highlight type", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Custom', 'axiom') => 0,
								__('Type 1', 'axiom') => 1,
								__('Type 2', 'axiom') => 2,
								__('Type 3', 'axiom') => 3
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "axiom"),
						"description" => __("Color for the highlighted text", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiom"),
						"description" => __("Background color for the highlighted text", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "axiom"),
						"description" => __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => __("Highlight text", "axiom"),
						"description" => __("Content for highlight", "axiom"),
						"class" => "",
						"value" => "",
						/*"holder" => "div",*/
						"type" => "textarea_html"
					),
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Highlight extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Icon
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_icon",
				"name" => __("Icon", "axiom"),
				"description" => __("Insert the icon", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_icon',
				"class" => "trx_sc_single trx_sc_icon",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => __("Icon", "axiom"),
						"description" => __("Select icon class from Fontello icons set", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "axiom"),
						"description" => __("Icon's color", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiom"),
						"description" => __("Background color for the icon", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_shape",
						"heading" => __("Background shape", "axiom"),
						"description" => __("Shape of the icon background", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('None', 'axiom') => 'none',
							__('Round', 'axiom') => 'round',
							__('Square', 'axiom') => 'square'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_style",
						"heading" => __("Icon's color scheme", "axiom"),
						"description" => __("Select icon's color scheme", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['button_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "axiom"),
						"description" => __("Icon's font size", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "axiom"),
						"description" => __("Icon's font weight", "axiom"),
						"class" => "",
						"value" => array(
							__('Default', 'axiom') => 'inherit',
							__('Thin (100)', 'axiom') => '100',
							__('Light (300)', 'axiom') => '300',
							__('Normal (400)', 'axiom') => '400',
							__('Bold (700)', 'axiom') => '700'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Icon's alignment", "axiom"),
						"description" => __("Align icon to left, center or right", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "axiom"),
						"description" => __("Link URL from this icon (if not empty)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Icon extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Image
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_image",
				"name" => __("Image", "axiom"),
				"description" => __("Insert image", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_image',
				"class" => "trx_sc_single trx_sc_image",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("Select image", "axiom"),
						"description" => __("Select image from library", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => __("Image alignment", "axiom"),
						"description" => __("Align image to left or right side", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => __("Image shape", "axiom"),
						"description" => __("Shape of the image: square or round", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Square', 'axiom') => 'square',
							__('Round', 'axiom') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiom"),
						"description" => __("Image's title", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Title's icon", "axiom"),
						"description" => __("Select icon for the title from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Image extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Infobox
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_infobox",
				"name" => __("Infobox", "axiom"),
				"description" => __("Box with info or error message", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_infobox',
				"class" => "trx_sc_container trx_sc_infobox",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiom"),
						"description" => __("Infobox style", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Regular', 'axiom') => 'regular',
								__('Info', 'axiom') => 'info',
								__('Success', 'axiom') => 'success',
								__('Error', 'axiom') => 'error',
								__('Result', 'axiom') => 'result'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "closeable",
						"heading" => __("Closeable", "axiom"),
						"description" => __("Create closeable box (with close button)", "axiom"),
						"class" => "",
						"value" => array(__('Close button', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Custom icon", "axiom"),
						"description" => __("Select icon for the infobox from Fontello icons set. If empty - use default icon", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "axiom"),
						"description" => __("Any color for the text and headers", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiom"),
						"description" => __("Any background color for this infobox", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
/*
					array(
						"param_name" => "content",
						"heading" => __("Message text", "axiom"),
						"description" => __("Message for the infobox", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Infobox extends AXIOM_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Line
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_line",
				"name" => __("Line", "axiom"),
				"description" => __("Insert line (delimiter)", "axiom"),
				"category" => __('Content', 'js_composer'),
				"class" => "trx_sc_single trx_sc_line",
				'icon' => 'icon_trx_line',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiom"),
						"description" => __("Line style", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Solid', 'axiom') => 'solid',
								__('Dashed', 'axiom') => 'dashed',
								__('Dotted', 'axiom') => 'dotted',
								__('Double', 'axiom') => 'double',
								__('Shadow', 'axiom') => 'shadow'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Line color", "axiom"),
						"description" => __("Line color", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Line extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// List
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_list",
				"name" => __("List", "axiom"),
				"description" => __("List items with specific bullets", "axiom"),
				"category" => __('Content', 'js_composer'),
				"class" => "trx_sc_collection trx_sc_list",
				'icon' => 'icon_trx_list',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_list_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Bullet's style", "axiom"),
						"description" => __("Bullet's style for each list item", "axiom"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['list_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", "axiom"),
						"description" => __("List items color", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => __("List icon", "axiom"),
						"description" => __("Select list icon from Fontello icons set (only for style=Iconed)", "axiom"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => __("Icon color", "axiom"),
						"description" => __("List icons color", "axiom"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => "",
						"type" => "colorpicker"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_list_item]' . __( 'Item 1', 'axiom' ) . '[/trx_list_item]
					[trx_list_item]' . __( 'Item 2', 'axiom' ) . '[/trx_list_item]
				'
			) );
			
			
			vc_map( array(
				"base" => "trx_list_item",
				"name" => __("List item", "axiom"),
				"description" => __("List item with specific bullet", "axiom"),
				"class" => "trx_sc_single trx_sc_list_item",
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_list_item',
				"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_list'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("List item title", "axiom"),
						"description" => __("Title for the current list item (show it as tooltip)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "axiom"),
						"description" => __("Link URL for the current list item", "axiom"),
						"admin_label" => true,
						"group" => __('Link', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => __("Link target", "axiom"),
						"description" => __("Link target for the current list item", "axiom"),
						"admin_label" => true,
						"group" => __('Link', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", "axiom"),
						"description" => __("Text color for this item", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => __("List item icon", "axiom"),
						"description" => __("Select list item icon from Fontello icons set (only for style=Iconed)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => __("Icon color", "axiom"),
						"description" => __("Icon color for this item", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "content",
						"heading" => __("List item text", "axiom"),
						"description" => __("Current list item content", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_List extends AXIOM_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_List_Item extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			
			
			// Number
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_number",
				"name" => __("Number", "axiom"),
				"description" => __("Insert number or any word as set of separated characters", "axiom"),
				"category" => __('Content', 'js_composer'),
				"class" => "trx_sc_single trx_sc_number",
				'icon' => 'icon_trx_number',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "value",
						"heading" => __("Value", "axiom"),
						"description" => __("Number or any word to separate", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Select block alignment", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Number extends AXIOM_VC_ShortCodeSingle {}


			
			
			
			
			
			// Parallax
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_parallax",
				"name" => __("Parallax", "axiom"),
				"description" => __("Create the parallax container (with asinc background image)", "axiom"),
				"category" => __('Structure', 'js_composer'),
				'icon' => 'icon_trx_parallax',
				"class" => "trx_sc_collection trx_sc_parallax",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "gap",
						"heading" => __("Create gap", "axiom"),
						"description" => __("Create gap around parallax container (not need in fullscreen pages)", "axiom"),
						"class" => "",
						"value" => array(__('Create gap', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Direction", "axiom"),
						"description" => __("Scroll direction for the parallax background", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Up', 'axiom') => 'up',
								__('Down', 'axiom') => 'down'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "speed",
						"heading" => __("Speed", "axiom"),
						"description" => __("Parallax background motion speed (from 0.0 to 1.0)", "axiom"),
						"class" => "",
						"value" => "0.3",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Text color", "axiom"),
						"description" => __("Select color for text object inside parallax block", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => __("Bg tint", "axiom"),
						"description" => __("Select tint of the parallax background (for correct font color choise)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Light', 'axiom') => 'light',
								__('Dark', 'axiom') => 'dark'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Backgroud color", "axiom"),
						"description" => __("Select color for parallax background", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", "axiom"),
						"description" => __("Select or upload image or write URL from other site for the parallax background", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image_x",
						"heading" => __("Image X position", "axiom"),
						"description" => __("Parallax background X position (in percents)", "axiom"),
						"class" => "",
						"value" => "50%",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video",
						"heading" => __("Video background", "axiom"),
						"description" => __("Paste URL for video file to show it as parallax background", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video_ratio",
						"heading" => __("Video ratio", "axiom"),
						"description" => __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "axiom"),
						"class" => "",
						"value" => "16:9",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "axiom"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "axiom"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
/*
					array(
						"param_name" => "content",
						"heading" => __("Content", "axiom"),
						"description" => __("Content for the parallax container", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Parallax extends AXIOM_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Popup
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_popup",
				"name" => __("Popup window", "axiom"),
				"description" => __("Container for any html-block with desired class and style for popup window", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_popup',
				"class" => "trx_sc_collection trx_sc_popup",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "axiom"),
						"description" => __("Content for popup container", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Popup extends AXIOM_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Price
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price",
				"name" => __("Price", "axiom"),
				"description" => __("Insert price with decoration", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_price',
				"class" => "trx_sc_single trx_sc_price",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "money",
						"heading" => __("Money", "axiom"),
						"description" => __("Money value (dot or comma separated)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => __("Currency symbol", "axiom"),
						"description" => __("Currency character", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => __("Period", "axiom"),
						"description" => __("Period text (if need). For example: monthly, daily, etc.", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Align price to left or right side", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Price extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Price block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price_block",
				"name" => __("Price block", "axiom"),
				"description" => __("Insert price block with title, price and description", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_price_block',
				"class" => "trx_sc_single trx_sc_price_block",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiom"),
						"description" => __("Block title", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link URL", "axiom"),
						"description" => __("URL for link from button (at bottom of the block)", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_text",
						"heading" => __("Link text", "axiom"),
						"description" => __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Icon", "axiom"),
						"description" => __("Select icon from Fontello icons set (placed before/instead price)", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "money",
						"heading" => __("Money", "axiom"),
						"description" => __("Money value (dot or comma separated)", "axiom"),
						"admin_label" => true,
						"group" => __('Money', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => __("Currency symbol", "axiom"),
						"description" => __("Currency character", "axiom"),
						"admin_label" => true,
						"group" => __('Money', 'axiom'),
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => __("Period", "axiom"),
						"description" => __("Period text (if need). For example: monthly, daily, etc.", "axiom"),
						"admin_label" => true,
						"group" => __('Money', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Align price to left or right side", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Description", "axiom"),
						"description" => __("Description for this price block", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_PriceBlock extends AXIOM_VC_ShortCodeSingle {}

			
			
			
			
			// Quote
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_quote",
				"name" => __("Quote", "axiom"),
				"description" => __("Quote text", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_quote',
				"class" => "trx_sc_single trx_sc_quote",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "cite",
						"heading" => __("Quote cite", "axiom"),
						"description" => __("URL for the quote cite link", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title (author)", "axiom"),
						"description" => __("Quote title (author name)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => __("Quote content", "axiom"),
						"description" => __("Quote content", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					axiom_vc_width(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Quote extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Reviews
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_reviews",
				"name" => __("Reviews", "axiom"),
				"description" => __("Insert reviews block in the single post", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_reviews',
				"class" => "trx_sc_single trx_sc_reviews",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Align counter to left, center or right", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Reviews extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Search
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_search",
				"name" => __("Search form", "axiom"),
				"description" => __("Insert search form", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_search',
				"class" => "trx_sc_single trx_sc_search",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Style", "axiom"),
						"description" => __("Select style to display search field", "axiom"),
						"class" => "",
						"value" => array(
							__('Regular', 'axiom') => "regular",
							__('Flat', 'axiom') => "flat"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiom"),
						"description" => __("Title (placeholder) for the search field", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => __("Search &hellip;", 'axiom'),
						"type" => "textfield"
					),
					array(
						"param_name" => "ajax",
						"heading" => __("AJAX", "axiom"),
						"description" => __("Search via AJAX or reload page", "axiom"),
						"class" => "",
						"value" => array(__('Use AJAX search', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Search extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Section
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_section",
				"name" => __("Section container", "axiom"),
				"description" => __("Container for any block ([block] analog - to enable nesting)", "axiom"),
				"category" => __('Content', 'js_composer'),
				"class" => "trx_sc_collection trx_sc_section",
				'icon' => 'icon_trx_block',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => __("Dedicated", "axiom"),
						"description" => __("Use this block as dedicated content - show it before post title on single page", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Use as dedicated content', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Select block alignment", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns emulation", "axiom"),
						"description" => __("Select width for columns emulation", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => __("Use pan effect", "axiom"),
						"description" => __("Use pan effect to show section content", "axiom"),
						"group" => __('Scroll', 'axiom'),
						"class" => "",
						"value" => array(__('Content scroller', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Use scroller", "axiom"),
						"description" => __("Use scroller to show section content", "axiom"),
						"group" => __('Scroll', 'axiom'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Content scroller', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => __("Scroll and Pan direction", "axiom"),
						"description" => __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"group" => __('Scroll', 'axiom'),
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => __("Scroll controls", "axiom"),
						"description" => __("Show scroll controls (if Use scroller = yes)", "axiom"),
						"class" => "",
						"group" => __('Scroll', 'axiom'),
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Fore color", "axiom"),
						"description" => __("Any color for objects in this section", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => __("Background tint", "axiom"),
						"description" => __("Main background tint: dark or light", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiom"),
						"description" => __("Any background color for this section", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "axiom"),
						"description" => __("Select background image from library for this section", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "axiom"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "axiom"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "axiom"),
						"description" => __("Font size of the text (default - in pixels, allows any CSS units of measure)", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "axiom"),
						"description" => __("Font weight of the text", "axiom"),
						"class" => "",
						"value" => array(
							__('Default', 'axiom') => 'inherit',
							__('Thin (100)', 'axiom') => '100',
							__('Light (300)', 'axiom') => '300',
							__('Normal (400)', 'axiom') => '400',
							__('Bold (700)', 'axiom') => '700'
						),
						"type" => "dropdown"
					),
/*
					array(
						"param_name" => "content",
						"heading" => __("Container content", "axiom"),
						"description" => __("Content for section container", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Section extends AXIOM_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Skills
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_skills",
				"name" => __("Skills", "axiom"),
				"description" => __("Insert skills diagramm", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_skills',
				"class" => "trx_sc_collection trx_sc_skills",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_skills_item'),
				"params" => array(
					array(
						"param_name" => "max_value",
						"heading" => __("Max value", "axiom"),
						"description" => __("Max value for skills items", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "100",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => __("Skills type", "axiom"),
						"description" => __("Select type of skills block", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Bar', 'axiom') => 'bar',
							__('Pie chart', 'axiom') => 'pie',
							__('Counter', 'axiom') => 'counter',
							__('Arc', 'axiom') => 'arc'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "layout",
						"heading" => __("Skills layout", "axiom"),
						"description" => __("Select layout of skills block", "axiom"),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter','bar','pie')
						),
						"class" => "",
						"value" => array(
							__('Rows', 'axiom') => 'rows',
							__('Columns', 'axiom') => 'columns'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => __("Direction", "axiom"),
						"description" => __("Select direction of skills block", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Counters style", "axiom"),
						"description" => __("Select style of skills items (only for type=counter)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Style 1', 'axiom') => '1',
							__('Style 2', 'axiom') => '2',
							__('Style 3', 'axiom') => '3',
							__('Style 4', 'axiom') => '4'
						),
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns count", "axiom"),
						"description" => __("Skills columns count (required)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", "axiom"),
						"description" => __("Color for all skills items", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiom"),
						"description" => __("Background color for all skills items (only for type=pie)", "axiom"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => __("Border color", "axiom"),
						"description" => __("Border color for all skills items (only for type=pie)", "axiom"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiom"),
						"description" => __("Title of the skills block", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => __("Subtitle", "axiom"),
						"description" => __("Default subtitle of the skills block (only if type=arc)", "axiom"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('arc')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Align skills block to left or right side", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_skills_item",
				"name" => __("Skill", "axiom"),
				"description" => __("Skills item", "axiom"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_skills_item",
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_skills'),
				"as_parent" => array('except' => 'trx_skills'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiom"),
						"description" => __("Title for the current skills item", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Icon", "axiom"),
						"description" => __("Select icon class from Fontello icons set", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "value",
						"heading" => __("Value", "axiom"),
						"description" => __("Value for the current skills item", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "50",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", "axiom"),
						"description" => __("Color for current skills item", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiom"),
						"description" => __("Background color for current skills item (only for type=pie)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => __("Border color", "axiom"),
						"description" => __("Border color for current skills item (only for type=pie)", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "style",
						"heading" => __("Item style", "axiom"),
						"description" => __("Select style for the current skills item (only for type=counter)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Style 1', 'axiom') => '1',
							__('Style 2', 'axiom') => '2',
							__('Style 3', 'axiom') => '3',
							__('Style 4', 'axiom') => '4'
						),
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Skills extends AXIOM_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Skills_Item extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Slider
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_slider",
				"name" => __("Slider", "axiom"),
				"description" => __("Insert slider", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_slider',
				"class" => "trx_sc_collection trx_sc_slider",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_slider_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "engine",
						"heading" => __("Engine", "axiom"),
						"description" => __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['sliders']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Float slider", "axiom"),
						"description" => __("Float slider to left or right side", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom slides", "axiom"),
						"description" => __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "axiom"),
						"class" => "",
						"value" => array(__('Custom slides', 'axiom') => 'yes'),
						"type" => "checkbox"
					)
					),
					axiom_exists_revslider() || axiom_exists_royalslider() ? array(
					array(
						"param_name" => "alias",
						"heading" => __("Revolution slider alias or Royal Slider ID", "axiom"),
						"description" => __("Alias for Revolution slider or Royal slider ID", "axiom"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'engine',
							'value' => array('revo','royal')
						),
						"value" => "",
						"type" => "textfield"
					)) : array(), array(
					array(
						"param_name" => "cat",
						"heading" => __("Categories list", "axiom"),
						"description" => __("Select category. If empty - show posts from any category or from IDs list", "axiom"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip(axiom_array_merge(array(0 => __('- Select category -', 'axiom')), $AXIOM_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => __("Swiper: Number of posts", "axiom"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiom"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Swiper: Offset before select posts", "axiom"),
						"description" => __("Skip posts before select next part.", "axiom"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Swiper: Post sorting", "axiom"),
						"description" => __("Select desired posts sorting method", "axiom"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Swiper: Post order", "axiom"),
						"description" => __("Select desired posts order", "axiom"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Swiper: Post IDs list", "axiom"),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiom"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Swiper: Show slider controls", "axiom"),
						"description" => __("Show arrows inside slider", "axiom"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Show controls', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pagination",
						"heading" => __("Swiper: Show slider pagination", "axiom"),
						"description" => __("Show bullets or titles to switch slides", "axiom"),
						"group" => __('Details', 'axiom'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								__('Dots', 'axiom') => 'yes',
								__('Side Titles', 'axiom') => 'full',
								__('Over Titles', 'axiom') => 'over',
								__('None', 'axiom') => 'no'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "titles",
						"heading" => __("Swiper: Show titles section", "axiom"),
						"description" => __("Show section with post's title and short post's description", "axiom"),
						"group" => __('Details', 'axiom'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								__('Not show', 'axiom') => "no",
								__('Show/Hide info', 'axiom') => "slide",
								__('Fixed info', 'axiom') => "fixed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "descriptions",
						"heading" => __("Swiper: Post descriptions", "axiom"),
						"description" => __("Show post's excerpt max length (characters)", "axiom"),
						"group" => __('Details', 'axiom'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => __("Swiper: Post's title as link", "axiom"),
						"description" => __("Make links from post's titles", "axiom"),
						"group" => __('Details', 'axiom'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Titles as a links', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "crop",
						"heading" => __("Swiper: Crop images", "axiom"),
						"description" => __("Crop images in each slide or live it unchanged", "axiom"),
						"group" => __('Details', 'axiom'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Crop images', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Swiper: Autoheight", "axiom"),
						"description" => __("Change whole slider's height (make it equal current slide's height)", "axiom"),
						"group" => __('Details', 'axiom'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Autoheight', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Swiper: Slides change interval", "axiom"),
						"description" => __("Slides change interval (in milliseconds: 1000ms = 1s)", "axiom"),
						"group" => __('Details', 'axiom'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "5000",
						"type" => "textfield"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_slider_item",
				"name" => __("Slide", "axiom"),
				"description" => __("Slider item - single slide", "axiom"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_slider_item',
				"as_child" => array('only' => 'trx_slider'),
				"as_parent" => array('except' => 'trx_slider'),
				"params" => array(
					array(
						"param_name" => "src",
						"heading" => __("URL (source) for image file", "axiom"),
						"description" => __("Select or upload image or write URL from other site for the current slide", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Slider extends AXIOM_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Slider_Item extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Socials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_socials",
				"name" => __("Social icons", "axiom"),
				"description" => __("Custom social icons", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_socials',
				"class" => "trx_sc_collection trx_sc_socials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_social_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "size",
						"heading" => __("Icon's size", "axiom"),
						"description" => __("Size of the icons", "axiom"),
						"class" => "",
						"value" => array(
							__('Tiny', 'axiom') => 'tiny',
							__('Small', 'axiom') => 'small',
							__('Large', 'axiom') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "quantity",
						"heading" => __("Socials icons quantity ", "axiom"),
						"description" => __("Limit quantity of shown socials icons. Leave blank to show all.", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "socials",
						"heading" => __("Manual socials list", "axiom"),
						"description" => __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom socials", "axiom"),
						"description" => __("Make custom icons from inner shortcodes (prepare it on tabs)", "axiom"),
						"class" => "",
						"value" => array(__('Custom socials', 'axiom') => 'yes'),
						"type" => "checkbox"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_social_item",
				"name" => __("Custom social item", "axiom"),
				"description" => __("Custom social item: name, profile url and icon url", "axiom"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_social_item',
				"as_child" => array('only' => 'trx_socials'),
				"as_parent" => array('except' => 'trx_socials'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => __("Social name", "axiom"),
						"description" => __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => __("Your profile URL", "axiom"),
						"description" => __("URL of your profile in specified social network", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("URL (source) for icon file", "axiom"),
						"description" => __("Select or upload image or write URL from other site for the current social icon", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Socials extends AXIOM_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Social_Item extends AXIOM_VC_ShortCodeSingle {}
			

			
			
			
			
			
			// Table
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_table",
				"name" => __("Table", "axiom"),
				"description" => __("Insert a table", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_table',
				"class" => "trx_sc_container trx_sc_table",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => __("Cells content alignment", "axiom"),
						"description" => __("Select alignment for each table cell", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => __("Table content", "axiom"),
						"description" => __("Content, created with any table-generator", "axiom"),
						"class" => "",
						"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
						/*"holder" => "div",*/
						"type" => "textarea_html"
					),
					axiom_vc_width(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Table extends AXIOM_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Tabs
			//-------------------------------------------------------------------------------------
			
			$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
			$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
			vc_map( array(
				"base" => "trx_tabs",
				"name" => __("Tabs", "axiom"),
				"description" => __("Tabs", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_tabs',
				"class" => "trx_sc_collection trx_sc_tabs",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_tab'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Tabs style", "axiom"),
						"description" => __("Select style of tabs items", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Style 1', 'axiom') => '1',
							__('Style 2', 'axiom') => '2'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "initial",
						"heading" => __("Initially opened tab", "axiom"),
						"description" => __("Number of initially opened tab", "axiom"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "scroll",
						"heading" => __("Scroller", "axiom"),
						"description" => __("Use scroller to show tab content (height parameter required)", "axiom"),
						"class" => "",
						"value" => array("Use scroller" => "yes" ),
						"type" => "checkbox"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_tab title="' . __( 'Tab 1', 'axiom' ) . '" tab_id="'.esc_attr($tab_id_1).'"][/trx_tab]
					[trx_tab title="' . __( 'Tab 2', 'axiom' ) . '" tab_id="'.esc_attr($tab_id_2).'"][/trx_tab]
				',
				"custom_markup" => '
					<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
						<ul class="tabs_controls">
						</ul>
						%content%
					</div>
				',
				'js_view' => 'VcTrxTabsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_tab",
				"name" => __("Tab item", "axiom"),
				"description" => __("Single tab item", "axiom"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_tab",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_tab',
				"as_child" => array('only' => 'trx_tabs'),
				"as_parent" => array('except' => 'trx_tabs'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Tab title", "axiom"),
						"description" => __("Title for current tab", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "tab_id",
						"heading" => __("Tab ID", "axiom"),
						"description" => __("ID for current tab (required). Please, start it from letter.", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxTabView'
			) );
			class WPBakeryShortCode_Trx_Tabs extends AXIOM_VC_ShortCodeTabs {}
			class WPBakeryShortCode_Trx_Tab extends AXIOM_VC_ShortCodeTab {}
			
			
			
			
			// Team
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_team",
				"name" => __("Team", "axiom"),
				"description" => __("Insert team members", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_team',
				"class" => "trx_sc_columns trx_sc_team",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_team_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Team style", "axiom"),
						"description" => __("Select style to display team members", "axiom"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							__('Style 1', 'axiom') => 1,
							__('Style 2', 'axiom') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => __("Columns", "axiom"),
						"description" => __("How many columns use to show team members", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "axiom"),
						"description" => __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "axiom"),
						"class" => "",
						"value" => array("Custom members" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "cat",
						"heading" => __("Categories", "axiom"),
						"description" => __("Put here comma separated categories (ids or slugs) to show team members. If empty - select team members from any category (group) or from IDs list", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => __("Number of posts", "axiom"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Offset before select posts", "axiom"),
						"description" => __("Skip posts before select next part.", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Post sorting", "axiom"),
						"description" => __("Select desired posts sorting method", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Post order", "axiom"),
						"description" => __("Select desired posts order", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Team member's IDs list", "axiom"),
						"description" => __("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_team_item user="' . __( 'Member 1', 'axiom' ) . '"][/trx_team_item]
					[trx_team_item user="' . __( 'Member 2', 'axiom' ) . '"][/trx_team_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_team_item",
				"name" => __("Team member", "axiom"),
				"description" => __("Team member - all data pull out from it account on your site", "axiom"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_item trx_sc_column_item trx_sc_team_item",
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_team_item',
				"as_child" => array('only' => 'trx_team'),
				"as_parent" => array('except' => 'trx_team'),
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => __("Registered user", "axiom"),
						"description" => __("Select one of registered users (if present) or put name, position, etc. in fields below", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['users']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "member",
						"heading" => __("Team member", "axiom"),
						"description" => __("Select one of team members (if present) or put name, position, etc. in fields below", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['members']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link", "axiom"),
						"description" => __("Link on team member's personal page", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "name",
						"heading" => __("Name", "axiom"),
						"description" => __("Team member's name", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => __("Position", "axiom"),
						"description" => __("Team member's position", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => __("E-mail", "axiom"),
						"description" => __("Team member's e-mail", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => __("Member's Photo", "axiom"),
						"description" => __("Team member's photo (avatar", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "socials",
						"heading" => __("Socials", "axiom"),
						"description" => __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Team extends AXIOM_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Team_Item extends AXIOM_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Testimonials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_testimonials",
				"name" => __("Testimonials", "axiom"),
				"description" => __("Insert testimonials slider", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_collection trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "controls",
						"heading" => __("Show arrows", "axiom"),
						"description" => __("Show control buttons", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls_top",
						"heading" => __("Arrows at top position", "axiom"),
						"description" => __("Show control buttons at top position? Default - middle position", "axiom"),
						"admin_label" => false,
						'dependency' => array(
							'element' => 'controls',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Testimonials change interval", "axiom"),
						"description" => __("Testimonials change interval (in milliseconds: 1000ms = 1s)", "axiom"),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Alignment of the testimonials block", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Autoheight", "axiom"),
						"description" => __("Change whole slider's height (make it equal current slide's height)", "axiom"),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => __("Custom", "axiom"),
						"description" => __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "axiom"),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "cat",
						"heading" => __("Categories", "axiom"),
						"description" => __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => __("Number of posts", "axiom"),
						"description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => __("Offset before select posts", "axiom"),
						"description" => __("Skip posts before select next part.", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => __("Post sorting", "axiom"),
						"description" => __("Select desired posts sorting method", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => __("Post order", "axiom"),
						"description" => __("Select desired posts order", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => __("Post IDs list", "axiom"),
						"description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "axiom"),
						"group" => __('Query', 'axiom'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => __("Background tint", "axiom"),
						"description" => __("Main background tint: dark or light", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiom"),
						"description" => __("Any background color for this section", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "axiom"),
						"description" => __("Select background image from library for this section", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "axiom"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "axiom"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => __("Testimonial", "axiom"),
				"description" => __("Single testimonials item", "axiom"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => __("Author", "axiom"),
						"description" => __("Name of the testimonmials author", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => __("Link", "axiom"),
						"description" => __("Link URL to the testimonmials author page", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => __("E-mail", "axiom"),
						"description" => __("E-mail of the testimonmials author", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => __("Photo", "axiom"),
						"description" => __("Select or upload photo of testimonmials author or write URL of photo from other site", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "hidden" //attach_image
					),
					array(
						"param_name" => "content",
						"heading" => __("Testimonials text", "axiom"),
						"description" => __("Current testimonials text", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Testimonials extends AXIOM_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Testimonials_Item extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Title
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_title",
				"name" => __("Title", "axiom"),
				"description" => __("Create header tag (1-6 level) with many styles", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_title',
				"class" => "trx_sc_single trx_sc_title",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => __("Title content", "axiom"),
						"description" => __("Title content", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					array(
						"param_name" => "type",
						"heading" => __("Title type", "axiom"),
						"description" => __("Title type (header level)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Header 1', 'axiom') => '1',
							__('Header 2', 'axiom') => '2',
							__('Header 3', 'axiom') => '3',
							__('Header 4', 'axiom') => '4',
							__('Header 5', 'axiom') => '5',
							__('Header 6', 'axiom') => '6'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "h1_styling",
						"heading" => __("H1 additional style.", "axiom"),
						"description" => __("Select H1 additional style. Top, bottom or none.", "axiom"),
						"class" => "",
						'dependency' => array(
							'element' => 'type',
							'value' => array('1')
						),
						"value" => array(
							__('None', 'axiom') => '0',
							__('Line on the left', 'axiom') => '1'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => __("Title style", "axiom"),
						"description" => __("Title style: only text (regular) or with icon/image (iconed)", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Regular', 'axiom') => 'regular',
							__('Underline', 'axiom') => 'underline',
							__('Divider', 'axiom') => 'divider',
							__('With icon (image)', 'axiom') => 'iconed'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Title text alignment", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => __("Font size", "axiom"),
						"description" => __("Custom font size. If empty - use theme default", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => __("Font weight", "axiom"),
						"description" => __("Custom font weight. If empty or inherit - use theme default", "axiom"),
						"class" => "",
						"value" => array(
							__('Default', 'axiom') => 'inherit',
							__('Thin (100)', 'axiom') => '100',
							__('Light (300)', 'axiom') => '300',
							__('Normal (400)', 'axiom') => '400',
							__('Semibold (600)', 'axiom') => '600',
							__('Bold (700)', 'axiom') => '700',
							__('Black (900)', 'axiom') => '900'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => __("Title color", "axiom"),
						"description" => __("Select color for the title", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "link",
						"heading" => __("Title link", "axiom"),
						"description" => __("Insert title link if needed", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => __("Title font icon", "axiom"),
						"description" => __("Select font icon for the title from Fontello icons set (if style=iconed)", "axiom"),
						"class" => "",
						"group" => __('Icon &amp; Image', 'axiom'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => __("or image icon", "axiom"),
						"description" => __("Select image icon for the title instead icon above (if style=iconed)", "axiom"),
						"class" => "",
						"group" => __('Icon &amp; Image', 'axiom'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $AXIOM_GLOBALS['sc_params']['images'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "picture",
						"heading" => __("or select uploaded image", "axiom"),
						"description" => __("Select or upload image or write URL from other site (if style=iconed)", "axiom"),
						"group" => __('Icon &amp; Image', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_size",
						"heading" => __("Image (picture) size", "axiom"),
						"description" => __("Select image (picture) size (if style=iconed)", "axiom"),
						"group" => __('Icon &amp; Image', 'axiom'),
						"class" => "",
						"value" => array(
							__('Small', 'axiom') => 'small',
							__('Medium', 'axiom') => 'medium',
							__('Large', 'axiom') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "position",
						"heading" => __("Icon (image) position", "axiom"),
						"description" => __("Select icon (image) position (if style=iconed)", "axiom"),
						"group" => __('Icon &amp; Image', 'axiom'),
						"class" => "",
						"value" => array(
							__('Top', 'axiom') => 'top',
							__('Left', 'axiom') => 'left'
						),
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Title extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Toggles
			//-------------------------------------------------------------------------------------
				
			vc_map( array(
				"base" => "trx_toggles",
				"name" => __("Toggles", "axiom"),
				"description" => __("Toggles items", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_toggles',
				"class" => "trx_sc_collection trx_sc_toggles",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_toggles_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => __("Toggles style", "axiom"),
						"description" => __("Select style for display toggles", "axiom"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							__('Style 1', 'axiom') => 1,
							__('Style 2', 'axiom') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => __("Counter", "axiom"),
						"description" => __("Display counter before each toggles title", "axiom"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "axiom"),
						"description" => __("Select icon for the closed toggles item from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "axiom"),
						"description" => __("Select icon for the opened toggles item from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class']
				),
				'default_content' => '
					[trx_toggles_item title="' . __( 'Item 1 title', 'axiom' ) . '"][/trx_toggles_item]
					[trx_toggles_item title="' . __( 'Item 2 title', 'axiom' ) . '"][/trx_toggles_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", "axiom").'">'.__("Add item", "axiom").'</button>
					</div>
				',
				'js_view' => 'VcTrxTogglesView'
			) );
			
			
			vc_map( array(
				"base" => "trx_toggles_item",
				"name" => __("Toggles item", "axiom"),
				"description" => __("Single toggles item", "axiom"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_toggles_item',
				"as_child" => array('only' => 'trx_toggles'),
				"as_parent" => array('except' => 'trx_toggles'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => __("Title", "axiom"),
						"description" => __("Title for current toggles item", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => __("Open on show", "axiom"),
						"description" => __("Open current toggle item on show", "axiom"),
						"class" => "",
						"value" => array("Opened" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => __("Icon while closed", "axiom"),
						"description" => __("Select icon for the closed toggles item from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => __("Icon while opened", "axiom"),
						"description" => __("Select icon for the opened toggles item from Fontello icons set", "axiom"),
						"class" => "",
						"value" => $AXIOM_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTogglesTabView'
			) );
			class WPBakeryShortCode_Trx_Toggles extends AXIOM_VC_ShortCodeToggles {}
			class WPBakeryShortCode_Trx_Toggles_Item extends AXIOM_VC_ShortCodeTogglesItem {}
			
			
			
			
			
			
			// Twitter
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_twitter",
				"name" => __("Twitter", "axiom"),
				"description" => __("Insert twitter feed into post (page)", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_twitter',
				"class" => "trx_sc_single trx_sc_twitter",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => __("Twitter Username", "axiom"),
						"description" => __("Your username in the twitter account. If empty - get it from Theme Options.", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_key",
						"heading" => __("Consumer Key", "axiom"),
						"description" => __("Consumer Key from the twitter account", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_secret",
						"heading" => __("Consumer Secret", "axiom"),
						"description" => __("Consumer Secret from the twitter account", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_key",
						"heading" => __("Token Key", "axiom"),
						"description" => __("Token Key from the twitter account", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_secret",
						"heading" => __("Token Secret", "axiom"),
						"description" => __("Token Secret from the twitter account", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => __("Tweets number", "axiom"),
						"description" => __("Number tweets to show", "axiom"),
						"class" => "",
						"divider" => true,
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => __("Show arrows", "axiom"),
						"description" => __("Show control buttons", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => __("Tweets change interval", "axiom"),
						"description" => __("Tweets change interval (in milliseconds: 1000ms = 1s)", "axiom"),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Alignment of the tweets block", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => __("Autoheight", "axiom"),
						"description" => __("Change whole slider's height (make it equal current slide's height)", "axiom"),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => __("Background tint", "axiom"),
						"description" => __("Main background tint: dark or light", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => __("Background color", "axiom"),
						"description" => __("Any background color for this section", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image URL", "axiom"),
						"description" => __("Select background image from library for this section", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => __("Overlay", "axiom"),
						"description" => __("Overlay color opacity (from 0.0 to 1.0)", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => __("Texture", "axiom"),
						"description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "axiom"),
						"group" => __('Colors and Images', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Twitter extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Video
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_video",
				"name" => __("Video", "axiom"),
				"description" => __("Insert video player", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_video',
				"class" => "trx_sc_single trx_sc_video",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => __("URL for video file", "axiom"),
						"description" => __("Paste URL for video file", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ratio",
						"heading" => __("Ratio", "axiom"),
						"description" => __("Select ratio for display video", "axiom"),
						"class" => "",
						"value" => array(
							__('16:9', 'axiom') => "16:9",
							__('4:3', 'axiom') => "4:3"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoplay",
						"heading" => __("Autoplay video", "axiom"),
						"description" => __("Autoplay video on page load", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Select block alignment", "axiom"),
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => __("Cover image", "axiom"),
						"description" => __("Select or upload image or write URL from other site for video preview", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", "axiom"),
						"description" => __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "axiom"),
						"group" => __('Background', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => __("Top offset", "axiom"),
						"description" => __("Top offset (padding) from background image to video block (in percent). For example: 3%", "axiom"),
						"group" => __('Background', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => __("Bottom offset", "axiom"),
						"description" => __("Bottom offset (padding) from background image to video block (in percent). For example: 3%", "axiom"),
						"group" => __('Background', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => __("Left offset", "axiom"),
						"description" => __("Left offset (padding) from background image to video block (in percent). For example: 20%", "axiom"),
						"group" => __('Background', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => __("Right offset", "axiom"),
						"description" => __("Right offset (padding) from background image to video block (in percent). For example: 12%", "axiom"),
						"group" => __('Background', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Video extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Zoom
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_zoom",
				"name" => __("Zoom", "axiom"),
				"description" => __("Insert the image with zoom/lens effect", "axiom"),
				"category" => __('Content', 'js_composer'),
				'icon' => 'icon_trx_zoom',
				"class" => "trx_sc_single trx_sc_zoom",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "effect",
						"heading" => __("Effect", "axiom"),
						"description" => __("Select effect to display overlapping image", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Lens', 'axiom') => 'lens',
							__('Zoom', 'axiom') => 'zoom'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "url",
						"heading" => __("Main image", "axiom"),
						"description" => __("Select or upload main image", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "over",
						"heading" => __("Overlaping image", "axiom"),
						"description" => __("Select or upload overlaping image", "axiom"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => __("Alignment", "axiom"),
						"description" => __("Float zoom to left or right side", "axiom"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($AXIOM_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_image",
						"heading" => __("Background image", "axiom"),
						"description" => __("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "axiom"),
						"group" => __('Background', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => __("Top offset", "axiom"),
						"description" => __("Top offset (padding) from background image to zoom block (in percent). For example: 3%", "axiom"),
						"group" => __('Background', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => __("Bottom offset", "axiom"),
						"description" => __("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", "axiom"),
						"group" => __('Background', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => __("Left offset", "axiom"),
						"description" => __("Left offset (padding) from background image to zoom block (in percent). For example: 20%", "axiom"),
						"group" => __('Background', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => __("Right offset", "axiom"),
						"description" => __("Right offset (padding) from background image to zoom block (in percent). For example: 12%", "axiom"),
						"group" => __('Background', 'axiom'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					axiom_vc_width(),
					axiom_vc_height(),
					$AXIOM_GLOBALS['vc_params']['margin_top'],
					$AXIOM_GLOBALS['vc_params']['margin_bottom'],
					$AXIOM_GLOBALS['vc_params']['margin_left'],
					$AXIOM_GLOBALS['vc_params']['margin_right'],
					$AXIOM_GLOBALS['vc_params']['id'],
					$AXIOM_GLOBALS['vc_params']['class'],
					$AXIOM_GLOBALS['vc_params']['animation'],
					$AXIOM_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Zoom extends AXIOM_VC_ShortCodeSingle {}
			

			do_action('axiom_action_shortcodes_list_vc');
			
			
			if (false && axiom_exists_woocommerce()) {
			
				// WooCommerce - Cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_cart",
					"name" => __("Cart", "axiom"),
					"description" => __("WooCommerce shortcode: show cart page", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_cart',
					"class" => "trx_sc_alone trx_sc_woocommerce_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Cart extends AXIOM_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Checkout
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_checkout",
					"name" => __("Checkout", "axiom"),
					"description" => __("WooCommerce shortcode: show checkout page", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_checkout',
					"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Checkout extends AXIOM_VC_ShortCodeAlone {}
			
			
				// WooCommerce - My Account
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_my_account",
					"name" => __("My Account", "axiom"),
					"description" => __("WooCommerce shortcode: show my account page", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_my_account',
					"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_My_Account extends AXIOM_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Order Tracking
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_order_tracking",
					"name" => __("Order Tracking", "axiom"),
					"description" => __("WooCommerce shortcode: show order tracking page", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_order_tracking',
					"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Order_Tracking extends AXIOM_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Shop Messages
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "shop_messages",
					"name" => __("Shop Messages", "axiom"),
					"description" => __("WooCommerce shortcode: show shop messages", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_shop_messages',
					"class" => "trx_sc_alone trx_sc_shop_messages",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Shop_Messages extends AXIOM_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Product Page
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_page",
					"name" => __("Product Page", "axiom"),
					"description" => __("WooCommerce shortcode: display single product page", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_page',
					"class" => "trx_sc_single trx_sc_product_page",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => __("SKU", "axiom"),
							"description" => __("SKU code of displayed product", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => __("ID", "axiom"),
							"description" => __("ID of displayed product", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "posts_per_page",
							"heading" => __("Number", "axiom"),
							"description" => __("How many products showed", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_type",
							"heading" => __("Post type", "axiom"),
							"description" => __("Post type for the WP query (leave 'product')", "axiom"),
							"class" => "",
							"value" => "product",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_status",
							"heading" => __("Post status", "axiom"),
							"description" => __("Display posts only with this status", "axiom"),
							"class" => "",
							"value" => array(
								__('Publish', 'axiom') => 'publish',
								__('Protected', 'axiom') => 'protected',
								__('Private', 'axiom') => 'private',
								__('Pending', 'axiom') => 'pending',
								__('Draft', 'axiom') => 'draft'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Page extends AXIOM_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product",
					"name" => __("Product", "axiom"),
					"description" => __("WooCommerce shortcode: display one product", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product',
					"class" => "trx_sc_single trx_sc_product",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => __("SKU", "axiom"),
							"description" => __("Product's SKU code", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => __("ID", "axiom"),
							"description" => __("Product's ID", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product extends AXIOM_VC_ShortCodeSingle {}
			
			
				// WooCommerce - Best Selling Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "best_selling_products",
					"name" => __("Best Selling Products", "axiom"),
					"description" => __("WooCommerce shortcode: show best selling products", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_best_selling_products',
					"class" => "trx_sc_single trx_sc_best_selling_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiom"),
							"description" => __("How many products showed", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiom"),
							"description" => __("How many columns per row use for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Best_Selling_Products extends AXIOM_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Recent Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "recent_products",
					"name" => __("Recent Products", "axiom"),
					"description" => __("WooCommerce shortcode: show recent products", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_recent_products',
					"class" => "trx_sc_single trx_sc_recent_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiom"),
							"description" => __("How many products showed", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiom"),
							"description" => __("How many columns per row use for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiom') => 'date',
								__('Title', 'axiom') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Recent_Products extends AXIOM_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Related Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "related_products",
					"name" => __("Related Products", "axiom"),
					"description" => __("WooCommerce shortcode: show related products", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_related_products',
					"class" => "trx_sc_single trx_sc_related_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "posts_per_page",
							"heading" => __("Number", "axiom"),
							"description" => __("How many products showed", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiom"),
							"description" => __("How many columns per row use for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiom') => 'date',
								__('Title', 'axiom') => 'title'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Related_Products extends AXIOM_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Featured Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "featured_products",
					"name" => __("Featured Products", "axiom"),
					"description" => __("WooCommerce shortcode: show featured products", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_featured_products',
					"class" => "trx_sc_single trx_sc_featured_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiom"),
							"description" => __("How many products showed", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiom"),
							"description" => __("How many columns per row use for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiom') => 'date',
								__('Title', 'axiom') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Featured_Products extends AXIOM_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Top Rated Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "top_rated_products",
					"name" => __("Top Rated Products", "axiom"),
					"description" => __("WooCommerce shortcode: show top rated products", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_top_rated_products',
					"class" => "trx_sc_single trx_sc_top_rated_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiom"),
							"description" => __("How many products showed", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiom"),
							"description" => __("How many columns per row use for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiom') => 'date',
								__('Title', 'axiom') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Top_Rated_Products extends AXIOM_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Sale Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "sale_products",
					"name" => __("Sale Products", "axiom"),
					"description" => __("WooCommerce shortcode: list products on sale", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_sale_products',
					"class" => "trx_sc_single trx_sc_sale_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiom"),
							"description" => __("How many products showed", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiom"),
							"description" => __("How many columns per row use for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiom') => 'date',
								__('Title', 'axiom') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Sale_Products extends AXIOM_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product Category
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_category",
					"name" => __("Products from category", "axiom"),
					"description" => __("WooCommerce shortcode: list products in specified category(-ies)", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_category',
					"class" => "trx_sc_single trx_sc_product_category",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiom"),
							"description" => __("How many products showed", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiom"),
							"description" => __("How many columns per row use for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiom') => 'date',
								__('Title', 'axiom') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "category",
							"heading" => __("Categories", "axiom"),
							"description" => __("Comma separated category slugs", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "operator",
							"heading" => __("Operator", "axiom"),
							"description" => __("Categories operator", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('IN', 'axiom') => 'IN',
								__('NOT IN', 'axiom') => 'NOT IN',
								__('AND', 'axiom') => 'AND'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Category extends AXIOM_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "products",
					"name" => __("Products", "axiom"),
					"description" => __("WooCommerce shortcode: list all products", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_products',
					"class" => "trx_sc_single trx_sc_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "skus",
							"heading" => __("SKUs", "axiom"),
							"description" => __("Comma separated SKU codes of products", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => __("IDs", "axiom"),
							"description" => __("Comma separated ID of products", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiom"),
							"description" => __("How many columns per row use for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiom') => 'date',
								__('Title', 'axiom') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Products extends AXIOM_VC_ShortCodeSingle {}
			
			
			
			
				// WooCommerce - Product Attribute
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_attribute",
					"name" => __("Products by Attribute", "axiom"),
					"description" => __("WooCommerce shortcode: show products with specified attribute", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_attribute',
					"class" => "trx_sc_single trx_sc_product_attribute",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => __("Number", "axiom"),
							"description" => __("How many products showed", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiom"),
							"description" => __("How many columns per row use for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiom') => 'date',
								__('Title', 'axiom') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "attribute",
							"heading" => __("Attribute", "axiom"),
							"description" => __("Attribute name", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "filter",
							"heading" => __("Filter", "axiom"),
							"description" => __("Attribute value", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Attribute extends AXIOM_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products Categories
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_categories",
					"name" => __("Product Categories", "axiom"),
					"description" => __("WooCommerce shortcode: show categories with products", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_categories',
					"class" => "trx_sc_single trx_sc_product_categories",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "number",
							"heading" => __("Number", "axiom"),
							"description" => __("How many categories showed", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => __("Columns", "axiom"),
							"description" => __("How many columns per row use for categories output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => __("Order by", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'axiom') => 'date',
								__('Title', 'axiom') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => __("Order", "axiom"),
							"description" => __("Sorting order for products output", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($AXIOM_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "parent",
							"heading" => __("Parent", "axiom"),
							"description" => __("Parent category slug", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "date",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => __("IDs", "axiom"),
							"description" => __("Comma separated ID of products", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "hide_empty",
							"heading" => __("Hide empty", "axiom"),
							"description" => __("Hide empty categories", "axiom"),
							"class" => "",
							"value" => array("Hide empty" => "1" ),
							"type" => "checkbox"
						)
					)
				) );
				
				class WPBakeryShortCode_Products_Categories extends AXIOM_VC_ShortCodeSingle {}
			
				/*
			
				// WooCommerce - Add to cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "add_to_cart",
					"name" => __("Add to cart", "axiom"),
					"description" => __("WooCommerce shortcode: Display a single product price + cart button", "axiom"),
					"category" => __('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_add_to_cart',
					"class" => "trx_sc_single trx_sc_add_to_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "id",
							"heading" => __("ID", "axiom"),
							"description" => __("Product's ID", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "sku",
							"heading" => __("SKU", "axiom"),
							"description" => __("Product's SKU code", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "quantity",
							"heading" => __("Quantity", "axiom"),
							"description" => __("How many item add", "axiom"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "show_price",
							"heading" => __("Show price", "axiom"),
							"description" => __("Show price near button", "axiom"),
							"class" => "",
							"value" => array("Show price" => "true" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "class",
							"heading" => __("Class", "axiom"),
							"description" => __("CSS class", "axiom"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "style",
							"heading" => __("CSS style", "axiom"),
							"description" => __("CSS style for additional decoration", "axiom"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Add_To_Cart extends AXIOM_VC_ShortCodeSingle {}
				*/
			}

		}
	}
}
?>