<?php
/**
 * Default skin file for theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('axiom_skin_theme_setup_education')) {
	add_action( 'axiom_action_init_theme', 'axiom_skin_theme_setup_education', 1 );
	function axiom_skin_theme_setup_education() {

		// Add skin fonts in the used fonts list
		add_filter('axiom_filter_used_fonts',			'axiom_filter_used_fonts_education');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('axiom_filter_list_fonts',			'axiom_filter_list_fonts_education');

		// Add skin stylesheets
		add_action('axiom_action_add_styles',			'axiom_action_add_styles_education');
		// Add skin inline styles
		add_filter('axiom_filter_add_styles_inline',		'axiom_filter_add_styles_inline_education');
		// Add skin responsive styles
		add_action('axiom_action_add_responsive',		'axiom_action_add_responsive_education');
		// Add skin responsive inline styles
		add_filter('axiom_filter_add_responsive_inline',	'axiom_filter_add_responsive_inline_education');

		// Add skin scripts
		add_action('axiom_action_add_scripts',			'axiom_action_add_scripts_education');
		// Add skin scripts inline
		add_action('axiom_action_add_scripts_inline',	'axiom_action_add_scripts_inline_education');

		// Return links color (if not set in the theme options)
		add_filter('axiom_filter_get_link_color',		'axiom_filter_get_link_color_education', 10, 1);
		// Return links dark color
		add_filter('axiom_filter_get_link_dark',			'axiom_filter_get_link_dark_education',  10, 1);
		// Return links light color
		add_filter('axiom_filter_get_link_light',		'axiom_filter_get_link_light_education', 10, 1);

		// Return main menu items color (if not set in the theme options)
		add_filter('axiom_filter_get_menu_color',		'axiom_filter_get_menu_color_education', 10, 1);
		// Return main menu items dark color
		add_filter('axiom_filter_get_menu_dark',			'axiom_filter_get_menu_dark_education',  10, 1);
		// Return main menu light color
		add_filter('axiom_filter_get_menu_light',		'axiom_filter_get_menu_light_education', 10, 1);

		// Return user menu items color (if not set in the theme options)
		add_filter('axiom_filter_get_user_color',		'axiom_filter_get_user_color_education', 10, 1);
		// Return user menu items dark color
		add_filter('axiom_filter_get_user_dark',			'axiom_filter_get_user_dark_education',  10, 1);
		// Return user menu light color
		add_filter('axiom_filter_get_user_light',		'axiom_filter_get_user_light_education', 10, 1);

		// Add color schemes
		axiom_add_color_scheme('original', array(
			'title'		 =>	__('Original', 'axiom'),
			'link_color' => '#28c3d4',
			'link_dark'  => '#5d7602',
			'link_light' => '#ffffff',
			'menu_color' => '#ff7b53',
			'menu_dark'  => '#A65227',
			'menu_light' => '#ffffff',
			'user_color' => '#a48576',
			'user_dark'  => '#9b8c85',
			'user_light' => '#ffffff'
			)
		);
		axiom_add_color_scheme('contrast', array(
			'title'		 =>	__('Contrast', 'axiom'),
			'menu_color' => '#26c3d6',		// rgb(38,195,214)
			'menu_dark'  => '#24b6c8',		// rgb(36,182,200)
			'menu_light' => '#ffffff',
			'link_color' => '#f55c6d',		// rgb(245,92,109)
			'link_dark'  => '#e24c5d',		// rgb(226,76,93)
			'link_light' => '#ffffff',
			'user_color' => '#2d3e50',		// rgb(45,62,80)
			'user_dark'  => '#233140',		// rgb(35,49,64)
			'user_light' => '#ffffff'
			)
		);
		axiom_add_color_scheme('modern', array(
			'title'		 =>	__('Modern', 'axiom'),
			'menu_color' => '#f9c82d',		// rgb(249,200,45)
			'menu_dark'  => '#e6ba29',		// rgb(230,186,41)
			'menu_light' => '#ffffff',
			'link_color' => '#a7d163',		// rgb(167,209,99)
			'link_dark'  => '#98bf5a',		// rgb(152,191,90)
			'link_light' => '#ffffff',
			'user_color' => '#fe7d60',		// rgb(254,125,96)
			'user_dark'  => '#eb7459',		// rgb(235,116,89)
			'user_light' => '#ffffff'
			)
		);
		axiom_add_color_scheme('pastel', array(
			'title'		 =>	__('Pastel', 'axiom'),
			'menu_color' => '#0dcdc0',		// rgb(13,205,192)
			'menu_dark'  => '#0bbaae',		// rgb(11,186,174)
			'menu_light' => '#ffffff',
			'link_color' => '#a7d163',		// rgb(167,209,99)
			'link_dark'  => '#98bf5a',		// rgb(152,191,90)
			'link_light' => '#ffffff',
			'user_color' => '#0ead99',		// rgb(14,173,153)
			'user_dark'  => '#0c9c88',		// rgb(12,156,136)
			'user_light' => '#ffffff'
			)
		);
	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('axiom_filter_used_fonts_education')) {
	//add_filter('axiom_filter_used_fonts', 'axiom_filter_used_fonts_education');
	function axiom_filter_used_fonts_education($theme_fonts) {
		$theme_fonts['Lora'] = 1;
		$theme_fonts['Montserrat'] = 1;
		$theme_fonts['Merriweather'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('axiom_filter_list_fonts_education')) {
	//add_filter('axiom_filter_list_fonts', 'axiom_filter_list_fonts_education');
	function axiom_filter_list_fonts_education($list) {
		// Example:
		// if (!isset($list['Advent Pro'])) {
		//		$list['Advent Pro'] = array(
		//			'family' => 'sans-serif',																						// (required) font family
		//			'link'   => 'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
		//			'css'    => axiom_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
		//			);
		// }
		/*if (!isset($list['Roboto']))    $list['Roboto'] = array('family'=>'sans-serif');
		if (!isset($list['Oswald']))	$list['Oswald'] = array('family'=>'sans-serif', 'link'=>'Oswald:400,700,300&subset=latin');
		if (!isset($list['Vollkorn']))	$list['Vollkorn'] = array('family'=>'serif', 'link'=>'Vollkorn:400,400italic,700,700italic&subset=latin');*/
		return $list;
	}
}

//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('axiom_action_add_styles_education')) {
	//add_action('axiom_action_add_styles', 'axiom_action_add_styles_education');
	function axiom_action_add_styles_education() {
		// Add stylesheet files
		axiom_enqueue_style( 'axiom-skin-style', axiom_get_file_url('skins/default/skin.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('axiom_filter_add_styles_inline_education')) {
	//add_filter('axiom_filter_add_styles_inline', 'axiom_filter_add_styles_inline_education');
	function axiom_filter_add_styles_inline_education($custom_style) {
	
		// Color scheme
		$scheme = axiom_get_custom_option('color_scheme');
		if (empty($scheme)) $scheme = 'original';

		global $AXIOM_GLOBALS;

		// Links color
		$clr = axiom_get_custom_option('link_color');
		if (empty($clr) && $scheme!= 'original')	$clr = apply_filters('axiom_filter_get_link_color', '');
		if (!empty($clr)) {
			$AXIOM_GLOBALS['color_schemes'][$scheme]['link_color'] = $clr;
			$rgb = axiom_hex2rgb($clr);
			$custom_style .= '
.booking_back_today a:hover,
.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title.ui-state-active,
.sc_testimonial_author a,
.sc_testimonial_author,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a,
.sc_list_style_iconed li:before,
.sc_list_style_iconed .sc_list_icon,
.sc_icon_shape_round.sc_icon_bg_link:hover,
.sc_icon_shape_square.sc_icon_bg_link:hover,
a:hover .sc_icon_shape_round.sc_icon_bg_link,
a:hover .sc_icon_shape_square.sc_icon_bg_link,
.sc_icon_bg_link,
.sc_dropcaps.sc_dropcaps_style_1 .sc_dropcaps_item,
.sc_countdown.sc_countdown_style_2 .sc_countdown_label,
.sc_button.sc_button_style_border,
.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title.ui-state-active,
.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.woocommerce ul.products li.product .star-rating:before, .woocommerce ul.products li.product .star-rating span,
.woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-message:before,
.woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price,.woocommerce ul.products li.product .price,.woocommerce-page ul.products li.product .price,
.woocommerce a.button.alt:hover,  .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover,  .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover,
  .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover,   .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover,
.woocommerce .quantity input[type="button"]:hover, .woocommerce #content input[type="button"]:hover, .woocommerce-page .quantity input[type="button"]:hover, .woocommerce-page #content .quantity input[type="button"]:hover,
.woocommerce ul.cart_list li > .amount, .woocommerce ul.product_list_widget li > .amount, .woocommerce-page ul.cart_list li > .amount, .woocommerce-page ul.product_list_widget li > .amount,
.woocommerce ul.cart_list li span .amount, .woocommerce ul.product_list_widget li span .amount, .woocommerce-page ul.cart_list li span .amount, .woocommerce-page ul.product_list_widget li span .amount,
.woocommerce ul.cart_list li ins .amount, .woocommerce ul.product_list_widget li ins .amount, .woocommerce-page ul.cart_list li ins .amount, .woocommerce-page ul.product_list_widget li ins .amount,
.woocommerce.widget_shopping_cart .total .amount, .woocommerce .widget_shopping_cart .total .amount, .woocommerce-page.widget_shopping_cart .total .amount, .woocommerce-page .widget_shopping_cart .total .amount,
.woocommerce a:hover h3, .woocommerce-page a:hover h3,
.woocommerce .cart-collaterals .order-total strong, .woocommerce-page .cart-collaterals .order-total strong,
.woocommerce .checkout #order_review .order-total .amount, .woocommerce-page .checkout #order_review .order-total .amount,
.woocommerce .star-rating, .woocommerce-page .star-rating, .woocommerce .star-rating:before, .woocommerce-page .star-rating:before,
.widget_area .widgetWrap ul > li .star-rating span, .woocommerce #review_form #respond .stars a, .woocommerce-page #review_form #respond .stars a,
.sc_team .sc_socials a.icons span[class^="icon-"],
.widget_area .widget_text a,
.widget_area .post_info a,
.post_info .post_info_item a,
.widget_area a,
.widget_area button:before,
.comments_list_wrap .comment_info > span.comment_author,
.comments_list_wrap .comment_info > .comment_date > .comment_date_value,
.post_author .post_author_title a,
.post_item:nth-child(3n+1) .post_rating .reviews_stars_bg,
.post_item:nth-child(3n+1) .post_rating .reviews_stars_hover,
.post_item:nth-child(3n+1) .post_rating .reviews_value,
.isotope_filters a,
.post_title .post_icon,
.page_top_wrap .breadcrumbs a.breadcrumbs_item,
.search_results .post_more,
.search_results .search_results_close,
.top_panel_style_light .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
.post_item_404 .page_subtitle,
.top_panel_style_light .content .search_wrap.search_style_regular .search_icon,
.search_wrap.search_style_regular .search_form_wrap .search_submit:before,
.sidemenu_wrap .sidemenu_area ul li ul li ul li a:hover,
.sidemenu_wrap .sidemenu_close:hover,
.menu_user_wrap .sidemenu_button i:hover,
.custom_options .menu_user_nav > li > ul a:hover,
.menu_user_wrap .menu_user_nav > li ul li.current-menu-item > a,
.menu_user_wrap .menu_user_nav > li ul li.current-menu-ancestor > a,
.top_panel_wrap .emergency_phone,
.menu_main_wrap .menu_main_nav > li:hover > a,
.menu_main_wrap .menu_main_nav > li.sfHover > a,
.menu_main_wrap .menu_main_nav > li#blob > a,
.menu_main_wrap .menu_main_nav > li.current-menu-ancestor > a,
.menu_main_wrap .menu_main_nav > li.current-menu-item > a,
.menu_main_wrap .menu_main_nav > li.current-menu-parent > a
.menu_main_wrap .menu_main_nav > li ul li a:hover,
.menu_main_wrap .menu_main_nav > li ul li.current-menu-item > a,
.menu_main_wrap .menu_main_nav > li ul li.current-menu-ancestor > a,
.menu_main_wrap .menu_main_nav li > a:hover,
.custom_options a:hover,
.custom_options .search_wrap .search_form_wrap .search_submit:hover,
.search_wrap .search_icon:hover:before,
.copyright_wrap a,
h4.post_title a:hover,
#sidemenu_button:hover,
.rev_slider_wrapper .search_wrap .search_icon:hover:before,
.tribe-events-list-separator-month span,
.sc_column_item:hover .sc_title .sc_title_icon_top:before,
.bg_tint_dark a:hover,
.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title.ui-state-default .sc_accordion_icon,
.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title.ui-state-active .sc_accordion_icon,
.widget_area.bg_tint_dark .post_title a:hover,
.widget_area .widget_contacts .sc_icon:before,
.page_top_wrap .breadcrumbs .breadcrumbs_item,
.widget_area .post_title a:hover,
.widget_area.bg_tint_dark .widget_calendar .weekday,
.widget_area .widget_twitter ul li:before,
.sc_team_item .sc_team_item_position,
.pagination_wrap .pager_next,
.pagination_wrap .pager_prev,
.pagination_wrap .pager_last,
.pagination_wrap .pager_first,
.pagination_pages > .active,
.pagination_pages > a:hover,
.sc_events .isotope_item_classic .startDate,
.sc_blogger.layout_list_1 .post_info span > span,
.sc_blogger.layout_list_2 .post_info span > span,
.sc_blogger.layout_list_3 .post_info span > span,
.sc_blogger.layout_list_1 li:after,
.sc_blogger.layout_list_2 li:after,
.sc_blogger.layout_list_3 li:after,
.swiper-wrapper .post_info .post_info_posted,
.swiper-wrapper .post_info .post_counters_number,
.sc_countdown.sc_countdown_style_1 .sc_countdown_digits,
.hover_icon:before,
.sc_dropcaps.sc_dropcaps_style_4 .sc_dropcaps_item,
.home1-sl3-date,
a
				{
					color:'.esc_attr($clr).';
				}

.menu_main_wrap .menu_main_nav > li ul li a:hover,
.sc_highlight_style_3,
.sc_events .isotope_item_classic2 h5 a,
#booking_slot_form > div > a:hover,
.rev_slider_wrapper .search_wrap .search_form_wrap .search_submit:hover:before,
.rev_slider_wrapper .search_wrap .search_icon:hover:before,
.widget_area .widget_product_search .search_button:hover:before,
.widget_area .widget_search .search_button:hover:before,
.search_wrap .search_form_wrap .search_submit:hover:before,
.search_wrap.search_style_regular .search_icon:hover,
.menu_left_nav li a:hover, .menu_left_nav li a.active,
.menu_left_nav li.current-menu-item a,
.custom_options .menu_user_nav > li > a:hover,
.custom_options .menu_user_nav > li > a:hover:after,
.booking_day_container:hover .booking_day_slots
				{
					color:'.esc_attr($clr).' !important;
				}

input[type="submit"]:hover,
.sc_price_block.sc_price_block_style_1,
.sc_skills_bar .sc_skills_item .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_3 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_info,
.sc_infobox.sc_infobox_style_regular,
.sc_icon_shape_round.sc_icon_bg_link,
.sc_icon_shape_square.sc_icon_bg_link,
.tribe-events-button, #tribe-events .tribe-events-button,
#tribe-bar-form .tribe-bar-submit input[type=submit],
a.tribe-events-read-more,
.tribe-events-button,
.tribe-events-nav-previous a,
.tribe-events-nav-next a,
.tribe-events-widget-link a,
.tribe-events-viewmore a,
.sc_dropcaps.sc_dropcaps_style_2 .sc_dropcaps_item,
.sc_highlight_style_1,
.tribe-events-calendar thead th,
.woocommerce table.cart thead th, .woocommerce #content table.cart thead th, .woocommerce-page table.cart thead th, .woocommerce-page #content table.cart thead th,
.woocommerce ul.products li.product .add_to_cart_button, .woocommerce-page ul.products li.product .add_to_cart_button,
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,
.reviews_block .reviews_max_level_100:nth-child(3n+2) .reviews_stars_hover,
.reviews_block .reviews_item:nth-child(3n+2) .reviews_slider,
.scroll_to_top:hover,
.woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt,
.woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover,  .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover,  .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover,  .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover,
.woocommerce span.new, .woocommerce-page span.new,
.woocommerce span.onsale, .woocommerce-page span.onsale,
.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title.ui-state-active .sc_accordion_icon_opened,
.sc_contact_form_button button,
.sc_button.sc_button_style_filled.sc_button_bg_user,
.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date,
.sc_scroll_bar .swiper-scrollbar-drag:before,
.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title.ui-state-active .sc_toggles_icon_opened,
.sc_tooltip_parent .sc_tooltip,
.sc_tooltip_parent .sc_tooltip:before,
.custom_options .emergency_phone,
input[type="submit"], input[type="button"], button, .sc_button,
.emergency_call_wrap,
.sc_slider_controls_wrap a:hover,
.sc_scroll_controls_wrap a:hover,
.menu_main_wrap .menu_main_nav_area .menu_main_responsive a:hover,
#booking_slot_form .booking_float_right .close_booking:hover,
.sc_button.sc_button_style_filled.sc_button_bg_link:hover,
.top_stripe.top_stripe_bg1,
.menu_user_wrap .menu_user_nav li:last-child > a,
.menu_user_wrap .menu_user_nav li > a:hover,
.pagination_pages > a,
.pagination_pages > span,
h1.styling1:before,
.sc_events .isotope_item_list .startDate,
.sc_slider_swiper .sc_slider_pagination_wrap .swiper-active-switch,
.sc_slider_swiper .sc_slider_pagination_wrap span:hover,
.ancora-paypal-donations button,
.sc_button.sc_button_style_filled:hover {
	background-color: '.esc_attr($clr).';
}

.woocommerce .price_slider_amount .button,
.sc_button.sc_button_style_border.sc_button_bg_link:hover,
.vc_btn.vc_btn_sky,
a.vc_btn.vc_btn_sky,
button.vc_btn.vc_btn_sky,
.sc_countdown.sc_countdown_style_2 .sc_countdown_digits span,
.rev_slider_wrapper .sc_countdown.sc_countdown_style_1 .sc_countdown_digits,
.sc_button.sc_emailer_button:hover  {
	background-color: '.esc_attr($clr).' !important;
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {
	background: '.esc_attr($clr).';
}

.sc_section.top_stripe.top_stripe_bg2:hover,
.sc_section.top_stripe.top_stripe_bg2:hover .sc_icon_shape_round.sc_icon_bg_menu,
form .booking_clear_custom:hover,
.booking_month_container_all .booking_month_nav_container .booking_mont_nav_button_container a,
.booking_ok_button,
#booking_submit_button,
.booking_month_container_all .booking_month_nav_container .booking_mont_nav_button_container a:hover,
.booking_day_container.booking_day_black a,
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, .mejs-controls .mejs-time-rail .mejs-time-current { background: '.esc_attr($clr).' !important; }

.sc_video_frame.sc_video_play_button:hover:after,
.sc_audio.sc_audio_image:after {
	background:  rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].', 0.7);
}

figure figcaption,
.sc_image figcaption,
.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect_dir.colored .info,
.post_content.ih-item.square.effect_shift.colored .info  {
	background:  rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].', 0.6);
}

 .eg-tavern-skin-container {
	background-color:  rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].', 0.8) !important;
}

.sc_blogger.layout_list_1 li:after,
.sc_blogger.layout_list_2 li:after,
.sc_blogger.layout_list_3 li:after,
.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title .sc_accordion_icon,
.sc_icon_shape_round.sc_icon_bg_link,
.sc_icon_shape_square.sc_icon_bg_link,
.top_panel_style_light .content .search_wrap.search_style_regular.search_opened,
pre.code,
.post_format_aside.post_item_single .post_content p,
.post_format_aside .post_descr,
.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title.ui-state-active,
.sc_button.sc_button_style_border,
.pagination_pages > a,
.pagination_pages > span,
.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date,
.sc_skills_bar .sc_skills_item .sc_skills_count,
.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title.ui-state-active {
	border-color: '.esc_attr($clr).';
}

.sc_button.sc_button_style_border.sc_button_bg_link:hover,
.sc_section.top_stripe.top_stripe_bg2:hover .sc_icon_shape_round.sc_icon_bg_menu {
	border-color: '.esc_attr($clr).' !important;
}

.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li a:hover,
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a,
.woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message,
.woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active,
.woocommerce a.button:active, .woocommerce button.button:active, .woocommerce input.button:active, .woocommerce #respond input#submit:active, .woocommerce #content input.button:active, .woocommerce-page a.button:active, .woocommerce-page button.button:active, .woocommerce-page input.button:active, .woocommerce-page #respond input#submit:active, .woocommerce-page #content input.button:active {
	border-top-color: '.esc_attr($clr).';
}
.widget_area .widget_calendar .today .day_wrap,
.page_top_wrap .breadcrumbs a.breadcrumbs_item:hover,
.comments_list_wrap > ul {
	border-bottom-color: '.esc_attr($clr).';
}


.post_content.ih-item.circle.effect17.colored:hover .img:before {
	box-shadow: inset 0 0 0 110px rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);
}

			';
		}
		// Links dark color
		$clr_dark = axiom_get_custom_option('link_dark');
		if (empty($clr_dark) && $scheme!= 'original')	$clr_dark = apply_filters('axiom_filter_get_link_dark', '');
		if (!empty($clr) || !empty($clr_dark)) {
			if (empty($clr_dark)) {
				$hsb = axiom_hex2hsb($clr);
				$hsb['s'] = min(100, $hsb['s'] + 15);
				$hsb['b'] = max(0, $hsb['b'] - 20);
				$clr = axiom_hsb2hex($hsb);
			} else
				$clr = $clr_dark;
			$AXIOM_GLOBALS['color_schemes'][$scheme]['link_dark'] = $clr;
			//$rgb = axiom_hex2rgb($clr);
			$custom_style .= '
			';
		}
		// Links light color
		$clr = axiom_get_custom_option('link_light');
		if (empty($clr) && $scheme!= 'original')	$clr = apply_filters('axiom_filter_get_link_light', '');
		if (!empty($clr)) {
			$AXIOM_GLOBALS['color_schemes'][$scheme]['link_light'] = $clr;
			//$rgb = axiom_hex2rgb($clr);
			$custom_style .= '
			';
		}


		// Menu color
		$clr = axiom_get_custom_option('menu_color');
		if (empty($clr) && $scheme!= 'original')	$clr = apply_filters('axiom_filter_get_menu_color', '');
		if (!empty($clr)) {
			$AXIOM_GLOBALS['color_schemes'][$scheme]['menu_color'] = $clr;
			$rgb = axiom_hex2rgb($clr);
			$custom_style .= '
.booking_back_today a,
.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title.ui-state-active .sc_toggles_icon,
.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title.ui-state-active,
.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title:hover,
.sc_testimonial_author a:hover,
.sc_testimonials .sc_slider_controls_wrap a:hover,
.sc_icon_shape_round.sc_icon_bg_menu:hover,
.sc_icon_shape_square.sc_icon_bg_menu:hover,
a:hover .sc_icon_shape_round.sc_icon_bg_menu,
a:hover .sc_icon_shape_square.sc_icon_bg_menu,
.sc_icon_bg_menu,
.sc_icon.sc_icon_bg_link:hover,
a:hover .sc_icon.sc_icon_bg_link,
.sc_countdown.sc_countdown_style_1 .sc_countdown_separator,
.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title:hover,
.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current,
.sc_team.sc_team_style_2 .sc_socials a.icons span[class^="icon-"]:hover,
.widget_area a:hover,
.widget_area ul li a:hover,
.widget_area button:hover:before,
.widget_area .widget_text a:hover,
.widget_area .post_info a:hover,
.bg_tint_light a:hover,
.bg_tint_light .menu_main_responsive_button,
.search_results .post_more:hover,
.search_results .search_results_close:hover,
.pagination_single > .pager_numbers,
.pagination_single a:hover,
.pagination_slider .pager_cur:hover,
.pagination_slider .pager_cur:focus,
.reviews_block .reviews_item:nth-child(3n+2) .reviews_stars_hover,
.post_item:nth-child(3n+2) .post_rating .reviews_stars_bg,
.post_item:nth-child(3n+2) .post_rating .reviews_stars_hover,
.post_item:nth-child(3n+2) .post_rating .reviews_value,
.post_author .post_author_title a:hover,
.widget_area .widget_product_tag_cloud a:hover,
.widget_area .widget_tag_cloud a:hover,
.footer_wrap .sc_icon,
.footer_wrap .dotted_bg .sc_icon,
.search_wrap.search_style_regular .search_form_wrap .search_submit:hover:before,
.menu_user_wrap .sidemenu_button i,
.menu_main_wrap .menu_main_nav > li.booking > a,
.copyright_wrap a:hover,
.post_info .post_info_item a:hover,
.bg_tint_light h1 a, .bg_tint_light h2 a, .bg_tint_light h3 a, .bg_tint_light h4 a, .bg_tint_light h5 a, .bg_tint_light h6 a,
.sc_title_icon,
.sc_hexagon_container .sc_hexagon_text,
#toc.under_slider .toc_description,
#toc.under_slider a,
.woocommerce ul.products li.product h3 a:hover, .woocommerce-page ul.products li.product h3 a:hover,
#sidemenu_button.under_slider,
.sc_button.sc_button_style_border.sc_button_bg_menu,
.menu_copy_nav > li > a:hover,
.widget_area .wp-calendar .month_prev a:hover,
.widget_area .wp-calendar .month_next a:hover,
.sc_team_item .sc_team_item_title a:hover,
.pagination_wrap .pager_next:hover,
.pagination_wrap .pager_prev:hover,
.pagination_wrap .pager_last:hover,
.pagination_wrap .pager_first:hover,
.reviews_block .reviews_item:nth-child(3n+1) .reviews_stars_hover,
.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title,
.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title.ui-state-active,
.sc_blogger.layout_list_1 li a:hover,
.sc_blogger.layout_list_2 li a:hover,
.sc_blogger.layout_list_3 li a:hover,
a:hover,
.vc_proparty_featured_projects_tmpl h4 a:hover {
	color: '.esc_attr($clr).';
}

.rev_slider_wrapper .tp-caption a:not(.sc_button):hover,
.sc_events .isotope_item_classic2 h5 a:hover,
.flat-light .esg-navigationbutton:hover,
.flat-light .esg-navigationbutton.selected,
.custom_options .menu_user_nav > li > a,
.custom_options .menu_user_nav > li > a:after,
.days_container_all .booking_day_slots,
.top_panel_style_light .content .search_wrap.search_style_regular .search_form_wrap .search_submit:hover {color:'.esc_attr($clr).' !important;}

.menu_main_wrap .menu_main_nav_area .menu_main_responsive,
.top_panel_style_dark.article_style_boxed .page_top_wrap .breadcrumbs a.breadcrumbs_item:hover,
.pagination_single > .pager_numbers,
.pagination_single a,
.pagination_slider .pager_cur,
.pagination_viewmore > a,
.sc_button.sc_button_style_filled.sc_button_bg_menu,
.viewmore_loader,
.mfp-preloader span,
.sc_video_frame.sc_video_active:before,
.post_featured .post_nav_item:before,
.post_featured .post_nav_item .post_nav_info,
.reviews_block .reviews_summary .reviews_item,
.sc_dropcaps.sc_dropcaps_style_3 .sc_dropcaps_item,
.sc_highlight_style_2,
.reviews_block .reviews_max_level_100:nth-child(3n+1) .reviews_stars_hover,
.reviews_block .reviews_item:nth-child(3n+1) .reviews_slider,
.widget_area .widget_calendar td a:hover,
.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title .sc_toggles_icon,
.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title:hover .sc_toggles_icon_opened,
.tribe-events-button, #tribe-events .tribe-events-button:hover,
.sc_icon_shape_round.sc_icon_bg_menu,
.sc_icon_shape_square.sc_icon_bg_menu,
.tribe-events-button, #tribe-events .tribe-events-button,
.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title:hover .sc_accordion_icon_opened,
.scroll_to_top,
.sc_infobox.sc_infobox_style_success,
.sc_popup:before,
.sc_price_block.sc_price_block_style_2,
.sc_scroll_controls_wrap a,
.sc_slider_controls_wrap a,
.custom_options #co_toggle,
.woocommerce nav.woocommerce-pagination ul li a,
.reviews_block .reviews_summary .reviews_stars,
.woocommerce ul.products li.product .add_to_cart_button:hover, .woocommerce-page ul.products li.product .add_to_cart_button:hover,
.sc_button.sc_button_style_filled:hover,
.sc_contact_form_button button:hover,
.woocommerce a.button:hover,
.woocommerce-page button.button.alt:hover,
.woocommerce button.button.alt:hover,
.woocommerce-page button.button:hover,
.woocommerce-page a.button:hover,
.woocommerce button.button:hover,
.woocommerce input.button:hover,
.woocommerce-page input.button:hover,
.woocommerce-page a.button.alt:hover,
.woocommerce input.button.alt:hover,
.woocommerce-page input.button.alt:hover,
.top_stripe.top_stripe_bg2,
.menu_user_wrap .menu_user_nav li > a:hover,
.menu_user_wrap .menu_user_nav li:last-child > a:hover,
.sc_events .isotope_item_classic .sc_button:hover,
.woocommerce nav.woocommerce-pagination ul li span.current {
	background-color: '.esc_attr($clr).';
}

.sc_button.sc_emailer_button,
.booking_ok_button:hover,
#booking_submit_button:hover,
.esg-pagination-button,
.esg-sortbutton-order,
.custom_options.co_light #co_toggle {
	background-color: '.esc_attr($clr).' !important;
}

.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect3.colored .info,
.post_content.ih-item.circle.effect4.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect6.colored .info,
.post_content.ih-item.circle.effect7.colored .info,
.post_content.ih-item.circle.effect8.colored .info,
.post_content.ih-item.circle.effect9.colored .info,
.post_content.ih-item.circle.effect10.colored .info,
.post_content.ih-item.circle.effect11.colored .info,
.post_content.ih-item.circle.effect12.colored .info,
.post_content.ih-item.circle.effect13.colored .info,
.post_content.ih-item.circle.effect14.colored .info,
.post_content.ih-item.circle.effect15.colored .info,
.post_content.ih-item.circle.effect16.colored .info,
.post_content.ih-item.circle.effect18.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect1.colored .info,
.post_content.ih-item.square.effect2.colored .info,
.post_content.ih-item.square.effect3.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect5.colored .info,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect8.colored .info,
.post_content.ih-item.square.effect9.colored .info .info-back,
.post_content.ih-item.square.effect10.colored .info,
.post_content.ih-item.square.effect11.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect14.colored .info,
.post_content.ih-item.square.effect15.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect_book.colored .info {
	background: '.esc_attr($clr).';
}

.esg-pagination-button, .esg-sortbutton-order,
.booking_day_container.booking_day_white:hover a {
	background: '.esc_attr($clr).' !important;
}

.sc_chat_inner,
.pagination > a,
.pagination_single > .pager_numbers,
.pagination_single a,
.pagination_slider .pager_cur,
.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title:hover,
.woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span.current,
.sc_button.sc_button_style_border.sc_button_bg_menu,
.sc_icon_shape_round.sc_icon_bg_menu,
.sc_icon_shape_square.sc_icon_bg_menu,
.sc_testimonials .sc_slider_controls_wrap a:hover,
.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title:hover,
.bg_tint_dark .sc_testimonials .sc_slider_controls_wrap a:hover,
#toc .toc_item.current,
#toc .toc_item:hover,
.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title .sc_toggles_icon {
	border-color: '.esc_attr($clr).';
}
.esg-pagination-button, .esg-sortbutton-order
{
	border-color: '.esc_attr($clr).' !important;
}

.post_content.ih-item.circle.effect1 .spinner,
.sc_chat:after,
.comments_list_wrap .comment-respond {
	border-bottom-color: '.esc_attr($clr).';
}

.sc_chat:after {
	border-left-color: '.esc_attr($clr).';
}

.post_content.ih-item.circle.effect1 .spinner {
	border-right-color: '.esc_attr($clr).';
}


.comments_list_wrap ul.children,
.comments_list_wrap ul > li + li {
	border-top-color: '.esc_attr($clr).';
}

.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a:hover,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a {
	border-bottom-color: rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].', 0.75);
}


			';
		}
		
		// Menu dark color
		$clr_dark = axiom_get_custom_option('menu_dark');
		if (empty($clr_dark) && $scheme!= 'original')	$clr_dark = apply_filters('axiom_filter_get_menu_dark', '');
		if (!empty($clr) || !empty($clr_dark)) {
			if (empty($clr_dark)) {
				$hsb = axiom_hex2hsb($clr);
				$hsb['s'] = min(100, $hsb['s'] + 15);
				$hsb['b'] = max(0, $hsb['b'] - 20);
				$clr = axiom_hsb2hex($hsb);
			} else
				$clr = $clr_dark;
			$AXIOM_GLOBALS['color_schemes'][$scheme]['menu_dark'] = $clr;
			//$rgb = axiom_hex2rgb($clr);
			$custom_style .= '
			';
		}
		// Menu light color
		$clr = axiom_get_custom_option('menu_light');
		if (empty($clr) && $scheme!= 'original')	$clr = apply_filters('axiom_filter_get_menu_light', '');
		if (!empty($clr)) {
			$AXIOM_GLOBALS['color_schemes'][$scheme]['menu_light'] = $clr;
			//$rgb = axiom_hex2rgb($clr);
			$custom_style .= '
			';
		}

		// User color
		$clr = axiom_get_custom_option('user_color');
		if (empty($clr) && $scheme!= 'original')	$clr = apply_filters('axiom_filter_get_user_color', '');
		if (!empty($clr)) {
			$AXIOM_GLOBALS['color_schemes'][$scheme]['user_color'] = $clr;
			$rgb = axiom_hex2rgb($clr);
			$custom_style .= '
			';
		}
		// User dark color
		$clr_dark = axiom_get_custom_option('user_dark');
		if (empty($clr_dark) && $scheme!= 'original')	$clr_dark = apply_filters('axiom_filter_get_user_dark', '');
		if (!empty($clr) || !empty($clr_dark)) {
			if (empty($clr_dark)) {
				$hsb = axiom_hex2hsb($clr);
				$hsb['s'] = min(100, $hsb['s'] + 15);
				$hsb['b'] = max(0, $hsb['b'] - 20);
				$clr = axiom_hsb2hex($hsb);
			} else
				$clr = $clr_dark;
			$AXIOM_GLOBALS['color_schemes'][$scheme]['user_dark'] = $clr;
			//$rgb = axiom_hex2rgb($clr);
			$custom_style .= '
			';
		}
		// User light color
		$clr = axiom_get_custom_option('user_light');
		if (empty($clr) && $scheme!= 'original')	$clr = apply_filters('axiom_filter_get_user_light', '');
		if (!empty($clr)) {
			$AXIOM_GLOBALS['color_schemes'][$scheme]['user_light'] = $clr;
			//$rgb = axiom_hex2rgb($clr);
			$custom_style .= '
			';
		}

		return $custom_style;
	}
}

// Add skin responsive styles
if (!function_exists('axiom_action_add_responsive_education')) {
	//add_action('axiom_action_add_responsive', 'axiom_action_add_responsive_education');
	function axiom_action_add_responsive_education() {
		if (file_exists(axiom_get_file_dir('skins/default/skin-responsive.css')))
			axiom_enqueue_style( 'theme-skin-responsive-style', axiom_get_file_url('skins/default/skin-responsive.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('axiom_filter_add_responsive_inline_education')) {
	//add_filter('axiom_filter_add_responsive_inline', 'axiom_filter_add_responsive_inline_education');
	function axiom_filter_add_responsive_inline_education($custom_style) {
		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('axiom_action_add_scripts_education')) {
	//add_action('axiom_action_add_scripts', 'axiom_action_add_scripts_education');
	function axiom_action_add_scripts_education() {
		if (file_exists(axiom_get_file_dir('skins/default/skin.js')))
			axiom_enqueue_script( 'theme-skin-script', axiom_get_file_url('skins/default/skin.js'), array(), null );
		if (axiom_get_theme_option('show_theme_customizer') == 'yes' && file_exists(axiom_get_file_dir('skins/default/skin.customizer.js')))
			axiom_enqueue_script( 'theme-skin-customizer-script', axiom_get_file_url('skins/default/skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('axiom_action_add_scripts_inline_education')) {
	//add_action('axiom_action_add_scripts_inline', 'axiom_action_add_scripts_inline_education');
	function axiom_action_add_scripts_inline_education() {
		echo '<script type="text/javascript">'
			. 'jQuery(document).ready(function() {'
			. "if (AXIOM_GLOBALS['theme_font']=='') AXIOM_GLOBALS['theme_font'] = 'Roboto';"
			. "AXIOM_GLOBALS['link_color'] = '" . apply_filters('axiom_filter_get_link_color', axiom_get_custom_option('link_color')) . "';"
			. "AXIOM_GLOBALS['menu_color'] = '" . apply_filters('axiom_filter_get_menu_color', axiom_get_custom_option('menu_color')) . "';"
			. "AXIOM_GLOBALS['user_color'] = '" . apply_filters('axiom_filter_get_user_color', axiom_get_custom_option('user_color')) . "';"
			. "});"
			. "</script>";
	}
}


//------------------------------------------------------------------------------
// Get skin's colors
//------------------------------------------------------------------------------


// Return main theme bg color
if (!function_exists('axiom_filter_get_theme_bgcolor_education')) {
	//add_filter('axiom_filter_get_theme_bgcolor', 'axiom_filter_get_theme_bgcolor_education', 10, 1);
	function axiom_filter_get_theme_bgcolor_education($clr) {
		return '#ffffff';
	}
}



// Return link color (if not set in the theme options)
if (!function_exists('axiom_filter_get_link_color_education')) {
	//add_filter('axiom_filter_get_link_color', 'axiom_filter_get_link_color_education', 10, 1);
	function axiom_filter_get_link_color_education($clr) {
		return empty($clr) ? axiom_get_scheme_color('link_color') : $clr;
	}
}

// Return links dark color (if not set in the theme options)
if (!function_exists('axiom_filter_get_link_dark_education')) {
	//add_filter('axiom_filter_get_link_dark', 'axiom_filter_get_link_dark_education', 10, 1);
	function axiom_filter_get_link_dark_education($clr) {
		return empty($clr) ? axiom_get_scheme_color('link_dark') : $clr;
	}
}

// Return links light color (if not set in the theme options)
if (!function_exists('axiom_filter_get_link_light_education')) {
	//add_filter('axiom_filter_get_link_light', 'axiom_filter_get_link_light_education', 10, 1);
	function axiom_filter_get_link_light_education($clr) {
		return empty($clr) ? axiom_get_scheme_color('link_light') : $clr;
	}
}



// Return main menu color (if not set in the theme options)
if (!function_exists('axiom_filter_get_menu_color_education')) {
	//add_filter('axiom_filter_get_menu_color', 'axiom_filter_get_menu_color_education', 10, 1);
	function axiom_filter_get_menu_color_education($clr) {
		return empty($clr) ? axiom_get_scheme_color('menu_color') : $clr;
	}
}

// Return main menu dark color (if not set in the theme options)
if (!function_exists('axiom_filter_get_menu_dark_education')) {
	//add_filter('axiom_filter_get_menu_dark', 'axiom_filter_get_menu_dark_education', 10, 1);
	function axiom_filter_get_menu_dark_education($clr) {
		return empty($clr) ? axiom_get_scheme_color('menu_dark') : $clr;
	}
}

// Return main menu light color (if not set in the theme options)
if (!function_exists('axiom_filter_get_menu_light_education')) {
	//add_filter('axiom_filter_get_menu_light', 'axiom_filter_get_menu_light_education', 10, 1);
	function axiom_filter_get_menu_light_education($clr) {
		return empty($clr) ? axiom_get_scheme_color('menu_light') : $clr;
	}
}



// Return user menu color (if not set in the theme options)
if (!function_exists('axiom_filter_get_user_color_education')) {
	//add_filter('axiom_filter_get_user_color', 'axiom_filter_get_user_color_education', 10, 1);
	function axiom_filter_get_user_color_education($clr) {
		return empty($clr) ? axiom_get_scheme_color('user_color') : $clr;
	}
}

// Return user menu dark color (if not set in the theme options)
if (!function_exists('axiom_filter_get_user_dark_education')) {
	//add_filter('axiom_filter_get_user_dark', 'axiom_filter_get_user_dark_education', 10, 1);
	function axiom_filter_get_user_dark_education($clr) {
		return empty($clr) ? axiom_get_scheme_color('user_dark') : $clr;
	}
}

// Return user menu light color (if not set in the theme options)
if (!function_exists('axiom_filter_get_user_light_education')) {
	//add_filter('axiom_filter_get_user_light', 'axiom_filter_get_user_light_education', 10, 1);
	function axiom_filter_get_user_light_education($clr) {
		return empty($clr) ? axiom_get_scheme_color('user_light') : $clr;
	}
}
?>