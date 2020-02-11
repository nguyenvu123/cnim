<?php

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_options_settings_theme_setup2' ) ) {
	add_action( 'axiom_action_after_init_theme', 'axiom_options_settings_theme_setup2', 1 );
	function axiom_options_settings_theme_setup2() {
		if (axiom_options_is_used()) {
			global $AXIOM_GLOBALS;
			// Replace arrays with actual parameters
			$lists = array();
			foreach ($AXIOM_GLOBALS['options'] as $k=>$v) {
				if (isset($v['options']) && is_array($v['options'])) {
					foreach ($v['options'] as $k1=>$v1) {
						if (axiom_substr($k1, 0, 7) == '$axiom_' || axiom_substr($v1, 0, 7) == '$axiom_') {
							$list_func = axiom_substr(axiom_substr($k1, 0, 7) == '$axiom_' ? $k1 : $v1, 1);
							unset($AXIOM_GLOBALS['options'][$k]['options'][$k1]);
							if (isset($lists[$list_func]))
								$AXIOM_GLOBALS['options'][$k]['options'] = axiom_array_merge($AXIOM_GLOBALS['options'][$k]['options'], $lists[$list_func]);
							else {
								if (function_exists($list_func)) {
									$AXIOM_GLOBALS['options'][$k]['options'] = $lists[$list_func] = axiom_array_merge($AXIOM_GLOBALS['options'][$k]['options'], $list_func == 'axiom_get_list_menus' ? $list_func(true) : $list_func());
							   	} else
							   		echo sprintf(__('Wrong function name %s in the theme options array', 'axiom'), $list_func);
							}
						}
					}
				}
			}
		}
	}
}

// Reset old Theme Options on theme first run
if ( !function_exists( 'axiom_options_reset' ) ) {
	function axiom_options_reset($clear=true) {
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(axiom_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'axiom_'.strip_tags($slug).'_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "axiom_options%"');
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}

// Prepare default Theme Options
if ( !function_exists( 'axiom_options_settings_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_options_settings_theme_setup', 2 );	// Priority 1 for add axiom_filter handlers
	function axiom_options_settings_theme_setup() {
		global $AXIOM_GLOBALS;
		
		axiom_options_reset(false);
		
		// Prepare arrays 
		$AXIOM_GLOBALS['options_params'] = array(
			'list_fonts'		=> array('$axiom_get_list_fonts' => ''),
			'list_fonts_styles'	=> array('$axiom_get_list_fonts_styles' => ''),
			'list_socials' 		=> array('$axiom_get_list_socials' => ''),
			'list_icons' 		=> array('$axiom_get_list_icons' => ''),
			'list_posts_types' 	=> array('$axiom_get_list_posts_types' => ''),
			'list_categories' 	=> array('$axiom_get_list_categories' => ''),
			'list_menus'		=> array('$axiom_get_list_menus' => ''),
			'list_sidebars'		=> array('$axiom_get_list_sidebars' => ''),
			'list_positions' 	=> array('$axiom_get_list_sidebars_positions' => ''),
			'list_tints'	 	=> array('$axiom_get_list_bg_tints' => ''),
			'list_sidebar_styles' => array('$axiom_get_list_sidebar_styles' => ''),
			'list_skins'		=> array('$axiom_get_list_skins' => ''),
			'list_color_schemes'=> array('$axiom_get_list_color_schemes' => ''),
			'list_body_styles'	=> array('$axiom_get_list_body_styles' => ''),
			'list_blog_styles'	=> array('$axiom_get_list_templates_blog' => ''),
			'list_single_styles'=> array('$axiom_get_list_templates_single' => ''),
			'list_article_styles'=> array('$axiom_get_list_article_styles' => ''),
			'list_animations_in' => array('$axiom_get_list_animations_in' => ''),
			'list_animations_out'=> array('$axiom_get_list_animations_out' => ''),
			'list_filters'		=> array('$axiom_get_list_portfolio_filters' => ''),
			'list_hovers'		=> array('$axiom_get_list_hovers' => ''),
			'list_hovers_dir'	=> array('$axiom_get_list_hovers_directions' => ''),
			'list_sliders' 		=> array('$axiom_get_list_sliders' => ''),
			'list_popups' 		=> array('$axiom_get_list_popup_engines' => ''),
			'list_gmap_styles' 	=> array('$axiom_get_list_googlemap_styles' => ''),
			'list_yes_no' 		=> array('$axiom_get_list_yesno' => ''),
			'list_on_off' 		=> array('$axiom_get_list_onoff' => ''),
			'list_show_hide' 	=> array('$axiom_get_list_showhide' => ''),
			'list_sorting' 		=> array('$axiom_get_list_sortings' => ''),
			'list_ordering' 	=> array('$axiom_get_list_orderings' => ''),
			'list_locations' 	=> array('$axiom_get_list_dedicated_locations' => '')
			);


		// Theme options array
		$AXIOM_GLOBALS['options'] = array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => __('Customization', 'axiom'),
					"start" => "partitions",
					"override" => "category,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> General
		//-------------------------------------------------
		
		'customization_general' => array(
					"title" => __('General', 'axiom'),
					"override" => "category,page,post",
					"icon" => 'iconadmin-cog',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_custom_1' => array(
					"title" => __('Theme customization general parameters', 'axiom'),
					"desc" => __('Select main theme skin, customize colors and enable responsive layouts for the small screens', 'axiom'),
					"override" => "category,page,post",
					"type" => "info"
					),
		
		'theme_skin' => array(
					"title" => __('Select theme skin', 'axiom'),
					"desc" => __('Select skin for the theme decoration', 'axiom'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "default",
					"options" => $AXIOM_GLOBALS['options_params']['list_skins'],
					"type" => "select"
					),

		'show_post_icon' => array(
			"title" => __('Show post/category icon', 'axiom'),
			"desc" => __('Do you want to show post/category  name in some layouts?', 'axiom'),
			"override" => "category,post",
			"std" => "no",
			"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
			"type" => "switch"
		),
		
		"icon" => array(
					"title" => __('Select icon', 'axiom'),
					"desc" => __('Select icon for output before post/category name in some layouts', 'axiom'),
					"divider" => false,
					"override" => "category,post",
					"std" => "",
					"options" => $AXIOM_GLOBALS['options_params']['list_icons'],
					"style" => "select",
					"type" => "icons"
					),

		"link_color" => array(
					"title" => __('Color 1', 'axiom'),
					"desc" => __('Theme color 1', 'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),

			/*"link_dark" => array(
						"title" => __('Links dark color', 'axiom'),
						"desc" => __('Used as background color for the buttons, hover states and some other elements', 'axiom'),
						"divider" => false,
						"override" => "category,post,page",
						"std" => "",
					"type" => "color"),*/

		"menu_color" => array(
					"title" => __('Color 2', 'axiom'),
					"desc" => __('Theme color 2', 'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),

			/*"menu_dark" => array(
						"title" => __('Main menu dark color', 'axiom'),
						"desc" => __('Used as text color for the menu items (in the Light style), as background color for the selected menu item, etc.', 'axiom'),
						"divider" => false,
						"override" => "category,post,page",
						"std" => "",
						"type" => "color"),*/

		"user_color" => array(
					"title" => __('Color 3', 'axiom'),
					"desc" => __('Theme color 3','axiom'),
					"override" => "category,post,page",
					"std" => "transparent",
					"type" => "hidden"),

/*		"user_dark" => array(
					"title" => __('User menu dark color', 'axiom'),
					"desc" => __('Used as background color for the selected user menu item, etc.', 'axiom'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),*/


		'css_animation' => array(
					"title" => __('Extended CSS animations', 'axiom'),
					"desc" => __('Do you want use extended animations effects on your site?', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => __('Remember visitor\'s settings', 'axiom'),
					"desc" => __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'axiom'),
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => __('Responsive Layouts', 'axiom'),
					"desc" => __('Do you want use responsive layouts on small screen or still use main layout?', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'info_custom_15' => array(
			"title" => __('Additional options', 'axiom'),
			"desc" => __('Additional options here', 'axiom'),
			"override" => "category,page,post",
			"type" => "info"
		),

		'show_scroll_to_top' => array(
			"title" => __('Show scroll to top', 'axiom'),
			"desc" => __('Do you want show "scroll to top" button?', 'axiom'),
			"std" => "yes",
			"override" => "page,post",
			"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
			"type" => "switch"
		),

		'info_custom_2' => array(
					"title" => __('Additional CSS and HTML/JS code', 'axiom'),
					"desc" => __('Put here your custom CSS and JS code', 'axiom'),
					"override" => "category,page,post",
					"type" => "info"
					),

		'custom_css' => array(
					"title" => __('Your CSS code',  'axiom'),
					"desc" => __('Put here your css code to correct main theme styles',  'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),

		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => __('Body style', 'axiom'),
					"override" => "category,post,page",
					"icon" => 'iconadmin-picture-1',
					"type" => "tab"
					),
		
		'info_custom_3' => array(
					"title" => __('Body parameters', 'axiom'),
					"desc" => __('Background color, pattern and image used only for fixed body style.', 'axiom'),
					"override" => "category,post,page",
					"type" => "info"
					),
					
		'body_style' => array(
					"title" => __('Body style', 'axiom'),
					"desc" => __('Select body style:<br><b>boxed</b> - if you want use background color and/or image,<br><b>wide</b> - page fill whole window with centered content,<br><b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings),<br><b>fullscreen</b> - page content fill whole window without any paddings', 'axiom'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "wide",
					"options" => $AXIOM_GLOBALS['options_params']['list_body_styles'],
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_filled' => array(
					"title" => __('Fill body', 'axiom'),
					"desc" => __('Fill the body background with the solid color (white or grey) or leave it transparend to show background image (or video)', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'load_bg_image' => array(
					"title" => __('Load background image', 'axiom'),
					"desc" => __('Always load background images or only for boxed body style', 'axiom'),
					"override" => "category,post,page",
					"std" => "boxed",
					"size" => "medium",
					"options" => array(
						'boxed' => __('Boxed', 'axiom'),
						'always' => __('Always', 'axiom')
					),
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => __('Background color',  'axiom'),
					"desc" => __('Body background color',  'axiom'),
					"override" => "category,post,page",
					"std" => "#bfbfbf",
					"type" => "color"
					),
		
		'bg_pattern' => array(
					"title" => __('Background predefined pattern',  'axiom'),
					"desc" => __('Select theme background pattern (first case - without pattern)',  'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"options" => array(
						0 => axiom_get_file_url('/images/spacer.png'),
						1 => axiom_get_file_url('/images/bg/pattern_1.png'),
						2 => axiom_get_file_url('/images/bg/pattern_2.png'),
						3 => axiom_get_file_url('/images/bg/pattern_3.png'),
						4 => axiom_get_file_url('/images/bg/pattern_4.png'),
						5 => axiom_get_file_url('/images/bg/pattern_5.png'),
						6 => axiom_get_file_url('/images/bg/pattern_6.png'),
						7 => axiom_get_file_url('/images/bg/pattern_7.png'),
						8 => axiom_get_file_url('/images/bg/pattern_8.png'),
						9 => axiom_get_file_url('/images/bg/pattern_9.png')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_custom_pattern' => array(
					"title" => __('Background custom pattern',  'axiom'),
					"desc" => __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => __('Background predefined image',  'axiom'),
					"desc" => __('Select theme background image (first case - without image)',  'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"options" => array(
						0 => axiom_get_file_url('/images/spacer.png'),
						1 => axiom_get_file_url('/images/bg/image_1_thumb.jpg'),
						2 => axiom_get_file_url('/images/bg/image_2_thumb.jpg'),
						3 => axiom_get_file_url('/images/bg/image_3_thumb.jpg'),
						4 => axiom_get_file_url('/images/bg/image_4_thumb.jpg'),
						5 => axiom_get_file_url('/images/bg/image_5_thumb.jpg'),
						6 => axiom_get_file_url('/images/bg/image_6_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_custom_image' => array(
					"title" => __('Background custom image',  'axiom'),
					"desc" => __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "media"
					),
		
		'bg_custom_image_position' => array( 
					"title" => __('Background custom image position',  'axiom'),
					"desc" => __('Select custom image position',  'axiom'),
					"override" => "category,post,page",
					"std" => "left_top",
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'show_video_bg' => array(
					"title" => __('Show video background',  'axiom'),
					"desc" => __("Show video on the site background (only for Fullscreen body style)", 'axiom'),
					"override" => "category,post,page",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'video_bg_youtube_code' => array(
					"title" => __('Youtube code for video bg',  'axiom'),
					"desc" => __("Youtube code of video", 'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "text"
					),
		
		'video_bg_url' => array(
					"title" => __('Local video for video bg',  'axiom'),
					"desc" => __("URL to video-file (uploaded on your site)", 'axiom'),
					"readonly" =>false,
					"override" => "category,post,page",
					"before" => array(	'title' => __('Choose video', 'axiom'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => __( 'Choose Video', 'axiom'),
															'update' => __( 'Select Video', 'axiom')
														)
								),
					"std" => "",
					"type" => "media"
					),
		
		'video_bg_overlay' => array(
					"title" => __('Use overlay for video bg', 'axiom'),
					"desc" => __('Use overlay texture for the video background', 'axiom'),
					"override" => "category,post,page",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		
		
		// Customization -> Logo
		//-------------------------------------------------
		
		'customization_logo' => array(
					"title" => __('Logo', 'axiom'),
					"override" => "category,post,page",
					"icon" => 'iconadmin-heart-1',
					"type" => "tab"
					),
		
		'info_custom_4' => array(
					"title" => __('Main logo', 'axiom'),
					"desc" => __('Select or upload logos for the site\'s header and select it position', 'axiom'),
					"override" => "category,post,page",
					"type" => "info"
					),

		'favicon' => array(
					"title" => __('Favicon', 'axiom'),
					"desc" => __('Upload a 16px x 16px image that will represent your website\'s favicon.<br /><em>To ensure cross-browser compatibility, we recommend converting the favicon into .ico format before uploading. (<a href="http://www.favicon.cc/">www.favicon.cc</a>)</em>', 'axiom'),
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_dark' => array(
					"title" => __('Logo image (dark header)', 'axiom'),
					"desc" => __('Main logo image for the dark header', 'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_light' => array(
					"title" => __('Logo image (light header)', 'axiom'),
					"desc" => __('Main logo image for the light header', 'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => __('Logo image (fixed header)', 'axiom'),
					"desc" => __('Logo image for the header (if menu is fixed after the page is scrolled)', 'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => __('Logo text', 'axiom'),
					"desc" => __('Logo text - display it after logo image', 'axiom'),
					"override" => "category,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_slogan' => array(
					"title" => __('Logo slogan', 'axiom'),
					"desc" => __('Logo slogan - display it under logo image (instead the site slogan)', 'axiom'),
					"override" => "category,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => __('Logo height', 'axiom'),
					"desc" => __('Height for the logo in the header area', 'axiom'),
					"override" => "category,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => __('Logo top offset', 'axiom'),
					"desc" => __('Top offset for the logo in the header area', 'axiom'),
					"override" => "category,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),

		'logo_align' => array(
					"title" => __('Logo alignment', 'axiom'),
					"desc" => __('Logo alignment (only if logo above menu)', 'axiom'),
					"override" => "category,post,page",
					"std" => "left",
					"options" =>  array("left"=>__("Left", 'axiom'), "center"=>__("Center", 'axiom'), "right"=>__("Right", 'axiom')),
					"dir" => "horizontal",
					"type" => "checklist"
					),

		'iinfo_custom_5' => array(
					"title" => __('Logo for footer', 'axiom'),
					"desc" => __('Select or upload logos for the site\'s footer and set it height', 'axiom'),
					"override" => "category,post,page",
					"type" => "info"
					),

		'logo_footer' => array(
					"title" => __('Logo image for footer', 'axiom'),
					"desc" => __('Logo image for the footer', 'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => __('Logo height', 'axiom'),
					"desc" => __('Height for the logo in the footer area (in contacts)', 'axiom'),
					"override" => "category,post,page",
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
		
		
		
		// Customization -> Menus
		//-------------------------------------------------
		
		"customization_menus" => array(
					"title" => __('Menus', 'axiom'),
					"override" => "category,post,page",
					"icon" => 'iconadmin-menu',
					"type" => "tab"),
		
		"info_custom_6" => array(
					"title" => __('Top panel', 'axiom'),
					"desc" => __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'axiom'),
					"override" => "category,post,page",
					"type" => "info"),
		
		"top_panel_position" => array( 
					"title" => __('Top panel position', 'axiom'),
					"desc" => __('Select position for the top panel with logo and main menu', 'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "above",
					"options" => array(
						'hide'  => __('Hide', 'axiom'),
						'above' => __('Above slider', 'axiom'),
						'below' => __('Below slider', 'axiom'),
						'over'  => __('Over slider', 'axiom')
					),
					"type" => "checklist"),
		
		"top_panel_style" => array( 
					"title" => __('Top panel style', 'axiom'),
					"desc" => __('Select background style for the top panel with logo and main menu', 'axiom'),
					"override" => "category,post,page",
					"std" => "dark",
					"options" => array(
						'dark' => __('Dark', 'axiom'),
						'light' => __('Light', 'axiom')
					),
					"type" => "checklist"),
		
		"top_panel_opacity" => array( 
					"title" => __('Top panel opacity', 'axiom'),
					"desc" => __('Select background opacity for the top panel with logo and main menu', 'axiom'),
					"override" => "category,post,page",
					"std" => "solid",
					"options" => array(
						'solid' => __('Solid', 'axiom'),
						'transparent' => __('Transparent', 'axiom')
					),
					"type" => "checklist"),
		
		'top_panel_bg_color' => array(
					"title" => __('Top panel bg color',  'axiom'),
					"desc" => __('Background color for the top panel',  'axiom'),
					"override" => "category,post,page",
					"std" => "transparent",
					"type" => "color"
					),
		
		"top_panel_bg_image" => array( 
					"title" => __('Top panel bg image', 'axiom'),
					"desc" => __('Upload top panel background image', 'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "media"),
		
		
		"info_custom_7" => array( 
					"title" => __('Main menu style and position', 'axiom'),
					"desc" => __('Select the Main menu style and position', 'axiom'),
					"override" => "category,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => __('Select main menu',  'axiom'),
					"desc" => __('Select main menu for the current page',  'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "default",
					"options" => $AXIOM_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"menu_position" => array( 
					"title" => __('Main menu position', 'axiom'),
					"desc" => __('Attach main menu to top of window then page scroll down', 'axiom'),
					"override" => "category,post,page",
					"std" => "fixed",
					"options" => array("fixed"=>__("Fix menu position", 'axiom'), "none"=>__("Don't fix menu position", 'axiom')),
					"dir" => "vertical",
					"type" => "radio"),
		
		"menu_align" => array( 
					"title" => __('Main menu alignment', 'axiom'),
					"desc" => __('Main menu alignment', 'axiom'),
					"override" => "category,post,page",
					"std" => "right",
					"options" => array(
						"left"   => __("Left (under logo)", 'axiom'),
						"center" => __("Center (under logo)", 'axiom'),
						"right"	 => __("Right (at same line with logo)", 'axiom')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => __('Main menu slider', 'axiom'),
					"desc" => __('Use slider background for main menu items', 'axiom'),
					"std" => "no",
					"type" => "switch",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no']),

		"menu_animation_in" => array( 
					"title" => __('Submenu show animation', 'axiom'),
					"desc" => __('Select animation to show submenu ', 'axiom'),
					"std" => "none",
					"type" => "select",
					"options" => $AXIOM_GLOBALS['options_params']['list_animations_in']),

		"menu_animation_out" => array( 
					"title" => __('Submenu hide animation', 'axiom'),
					"desc" => __('Select animation to hide submenu ', 'axiom'),
					"std" => "none",
					"type" => "select",
					"options" => $AXIOM_GLOBALS['options_params']['list_animations_out']),
		
		"menu_relayout" => array( 
					"title" => __('Main menu relayout', 'axiom'),
					"desc" => __('Allow relayout main menu if window width less then this value', 'axiom'),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => __('Main menu responsive', 'axiom'),
					"desc" => __('Allow responsive version for the main menu if window width less then this value', 'axiom'),
					"std" => 640,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => __('Submenu width', 'axiom'),
					"desc" => __('Width for dropdown menus in main menu', 'axiom'),
					"override" => "category,post,page",
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_custom_8" => array(
					"title" => __("User's menu area components", 'axiom'),
					"desc" => __("Select parts for the user's menu area", 'axiom'),
					"override" => "category,page,post",
					"type" => "info"),
		
		"show_menu_user" => array(
					"title" => __('Show user menu area', 'axiom'),
					"desc" => __('Show user menu area on top of page', 'axiom'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => __('Select user menu',  'axiom'),
					"desc" => __('Select user menu for the current page',  'axiom'),
					"override" => "category,post,page",
					"std" => "default",
					"options" => $AXIOM_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"show_contact_info" => array(
					"title" => __('Show contact info', 'axiom'),
					"desc" => __("Show the contact details for the owner of the site at the top left corner of the page", 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_currency" => array(
					"title" => __('Show currency selector', 'axiom'),
					"desc" => __('Show currency selector in the user menu', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_cart" => array(
					"title" => __('Show cart button', 'axiom'),
					"desc" => __('Show cart button in the user menu', 'axiom'),
					"override" => "category,post,page",
					"std" => "shop",
					"options" => array(
						'hide'   => __('Hide', 'axiom'),
						'always' => __('Always', 'axiom'),
						'shop'   => __('Only on shop pages', 'axiom')
					),
					"type" => "checklist"),
		
		"show_languages" => array(
					"title" => __('Show language selector', 'axiom'),
					"desc" => __('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => __('Show Login/Logout buttons', 'axiom'),
					"desc" => __('Show Login and Logout buttons in the user menu area', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => __('Show bookmarks', 'axiom'),
					"desc" => __('Show bookmarks selector in the user menu', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"info_custom_14" => array(
					"title" => __("Side Menus and Panels", 'axiom'),
					"desc" => __("Side Menus for the page.", 'axiom'),
					"override" => "category,page,post",
					"type" => "info"),

		'show_theme_customizer' => array(
					"title" => __('Show customizer', 'axiom'),
					"desc" => __('Do you want to show customizer?', 'axiom'),
					"override" => "category,post,page",
					"std" => "no",
					"divider" => false,
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
		),

		"menu_left" => array(
					"title" => __('Select left menu',  'axiom'),
					"desc" => __('Select menu for the left sidebar for the current page',  'axiom'),
					"override" => "category,post,page",
					"std" => "no",
					"divider" => false,
					"options" => $AXIOM_GLOBALS['options_params']['list_menus'],
					"type" => "hidden"),

/*		"customizer_demo" => array(
					"title" => __('Left Panel Menu demo time', 'axiom'),
					"desc" => __('Timer for demo mode for the Left Panel Menu (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'axiom'),
					"divider" => true,
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),*/

		'show_left_panel' => array(
					"title" => __('Show left panel', 'axiom'),
					"desc" => __('Do you want to show left panel?', 'axiom'),
					"override" => "category,post,page",
					"std" => "no",
					"divider" => true,
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
				),

		"menu_panel" => array(
					"title" => __('Select panel menu',  'axiom'),
					"desc" => __('Select panel menu for the current page',  'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"divider" => false,
					"options" => $AXIOM_GLOBALS['options_params']['list_menus'],
					"type" => "select"),

		"info_custom_9" => array(
					"title" => __("Table of Contents (TOC)", 'axiom'),
					"desc" => __("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'axiom'),
					"override" => "category,page,post",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => __('TOC position', 'axiom'),
					"desc" => __('Show TOC for the current page', 'axiom'),
					"override" => "category,post,page",
					"std" => "float",
					"options" => array(
						'hide'  => __('Hide', 'axiom'),
						'fixed' => __('Fixed', 'axiom'),
						'float' => __('Float', 'axiom')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => __('Add "Home" into TOC', 'axiom'),
					"desc" => __('Automatically add "Home" item into table of contents - return to home page of the site', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => __('Add "To Top" into TOC', 'axiom'),
					"desc" => __('Automatically add "To Top" item into table of contents - scroll to top of the page', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => __('Sidebars', 'axiom'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,post,page",
					"type" => "tab"),
		
		"info_custom_10" => array( 
					"title" => __('Custom sidebars', 'axiom'),
					"desc" => __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'axiom'),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => __('Custom sidebars',  'axiom'),
					"desc" => __('Manage custom sidebars. You can use it with each category (page, post) independently',  'axiom'),
					"divider" => false,
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_custom_11" => array(
					"title" => __('Sidebars settings', 'axiom'),
					"desc" => __('Show / Hide and Select sidebar in each location', 'axiom'),
					"override" => "category,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => __('Show main sidebar',  'axiom'),
					"desc" => __('Select style for the main sidebar or hide it',  'axiom'),
					"override" => "category,post,page",
					"std" => "light",
					"options" => $AXIOM_GLOBALS['options_params']['list_sidebar_styles'],
					"dir" => "horizontal",
					"type" => "checklist"),
		
		'sidebar_main_position' => array( 
					"title" => __('Main sidebar position',  'axiom'),
					"desc" => __('Select main sidebar position on blog page',  'axiom'),
					"override" => "category,post,page",
					"std" => "right",
					"options" => $AXIOM_GLOBALS['options_params']['list_positions'],
					"size" => "medium",
					"type" => "switch"),
		
		"sidebar_main" => array( 
					"title" => __('Select main sidebar',  'axiom'),
					"desc" => __('Select main sidebar for the blog page',  'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "sidebar_main",
					"options" => $AXIOM_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"show_sidebar_footer" => array(
					"title" => __('Show footer sidebar', 'axiom'),
					"desc" => __('Select style for the footer sidebar or hide it', 'axiom'),
					"override" => "category,post,page",
					"std" => "light",
					"options" => $AXIOM_GLOBALS['options_params']['list_sidebar_styles'],
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => __('Select footer sidebar',  'axiom'),
					"desc" => __('Select footer sidebar for the blog page',  'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "sidebar_footer",
					"options" => $AXIOM_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => __('Footer sidebar columns',  'axiom'),
					"desc" => __('Select columns number for the footer sidebar',  'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => __('Slider', 'axiom'),
					"icon" => "iconadmin-picture",
					"override" => "category,page",
					"type" => "tab"),
		
		"info_custom_13" => array(
					"title" => __('Main slider parameters', 'axiom'),
					"desc" => __('Select parameters for main slider (you can override it in each category and page)', 'axiom'),
					"override" => "category,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => __('Show Slider', 'axiom'),
					"desc" => __('Do you want to show slider on each page (post)', 'axiom'),
					"divider" => false,
					"override" => "category,page",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => __('Slider display', 'axiom'),
					"desc" => __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'axiom'),
					"override" => "category,page",
					"std" => "none",
					"options" => array(
						"boxed"=>__("Boxed", 'axiom'),
						"fullwide"=>__("Fullwide", 'axiom'),
						"fullscreen"=>__("Fullscreen", 'axiom')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => __("Height (in pixels)", 'axiom'),
					"desc" => __("Slider height (in pixels) - only if slider display with fixed height.", 'axiom'),
					"override" => "category,page",
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => __('Slider engine', 'axiom'),
					"desc" => __('What engine use to show slider?', 'axiom'),
					"override" => "category,page",
					"std" => "flex",
					"options" => $AXIOM_GLOBALS['options_params']['list_sliders'],
					"type" => "radio"),
		
		"slider_alias" => array(
					"title" => __('Layer Slider: Alias (for Revolution) or ID (for Royal)',  'axiom'),
					"desc" => __("Revolution Slider alias or Royal Slider ID (see in slider settings on plugin page)", 'axiom'),
					"override" => "category,page",
					"std" => "",
					"type" => "text"),
		
		"slider_category" => array(
					"title" => __('Posts Slider: Category to show', 'axiom'),
					"desc" => __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'axiom'),
					"override" => "category,page",
					"std" => "",
					"options" => axiom_array_merge(array(0 => __('- Select category -', 'axiom')), $AXIOM_GLOBALS['options_params']['list_categories']),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => __('Posts Slider: Number posts or comma separated posts list',  'axiom'),
					"desc" => __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'axiom'),
					"override" => "category,page",
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => __("Posts Slider: Posts order by",  'axiom'),
					"desc" => __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'axiom'),
					"override" => "category,page",
					"std" => "date",
					"options" => $AXIOM_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"slider_order" => array(
					"title" => __("Posts Slider: Posts order", 'axiom'),
					"desc" => __('Select the desired ordering method for posts', 'axiom'),
					"override" => "category,page",
					"std" => "desc",
					"options" => $AXIOM_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => __("Posts Slider: Slide change interval", 'axiom'),
					"desc" => __("Interval (in ms) for slides change in slider", 'axiom'),
					"override" => "category,page",
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => __("Posts Slider: Pagination", 'axiom'),
					"desc" => __("Choose pagination style for the slider", 'axiom'),
					"override" => "category,page",
					"std" => "no",
					"options" => array(
						'no'   => __('None', 'axiom'),
						'yes'  => __('Dots', 'axiom'),
						'over' => __('Titles', 'axiom')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => __("Posts Slider: Show infobox", 'axiom'),
					"desc" => __("Do you want to show post's title, reviews rating and description on slides in slider", 'axiom'),
					"override" => "category,page",
					"std" => "slide",
					"options" => array(
						'no'    => __('None',  'axiom'),
						'slide' => __('Slide', 'axiom'),
						'fixed' => __('Fixed', 'axiom')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => __("Posts Slider: Show post's category", 'axiom'),
					"desc" => __("Do you want to show post's category on slides in slider", 'axiom'),
					"override" => "category,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => __("Posts Slider: Show post's reviews rating", 'axiom'),
					"desc" => __("Do you want to show post's reviews rating on slides in slider", 'axiom'),
					"override" => "category,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => __("Posts Slider: Show post's descriptions", 'axiom'),
					"desc" => __("How many characters show in the post's description in slider. 0 - no descriptions", 'axiom'),
					"override" => "category,page",
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		// Customization -> Header & Footer
		//-------------------------------------------------
		
		'customization_header_footer' => array(
					"title" => __("Header &amp; Footer", 'axiom'),
					"override" => "category,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),

		"info_header_footer" => array(
			"title" => __("Header/Footer settings", 'axiom'),
			"desc" => __("Global header/footer options", 'axiom'),
			"override" => "category,page,post",
			"type" => "info"),


		"custom_page_paddings" => array(
			"title" => __('Disable original page paddings', 'axiom'),
			"desc" => __('Disable original page paddings and use your own. Useful when creating custom pages.', 'axiom'),
			"override" => "category,post,page",
			"std" => "no",
			"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
			"type" => "switch"),

		"info_footer_1" => array(
					"title" => __("Header settings", 'axiom'),
					"desc" => __("Select components of the page header, set style and put the content for the user's header area", 'axiom'),
					"override" => "category,page,post",
					"type" => "info"),
		
		"show_user_header" => array(
					"title" => __("Show user's header", 'axiom'),
					"desc" => __("Show custom user's header", 'axiom'),
					"divider" => false,
					"override" => "category,page,post",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"user_header_content" => array(
					"title" => __("User's header content", 'axiom'),
					"desc" => __('Put header html-code and/or shortcodes here. You can use any html-tags and shortcodes', 'axiom'),
					"override" => "category,page,post",
					"std" => "",
					"rows" => "10",
					"type" => "editor"),

		"show_page_top" => array(
					"title" => __('Show Top of page section', 'axiom'),
					"desc" => __('Show top section with post/page/category title and breadcrumbs', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_page_title" => array(
					"title" => __('Show Page title', 'axiom'),
					"desc" => __('Show post/page/category title', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => __('Show Breadcrumbs', 'axiom'),
					"desc" => __('Show path to current category (post, page)', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => __('Breadcrumbs max nesting', 'axiom'),
					"desc" => __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'axiom'),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),
		
		
		
		
		"info_footer_2" => array(
					"title" => __("Footer settings", 'axiom'),
					"desc" => __("Select components of the footer, set style and put the content for the user's footer area", 'axiom'),
					"override" => "category,page,post",
					"type" => "info"),
		
		"show_user_footer" => array(
					"title" => __("Show user's footer", 'axiom'),
					"desc" => __("Show custom user's footer", 'axiom'),
					"divider" => false,
					"override" => "category,page,post",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"user_footer_content" => array(
					"title" => __("User's footer content", 'axiom'),
					"desc" => __('Put footer html-code and/or shortcodes here. You can use any html-tags and shortcodes', 'axiom'),
					"override" => "category,page,post",
					"std" => "",
					"rows" => "10",
					"type" => "editor"),
		
		"show_contacts_in_footer" => array(
					"title" => __('Show Contacts in footer', 'axiom'),
					"desc" => __('Show contact information area in footer: site logo, contact info and large social icons', 'axiom'),
					"override" => "category,post,page",
					"std" => "dark",
					"options" => array(
						'hide' 	=> __('Hide', 'axiom'),
						'light'	=> __('Light', 'axiom'),
						'dark'	=> __('Dark', 'axiom')
					),
					"dir" => "horizontal",
					"type" => "checklist"),

		"show_copyright_in_footer" => array(
					"title" => __('Show Copyright area in footer', 'axiom'),
					"desc" => __('Show area with copyright information and small social icons in footer', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"footer_copyright" => array(
					"title" => __('Footer copyright text',  'axiom'),
					"desc" => __("Copyright text to show in footer area (bottom of site)", 'axiom'),
					"override" => "category,page,post",
					"std" => "AxiomThemes &copy; 2014 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),
		
		
		"info_footer_3" => array(
					"title" => __('Testimonials in Footer', 'axiom'),
					"desc" => __('Select parameters for Testimonials in the Footer (you can override it in each category and page)', 'axiom'),
					"override" => "category,page,post",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => __('Show Testimonials in footer', 'axiom'),
					"desc" => __('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "none",
					"options" => $AXIOM_GLOBALS['options_params']['list_tints'],
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => __('Testimonials count', 'axiom'),
					"desc" => __('Number testimonials to show', 'axiom'),
					"override" => "category,post,page",
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),

		"testimonials_bg_image" => array( 
					"title" => __('Testimonials bg image', 'axiom'),
					"desc" => __('Select image or put image URL from other site to use it as testimonials block background', 'axiom'),
					"override" => "category,post,page",
					"readonly" => false,
					"std" => "",
					"type" => "media"),

		"testimonials_bg_color" => array( 
					"title" => __('Testimonials bg color', 'axiom'),
					"desc" => __('Select color to use it as testimonials block background', 'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),

		"testimonials_bg_overlay" => array( 
					"title" => __('Testimonials bg overlay', 'axiom'),
					"desc" => __('Select background color opacity to create overlay effect on background', 'axiom'),
					"override" => "category,post,page",
					"std" => 0,
					"step" => 0.1,
					"min" => 0,
					"max" => 1,
					"type" => "spinner"),
		
		
		"info_footer_4" => array(
					"title" => __('Twitter in Footer', 'axiom'),
					"desc" => __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'axiom'),
					"override" => "category,page,post",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => __('Show Twitter in footer', 'axiom'),
					"desc" => __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'axiom'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "none",
					"options" => $AXIOM_GLOBALS['options_params']['list_tints'],
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => __('Twitter count', 'axiom'),
					"desc" => __('Number twitter to show', 'axiom'),
					"override" => "category,post,page",
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),

		"twitter_bg_image" => array( 
					"title" => __('Twitter bg image', 'axiom'),
					"desc" => __('Select image or put image URL from other site to use it as Twitter block background', 'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "media"),

		"twitter_bg_color" => array( 
					"title" => __('Twitter bg color', 'axiom'),
					"desc" => __('Select color to use it as Twitter block background', 'axiom'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),

		"twitter_bg_overlay" => array( 
					"title" => __('Twitter bg overlay', 'axiom'),
					"desc" => __('Select background color opacity to create overlay effect on background', 'axiom'),
					"override" => "category,post,page",
					"std" => 0,
					"step" => 0.1,
					"min" => 0,
					"max" => 1,
					"type" => "spinner"),


		"info_footer_5" => array(
					"title" => __('Google map parameters', 'axiom'),
					"desc" => __('Select parameters for Google map (you can override it in each category and page)', 'axiom'),
					"override" => "category,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => __('Show Google Map', 'axiom'),
					"desc" => __('Do you want to show Google map on each page (post)', 'axiom'),
					"divider" => false,
					"override" => "category,page,post",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => __("Map height", 'axiom'),
					"desc" => __("Map height (default - in pixels, allows any CSS units of measure)", 'axiom'),
					"override" => "category,page",
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => __('Address to show on map',  'axiom'),
					"desc" => __("Enter address to show on map center", 'axiom'),
					"override" => "category,page,post",
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => __('Latitude and Longtitude to show on map',  'axiom'),
					"desc" => __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'axiom'),
					"override" => "category,page,post",
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => __('Google map initial zoom',  'axiom'),
					"desc" => __("Enter desired initial zoom for Google map", 'axiom'),
					"override" => "category,page,post",
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => __('Google map style',  'axiom'),
					"desc" => __("Select style to show Google map", 'axiom'),
					"override" => "category,page,post",
					"std" => 'style1',
					"options" => $AXIOM_GLOBALS['options_params']['list_gmap_styles'],
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => __('Google map marker',  'axiom'),
					"desc" => __("Select or upload png-image with Google map marker", 'axiom'),
					"std" => '',
					"type" => "media"),

		"info_footer_6" => array(
					"title" => __('Emergency Call', 'axiom'),
					"desc" => __('Select whether to show Emergency Call in footer or not', 'axiom'),
					"override" => "category,page,post",
					"type" => "hidden"), //info

		"show_emergency_call" => array(
					"title" => __('Show Emergency Call', 'axiom'),
					"desc" => __('Do you want to show Emergency Call on each page (post)', 'axiom'),
					"divider" => false,
					"override" => "category,page,post",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"), //switch
		
		
		
		
		// Customization -> Media
		//-------------------------------------------------
		
		'customization_media' => array(
					"title" => __('Media', 'axiom'),
					"override" => "category,post,page",
					"icon" => 'iconadmin-picture',
					"type" => "tab"),
		
		"info_media_1" => array(
					"title" => __('Retina ready', 'axiom'),
					"desc" => __("Additional parameters for the Retina displays", 'axiom'),
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => __('Image dimensions', 'axiom'),
					"desc" => __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'axiom'),
					"divider" => false,
					"std" => "1",
					"size" => "medium",
					"options" => array("1"=>__("Original", 'axiom'), "2"=>__("Retina", 'axiom')),
					"type" => "switch"),
		
		"info_media_2" => array(
					"title" => __('Media Substitution parameters', 'axiom'),
					"desc" => __("Set up the media substitution parameters and slider's options", 'axiom'),
					"override" => "category,page,post",
					"type" => "info"),
		
		"substitute_gallery" => array(
					"title" => __('Substitute standard Wordpress gallery', 'axiom'),
					"desc" => __('Substitute standard Wordpress gallery with our slider on the single pages', 'axiom'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"substitute_slider_engine" => array(
					"title" => __('Substitution Slider engine', 'axiom'),
					"desc" => __('What engine use to show slider instead standard gallery?', 'axiom'),
					"override" => "category,post,page",
					"std" => "swiper",
					"options" => array(
						//"chop" => __("Chop slider", 'axiom'),
						"swiper" => __("Swiper slider", 'axiom')
					),
					"type" => "radio"),
		
		"gallery_instead_image" => array(
					"title" => __('Show gallery instead featured image', 'axiom'),
					"desc" => __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => __('Max images number in the slider', 'axiom'),
					"desc" => __('Maximum images number from gallery into slider', 'axiom'),
					"override" => "category,post,page",
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => __('Gallery popup engine', 'axiom'),
					"desc" => __('Select engine to show popup windows with galleries', 'axiom'),
					"std" => "magnific",
					"options" => $AXIOM_GLOBALS['options_params']['list_popups'],
					"type" => "select"),
		
		"popup_gallery" => array(
					"title" => __('Enable Gallery mode in the popup', 'axiom'),
					"desc" => __('Enable Gallery mode in the popup or show only single image', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		"substitute_audio" => array(
					"title" => __('Substitute audio tags', 'axiom'),
					"desc" => __('Substitute audio tag with source from soundcloud to embed player', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => __('Substitute video tags', 'axiom'),
					"desc" => __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => __('Use Media Element script for audio and video tags', 'axiom'),
					"desc" => __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		// Customization -> Typography
		//-------------------------------------------------
		
		'customization_typography' => array(
					"title" => __("Typography", 'axiom'),
					"icon" => 'iconadmin-font',
					"type" => "tab"),
		
		"info_typo_1" => array(
					"title" => __('Typography settings', 'axiom'),
					"desc" => __('Select fonts, sizes and styles for the headings and paragraphs. You can use Google fonts and custom fonts.<br><br>How to install custom @font-face fonts into the theme?<br>All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!<br>Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.<br>Create your @font-face kit by using <a href="http://www.fontsquirrel.com/fontface/generator">Fontsquirrel @font-face Generator</a> and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install.', 'axiom'),
					"type" => "info"),
		
		"typography_custom" => array(
					"title" => __('Use custom typography', 'axiom'),
					"desc" => __('Use custom font settings or leave theme-styled fonts', 'axiom'),
					"divider" => false,
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"typography_h1_font" => array(
					"title" => __('Heading 1', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h1_size" => array(
					"title" => __('Size', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "48",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h1_lineheight" => array(
					"title" => __('Line height', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "60",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h1_weight" => array(
					"title" => __('Weight', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h1_style" => array(
					"title" => __('Style', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h1_color" => array(
					"title" => __('Color', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h2_font" => array(
					"title" => __('Heading 2', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h2_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "36",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h2_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "43",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h2_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h2_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h2_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h3_font" => array(
					"title" => __('Heading 3', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h3_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "24",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h3_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "28",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h3_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h3_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h3_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h4_font" => array(
					"title" => __('Heading 4', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h4_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "20",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h4_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "24",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h4_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h4_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h4_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h5_font" => array(
					"title" => __('Heading 5', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h5_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "18",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h5_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "20",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h5_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h5_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h5_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h6_font" => array(
					"title" => __('Heading 6', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h6_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "16",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h6_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "18",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h6_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h6_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h6_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_p_font" => array(
					"title" => __('Paragraph text', 'axiom'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Source Sans Pro",
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_p_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "14",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_p_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "21",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_p_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "300",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_p_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $AXIOM_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_p_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8 last",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		
		
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => __('Blog &amp; Single', 'axiom'),
					"icon" => "iconadmin-docs",
					"override" => "category,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => __('Stream page', 'axiom'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => __('Blog streampage parameters', 'axiom'),
					"desc" => __('Select desired blog streampage parameters (you can override it in each category)', 'axiom'),
					"override" => "category,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => __('Blog style', 'axiom'),
					"desc" => __('Select desired blog style', 'axiom'),
					"divider" => false,
					"override" => "category,page",
					"std" => "excerpt",
					"options" => $AXIOM_GLOBALS['options_params']['list_blog_styles'],
					"type" => "select"),
		
		"article_style" => array(
					"title" => __('Article style', 'axiom'),
					"desc" => __('Select article display method: boxed or stretch', 'axiom'),
					"override" => "category,page",
					"std" => "stretch",
					"options" => $AXIOM_GLOBALS['options_params']['list_article_styles'],
					"size" => "medium",
					"type" => "switch"),
		
		"hover_style" => array(
					"title" => __('Hover style', 'axiom'),
					"desc" => __('Select desired hover style (only for Blog style = Portfolio)', 'axiom'),
					"override" => "category,page",
					"std" => "square effect_shift",
					"options" => $AXIOM_GLOBALS['options_params']['list_hovers'],
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => __('Hover dir', 'axiom'),
					"desc" => __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'axiom'),
					"override" => "category,page",
					"std" => "left_to_right",
					"options" => $AXIOM_GLOBALS['options_params']['list_hovers_dir'],
					"type" => "select"),
		
		"dedicated_location" => array(
					"title" => __('Dedicated location', 'axiom'),
					"desc" => __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'axiom'),
					"override" => "category,page,post",
					"std" => "default",
					"options" => $AXIOM_GLOBALS['options_params']['list_locations'],
					"type" => "select"),
		
		"show_filters" => array(
					"title" => __('Show filters', 'axiom'),
					"desc" => __('Show filter buttons (only for Blog style = Portfolio, Masonry, Classic)', 'axiom'),
					"override" => "category,page",
					"std" => "hide",
					"options" => $AXIOM_GLOBALS['options_params']['list_filters'],
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => __('Blog posts sorted by', 'axiom'),
					"desc" => __('Select the desired sorting method for posts', 'axiom'),
					"override" => "category,page",
					"std" => "date",
					"options" => $AXIOM_GLOBALS['options_params']['list_sorting'],
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => __('Blog posts order', 'axiom'),
					"desc" => __('Select the desired ordering method for posts', 'axiom'),
					"override" => "category,page",
					"std" => "desc",
					"options" => $AXIOM_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => __('Blog posts per page',  'axiom'),
					"desc" => __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'axiom'),
					"override" => "category,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => __('Excerpt maxlength for streampage',  'axiom'),
					"desc" => __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'axiom'),
					"override" => "category,page",
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => __('Excerpt maxlength for classic and masonry',  'axiom'),
					"desc" => __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'axiom'),
					"override" => "category,page",
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => __('Single page', 'axiom'),
					"icon" => "iconadmin-doc",
					"override" => "category,post,page",
					"type" => "tab"),
		
		
		"info_blog_2" => array(
					"title" => __('Single (detail) pages parameters', 'axiom'),
					"desc" => __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'axiom'),
					"override" => "category,post,page",
					"type" => "info"),
		
		"single_style" => array(
					"title" => __('Single page style', 'axiom'),
					"desc" => __('Select desired style for single page', 'axiom'),
					"divider" => false,
					"override" => "category,page,post",
					"std" => "single-standard",
					"options" => $AXIOM_GLOBALS['options_params']['list_single_styles'],
					"dir" => "horizontal",
					"type" => "radio"),
		
		"allow_editor" => array(
					"title" => __('Frontend editor',  'axiom'),
					"desc" => __("Allow authors to edit their posts in frontend area)", 'axiom'),
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_featured_image" => array(
					"title" => __('Show featured image before post',  'axiom'),
					"desc" => __("Show featured image (if selected) before post content on single pages", 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => __('Show post title', 'axiom'),
					"desc" => __('Show area with post title on single pages', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => __('Show post title on links, chat, quote, status', 'axiom'),
					"desc" => __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'axiom'),
					"override" => "category,page",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => __('Show post info', 'axiom'),
					"desc" => __('Show area with post info on single pages', 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => __('Show text before "Read more" tag', 'axiom'),
					"desc" => __('Show text before "Read more" tag on single pages', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => __('Show post author details',  'axiom'),
					"desc" => __("Show post author information block on single post page", 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => __('Show post tags',  'axiom'),
					"desc" => __("Show tags block on single post page", 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_counters" => array(
					"title" => __('Show post counters',  'axiom'),
					"desc" => __("Show counters block on single post page", 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => __('Show related posts',  'axiom'),
					"desc" => __("Show related posts block on single post page", 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"post_related_count" => array(
					"title" => __('Related posts number',  'axiom'),
					"desc" => __("How many related posts showed on single post page", 'axiom'),
					"override" => "category,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => __('Related posts columns',  'axiom'),
					"desc" => __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'axiom'),
					"override" => "category,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => __('Related posts sorted by', 'axiom'),
					"desc" => __('Select the desired sorting method for related posts', 'axiom'),
		//			"override" => "category,page",
					"std" => "date",
					"options" => $AXIOM_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => __('Related posts order', 'axiom'),
					"desc" => __('Select the desired ordering method for related posts', 'axiom'),
		//			"override" => "category,page",
					"std" => "desc",
					"options" => $AXIOM_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => __('Show comments',  'axiom'),
					"desc" => __("Show comments block on single post page", 'axiom'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_general' => array(
					"title" => __('Other parameters', 'axiom'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,page",
					"type" => "tab"),
		
		"info_blog_3" => array(
					"title" => __('Other Blog parameters', 'axiom'),
					"desc" => __('Select excluded categories, substitute parameters, etc.', 'axiom'),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => __('Exclude categories', 'axiom'),
					"desc" => __('Select categories, which posts are exclude from blog page', 'axiom'),
					"divider" => false,
					"std" => "",
					"options" => $AXIOM_GLOBALS['options_params']['list_categories'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => __('Blog pagination', 'axiom'),
					"desc" => __('Select type of the pagination on blog streampages', 'axiom'),
					"std" => "pages",
					"override" => "category,page",
					"options" => array(
						'pages'    => __('Standard page numbers', 'axiom'),
						'viewmore' => __('"View more" button', 'axiom'),
						'infinite' => __('Infinite scroll', 'axiom')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_pagination_style" => array(
					"title" => __('Blog pagination style', 'axiom'),
					"desc" => __('Select pagination style for standard page numbers', 'axiom'),
					"std" => "pages",
					"override" => "category,page",
					"options" => array(
						'pages'  => __('Page numbers list', 'axiom'),
						'slider' => __('Slider with page numbers', 'axiom')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => __('Blog counters', 'axiom'),
					"desc" => __('Select counters, displayed near the post title', 'axiom'),
					"std" => "views",
					"override" => "category,page",
					"options" => array(
						'views' => __('Views', 'axiom'),
						'likes' => __('Likes', 'axiom'),
						'rating' => __('Rating', 'axiom'),
						'comments' => __('Comments', 'axiom')
					),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => __("Post's category announce", 'axiom'),
					"desc" => __('What category display in announce block (over posts thumb) - original or nearest parental', 'axiom'),
					"std" => "parental",
					"override" => "category,page",
					"options" => array(
						'parental' => __('Nearest parental category', 'axiom'),
						'original' => __("Original post's category", 'axiom')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => __('Show post date after', 'axiom'),
					"desc" => __('Show post date after N days (before - show post age)', 'axiom'),
					"override" => "category,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		

		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => __('Reviews', 'axiom'),
					"icon" => "iconadmin-newspaper",
					"override" => "category",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => __('Reviews criterias', 'axiom'),
					"desc" => __('Set up list of reviews criterias. You can override it in any category.', 'axiom'),
					"override" => "category",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => __('Show reviews block',  'axiom'),
					"desc" => __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'axiom'),
					"divider" => false,
					"override" => "category",
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => __('Max reviews level',  'axiom'),
					"desc" => __("Maximum level for reviews marks", 'axiom'),
					"std" => "5",
					"options" => array(
						'5'=>__('5 stars', 'axiom'),
						'10'=>__('10 stars', 'axiom'),
						'100'=>__('100%', 'axiom')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => __('Show rating as',  'axiom'),
					"desc" => __("Show rating marks as text or as stars/progress bars.", 'axiom'),
					"std" => "stars",
					"options" => array(
						'text' => __('As text (for example: 7.5 / 10)', 'axiom'),
						'stars' => __('As stars or bars', 'axiom')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => __('Reviews Criterias Levels', 'axiom'),
					"desc" => __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'axiom'),
					"std" => __("bad,poor,normal,good,great", 'axiom'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => __('Show first reviews',  'axiom'),
					"desc" => __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'axiom'),
					"std" => "author",
					"options" => array(
						'author' => __('By author', 'axiom'),
						'users' => __('By visitors', 'axiom')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => __('Hide second reviews',  'axiom'),
					"desc" => __("Do you want hide second reviews tab in widgets and single posts?", 'axiom'),
					"std" => "show",
					"options" => $AXIOM_GLOBALS['options_params']['list_show_hide'],
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => __('What visitors can vote',  'axiom'),
					"desc" => __("What visitors can vote: all or only registered", 'axiom'),
					"std" => "all",
					"options" => array(
						'all'=>__('All visitors', 'axiom'),
						'registered'=>__('Only registered', 'axiom')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => __('Reviews criterias',  'axiom'),
					"desc" => __('Add default reviews criterias.',  'axiom'),
					"override" => "category",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => __('Contact info', 'axiom'),
					"icon" => "iconadmin-mail-1",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => __('Contact information', 'axiom'),
					"desc" => __('Company address, phones and e-mail', 'axiom'),
					"type" => "info"),
		
		"contact_email" => array(
					"title" => __('Contact form email', 'axiom'),
					"desc" => __('E-mail for send contact form and user registration data', 'axiom'),
					"divider" => false,
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail-1'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => __('Company address (part 1)', 'axiom'),
					"desc" => __('Company country, post code and city', 'axiom'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => __('Company address (part 2)', 'axiom'),
					"desc" => __('Street and house number', 'axiom'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => __('Phone', 'axiom'),
					"desc" => __('Phone number', 'axiom'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),

		"show_emergency_phone" => array(
					"title" => __('Show emergency phone', 'axiom'),
					"desc" => __('Show emergency phone number', 'axiom'),
					"override" => "category,post,page",
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"), //switch

		"emergency_phone" => array(
					"title" => __('Emergency phone', 'axiom'),
					"desc" => __('Emergency phone number', 'axiom'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "hidden"), //text
		
		"contact_fax" => array(
					"title" => __('Fax', 'axiom'),
					"desc" => __('Fax number', 'axiom'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"work_hours" => array(
					"title" => __('Custom info', 'axiom'),
					"desc" => __('Custom information you need to be added', 'axiom'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-cog'),
					"type" => "text"),

		"contact_info" => array(
					"title" => __('Contacts in header', 'axiom'),
					"desc" => __('String with contact info in the site header', 'axiom'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => __('Contact and Comments form', 'axiom'),
					"desc" => __('Maximum length of the messages in the contact form shortcode and in the comments form', 'axiom'),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => __('Contact form message', 'axiom'),
					"desc" => __("Message's maxlength in the contact form shortcode", 'axiom'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => __('Comments form message', 'axiom'),
					"desc" => __("Message's maxlength in the comments form", 'axiom'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => __('Default mail function', 'axiom'),
					"desc" => __('What function you want to use for sending mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'axiom'),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => __("Mail function", 'axiom'),
					"desc" => __("What function you want to use for sending mail?", 'axiom'),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => __('WP mail', 'axiom'),
						'mail' => __('PHP mail', 'axiom')
					),
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => __('Socials', 'axiom'),
					"icon" => "iconadmin-users-1",
					"override" => "category,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => __('Social networks', 'axiom'),
					"desc" => __("Social networks list for site footer and Social widget", 'axiom'),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => __('Social networks',  'axiom'),
					"desc" => __('Select icon and write URL to your profile in desired social networks.',  'axiom'),
					"divider" => false,
					"std" => array(array('url'=>'', 'icon'=>'')),
					"options" => $AXIOM_GLOBALS['options_params']['list_icons'],
					"cloneable" => true,
					"size" => "small",
					"style" => 'icons',
					"type" => "socials"),

		"info_socials_2" => array(
					"title" => __('Share buttons', 'axiom'),
					"override" => "category,page",
					"desc" => __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'axiom'),
					"type" => "info"),
		
		"show_share" => array(
					"title" => __('Show social share buttons',  'axiom'),
					"override" => "category,page",
					"desc" => __("Show social share buttons block", 'axiom'),
					"std" => "horizontal",
					"options" => array(
						'hide'		=> __('Hide', 'axiom'),
						'vertical'	=> __('Vertical', 'axiom'),
						'horizontal'=> __('Horizontal', 'axiom')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => __('Show share counters',  'axiom'),
					"override" => "category,page",
					"desc" => __("Show share counters after social buttons", 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"share_caption" => array(
					"title" => __('Share block caption',  'axiom'),
					"override" => "category,page",
					"desc" => __('Caption for the block with social share buttons',  'axiom'),
					"std" => __('Share:', 'axiom'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => __('Share buttons',  'axiom'),
					"desc" => __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'axiom'),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"options" => $AXIOM_GLOBALS['options_params']['list_icons'],
					"cloneable" => true,
					"size" => "small",
					"style" => 'icons',
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => __('Twitter API keys', 'axiom'),
					"desc" => __("Put to this section Twitter API 1.1 keys.<br>
					You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'axiom'),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => __('Twitter username',  'axiom'),
					"desc" => __('Your login (username) in Twitter',  'axiom'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => __('Consumer Key',  'axiom'),
					"desc" => __('Twitter API Consumer key',  'axiom'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => __('Consumer Secret',  'axiom'),
					"desc" => __('Twitter API Consumer secret',  'axiom'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => __('Token Key',  'axiom'),
					"desc" => __('Twitter API Token key',  'axiom'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => __('Token Secret',  'axiom'),
					"desc" => __('Twitter API Token secret',  'axiom'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => __('Search', 'axiom'),
					"icon" => "iconadmin-search-1",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => __('Search parameters', 'axiom'),
					"desc" => __('Enable/disable AJAX search and output settings for it', 'axiom'),
					"type" => "info"),
		
		"show_search" => array(
					"title" => __('Show search field', 'axiom'),
					"desc" => __('Show search field in the top area and side menus', 'axiom'),
					"divider" => false,
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => __('Enable AJAX search', 'axiom'),
					"desc" => __('Use incremental AJAX search for the search field in top of page', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => __('Min search string length',  'axiom'),
					"desc" => __('The minimum length of the search string',  'axiom'),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => __('Delay before search (in ms)',  'axiom'),
					"desc" => __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'axiom'),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => __('Search area', 'axiom'),
					"desc" => __('Select post types, what will be include in search results. If not selected - use all types.', 'axiom'),
					"std" => "",
					"options" => $AXIOM_GLOBALS['options_params']['list_posts_types'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => __('Posts number in output',  'axiom'),
					"desc" => __('Number of the posts to show in search results',  'axiom'),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => __("Show post's image", 'axiom'),
					"desc" => __("Show post's thumbnail in the search results", 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => __("Show post's date", 'axiom'),
					"desc" => __("Show post's publish date in the search results", 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => __("Show post's author", 'axiom'),
					"desc" => __("Show post's author in the search results", 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => __("Show post's counters", 'axiom'),
					"desc" => __("Show post's counters (views, comments, likes) in the search results", 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => __('Service', 'axiom'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => __('Theme functionality', 'axiom'),
					"desc" => __('Basic theme functionality settings', 'axiom'),
					"type" => "info"),
		
		"notify_about_new_registration" => array(
					"title" => __('Notify about new registration', 'axiom'),
					"desc" => __('Send E-mail with new registration data to the contact email or to site admin e-mail (if contact email is empty)', 'axiom'),
					"divider" => false,
					"std" => "no",
					"options" => array(
						'no'    => __('No', 'axiom'),
						'both'  => __('Both', 'axiom'),
						'admin' => __('Admin', 'axiom'),
						'user'  => __('User', 'axiom')
					),
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"use_ajax_views_counter" => array(
					"title" => __('Use AJAX post views counter', 'axiom'),
					"desc" => __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => __('Additional filters in the admin panel', 'axiom'),
					"desc" => __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => __('Show overriden options for taxonomies', 'axiom'),
					"desc" => __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => __('Show overriden options for posts and pages', 'axiom'),
					"desc" => __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => __('Enable Dummy Data Installer', 'axiom'),
					"desc" => __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => __('Dummy Data Installer Timeout',  'axiom'),
					"desc" => __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'axiom'),
					"std" => 1200,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_update_notifier" => array(
					"title" => __('Enable Update Notifier', 'axiom'),
					"desc" => __('Show update notifier in admin panel. <b>Attention!</b> When this option is enabled, the theme periodically (every few hours) will communicate with our server, to check the current version. When the connection is slow, it may slow down Dashboard.', 'axiom'),
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_emailer" => array(
					"title" => __('Enable Emailer in the admin panel', 'axiom'),
					"desc" => __('Allow to use AxiomThemes Emailer for mass-volume e-mail distribution and management of mailing lists in "Tools - Emailer"', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => __('Enable PO Composer in the admin panel', 'axiom'),
					"desc" => __('Allow to use "PO Composer" for edit language files in this theme (in the "Tools - PO Composer")', 'axiom'),
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"clear_shortcodes" => array(
					"title" => __('Remove line breaks around shortcodes', 'axiom'),
					"desc" => __('Do you want remove spaces and line breaks around shortcodes? <b>Be attentive!</b> This option thoroughly tested on our theme, but may affect third party plugins.', 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => __('Debug mode', 'axiom'),
					"desc" => __('Debug mode', 'axiom'),
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"packed_scripts" => array(
					"title" => __('Use packed css and js files', 'axiom'),
					"desc" => __('Do you want to use one packed css and one js file with most theme scripts and styles instead many separate files (for speed up page loading). This reduces the number of HTTP requests when loading pages.', 'axiom'),
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"info_service_3" => array(
					"title" => esc_html__('API Keys', 'axiom'),
					"desc" => wp_kses_data( __('API Keys for some Web services', 'axiom') ),
					"type" => "info"),
		'api_google' => array(
					"title" => esc_html__('Google API Key', 'axiom'),
					"desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'axiom') ),
					"std" => "",
					"type" => "text"),

		"info_service_2" => array(
					"title" => __('Clear Wordpress cache', 'axiom'),
					"desc" => __('For example, it recommended after activating the WPML plugin - in the cache are incorrect data about the structure of categories and your site may display "white screen". After clearing the cache usually the performance of the site is restored.', 'axiom'),
					"type" => "info"),
		
		"clear_cache" => array(
					"title" => __('Clear cache', 'axiom'),
					"desc" => __('Clear Wordpress cache data', 'axiom'),
					"divider" => false,
					"icon" => "iconadmin-trash-1",
					"action" => "clear_cache",
					"type" => "button")
		);


		// Woocommerce
		if (axiom_exists_woocommerce()) {
			$AXIOM_GLOBALS['options']["partition_woocommerce"] = array(
					"title" => __('WooCommerce', 'axiom'),
					"icon" => "iconadmin-basket-1",
					"type" => "partition");
		
			$AXIOM_GLOBALS['options']["info_wooc_1"] = array(
					"title" => __('WooCommerce products list parameters', 'axiom'),
					"desc" => __("Select WooCommerce products list's style and crop parameters", 'axiom'),
					"type" => "info");
		
			$AXIOM_GLOBALS['options']["shop_mode"] = array(
					"title" => __('Shop list style',  'axiom'),
					"desc" => __("WooCommerce products list's style: thumbs or list with description", 'axiom'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => __('Thumbs', 'axiom'),
						'list' => __('List', 'axiom')
					),
					"type" => "checklist");
		
			$AXIOM_GLOBALS['options']["show_mode_buttons"] = array(
					"title" => __('Show style buttons',  'axiom'),
					"desc" => __("Show buttons to allow visitors change list style", 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch");

			$AXIOM_GLOBALS['options']["crop_product_thumb"] = array(
					"title" => __('Crop product thumbnail',  'axiom'),
					"desc" => __("Crop product's thumbnails on search results page", 'axiom'),
					"std" => "no",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch");
		
			$AXIOM_GLOBALS['options']["show_category_bg"] = array(
					"title" => __('Show category background',  'axiom'),
					"desc" => __("Show background under thumbnails for the product's categories", 'axiom'),
					"std" => "yes",
					"options" => $AXIOM_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch");
		}		

	}
}
?>