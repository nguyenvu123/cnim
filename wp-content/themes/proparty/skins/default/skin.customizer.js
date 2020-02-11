// Interactive change skin custom styles
function axiom_skin_customizer(option, val) {

	var custom_style = '';

	if (option == 'bg_color') {

		jQuery("#custom_options .co_switch_box a[data-value='boxed']").trigger('click');
		jQuery('#custom_options #co_bg_pattern_list .co_pattern_wrapper, #custom_options #co_bg_images_list .co_image_wrapper').removeClass('active');
		jQuery('body').removeClass('bg_pattern_1 bg_pattern_2 bg_pattern_3 bg_pattern_4 bg_pattern_5 bg_image_1 bg_image_2 bg_image_3').css('backgroundColor', clr);

	} else if (option == 'bg_pattern') {

		jQuery('body')
			.removeClass('bg_pattern_1 bg_pattern_2 bg_pattern_3 bg_pattern_4 bg_pattern_5 bg_image_1 bg_image_2 bg_image_3')
			.css('backgroundColor', 'transparent')
			.addClass('bg_pattern_' + val);

	} else if (option == 'bg_image') {

		jQuery('body')
			.removeClass('bg_pattern_1 bg_pattern_2 bg_pattern_3 bg_pattern_4 bg_pattern_5 bg_image_1 bg_image_2 bg_image_3')
			.css('backgroundColor', 'transparent')
			.addClass('bg_image_' + val);

	} else if (option == 'link_color') {

		var clr = val;
		var rgb = axiom_hex2rgb(clr);
		// Link color styles: color:'+clr+', rgba('+rgb.r+','+rgb.g+','+rgb.b+', 0.8)
		custom_style += '\
.booking_back_today a:hover,\
			.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title.ui-state-active,\
	.sc_testimonial_author a,\
	.sc_testimonial_author,\
	.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a,\
	.sc_list_style_iconed li:before,\
	.sc_list_style_iconed .sc_list_icon,\
	.sc_icon_shape_round.sc_icon_bg_link:hover,\
	.sc_icon_shape_square.sc_icon_bg_link:hover,\
			a:hover .sc_icon_shape_round.sc_icon_bg_link,\
			a:hover .sc_icon_shape_square.sc_icon_bg_link,\
	.sc_icon_bg_link,\
	.sc_dropcaps.sc_dropcaps_style_1 .sc_dropcaps_item,\
	.sc_countdown.sc_countdown_style_2 .sc_countdown_label,\
	.sc_button.sc_button_style_border,\
	.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title.ui-state-active,\
	.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,\
	.woocommerce ul.products li.product .star-rating:before, .woocommerce ul.products li.product .star-rating span,\
	.woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-message:before,\
	.woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price,.woocommerce ul.products li.product .price,.woocommerce-page ul.products li.product .price,\
	.woocommerce a.button.alt:hover,  .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover,  .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover,\
	.woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover,   .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover,\
	.woocommerce .quantity input[type="button"]:hover, .woocommerce #content input[type="button"]:hover, .woocommerce-page .quantity input[type="button"]:hover, .woocommerce-page #content .quantity input[type="button"]:hover,\
	.woocommerce ul.cart_list li > .amount, .woocommerce ul.product_list_widget li > .amount, .woocommerce-page ul.cart_list li > .amount, .woocommerce-page ul.product_list_widget li > .amount,\
	.woocommerce ul.cart_list li span .amount, .woocommerce ul.product_list_widget li span .amount, .woocommerce-page ul.cart_list li span .amount, .woocommerce-page ul.product_list_widget li span .amount,\
	.woocommerce ul.cart_list li ins .amount, .woocommerce ul.product_list_widget li ins .amount, .woocommerce-page ul.cart_list li ins .amount, .woocommerce-page ul.product_list_widget li ins .amount,\
	.woocommerce.widget_shopping_cart .total .amount, .woocommerce .widget_shopping_cart .total .amount, .woocommerce-page.widget_shopping_cart .total .amount, .woocommerce-page .widget_shopping_cart .total .amount,\
	.woocommerce a:hover h3, .woocommerce-page a:hover h3,\
	.woocommerce .cart-collaterals .order-total strong, .woocommerce-page .cart-collaterals .order-total strong,\
	.woocommerce .checkout #order_review .order-total .amount, .woocommerce-page .checkout #order_review .order-total .amount,\
	.woocommerce .star-rating, .woocommerce-page .star-rating, .woocommerce .star-rating:before, .woocommerce-page .star-rating:before,\
	.widget_area .widgetWrap ul > li .star-rating span, .woocommerce #review_form #respond .stars a, .woocommerce-page #review_form #respond .stars a,\
	.sc_team .sc_socials a.icons span[class^="icon-"],\
	.widget_area .widget_text a,\
	.widget_area .post_info a,\
	.post_info .post_info_item a,\
	.widget_area a,\
	.widget_area button:before,\
	.comments_list_wrap .comment_info > span.comment_author,\
	.comments_list_wrap .comment_info > .comment_date > .comment_date_value,\
	.post_author .post_author_title a,\
	.post_item:nth-child(3n+1) .post_rating .reviews_stars_bg,\
	.post_item:nth-child(3n+1) .post_rating .reviews_stars_hover,\
	.post_item:nth-child(3n+1) .post_rating .reviews_value,\
	.isotope_filters a,\
	.post_title .post_icon,\
	.page_top_wrap .breadcrumbs a.breadcrumbs_item,\
	.search_results .post_more,\
	.search_results .search_results_close,\
	.top_panel_style_light .content .search_wrap.search_style_regular .search_form_wrap .search_submit,\
	.post_item_404 .page_subtitle,\
	.top_panel_style_light .content .search_wrap.search_style_regular .search_icon,\
	.search_wrap.search_style_regular .search_form_wrap .search_submit:before,\
	.sidemenu_wrap .sidemenu_area ul li ul li ul li a:hover,\
	.sidemenu_wrap .sidemenu_close:hover,\
	.menu_user_wrap .sidemenu_button i:hover,\
	.custom_options .menu_user_nav > li > ul a:hover,\
	.menu_user_wrap .menu_user_nav > li ul li.current-menu-item > a,\
	.menu_user_wrap .menu_user_nav > li ul li.current-menu-ancestor > a,\
	.top_panel_wrap .emergency_phone,\
	.menu_main_wrap .menu_main_nav > li:hover > a,\
	.menu_main_wrap .menu_main_nav > li.sfHover > a,\
	.menu_main_wrap .menu_main_nav > li#blob > a,\
	.menu_main_wrap .menu_main_nav > li.current-menu-ancestor > a,\
	.menu_main_wrap .menu_main_nav > li.current-menu-item > a,\
	.menu_main_wrap .menu_main_nav > li.current-menu-parent > a,\
			.menu_main_wrap .menu_main_nav > li ul li a:hover,\
	.menu_main_wrap .menu_main_nav > li ul li.current-menu-item > a,\
	.menu_main_wrap .menu_main_nav > li ul li.current-menu-ancestor > a,\
	.menu_main_wrap .menu_main_nav li > a:hover,\
	.custom_options a:hover,\
	.custom_options .search_wrap .search_form_wrap .search_submit:hover,\
	.search_wrap .search_icon:hover:before,\
	.copyright_wrap a,\
			h4.post_title a:hover,\
#sidemenu_button:hover,\
	.rev_slider_wrapper .search_wrap .search_icon:hover:before,\
	.tribe-events-list-separator-month span,\
	.sc_column_item:hover .sc_title .sc_title_icon_top:before,\
	.bg_tint_dark a:hover,\
	.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title.ui-state-default .sc_accordion_icon,\
	.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title.ui-state-active .sc_accordion_icon,\
	.widget_area.bg_tint_dark .post_title a:hover,\
	.widget_area .widget_contacts .sc_icon:before,\
	.page_top_wrap .breadcrumbs .breadcrumbs_item,\
	.widget_area .post_title a:hover,\
	.widget_area.bg_tint_dark .widget_calendar .weekday,\
	.widget_area .widget_twitter ul li:before,\
	.sc_team_item .sc_team_item_position,\
	.pagination_wrap .pager_next,\
	.pagination_wrap .pager_prev,\
	.pagination_wrap .pager_last,\
	.pagination_wrap .pager_first,\
	.pagination_pages > .active,\
	.pagination_pages > a:hover,\
	.sc_events .isotope_item_classic .startDate,\
	.sc_blogger.layout_list_1 .post_info span > span,\
	.sc_blogger.layout_list_2 .post_info span > span,\
	.sc_blogger.layout_list_3 .post_info span > span,\
	.sc_blogger.layout_list_1 li:after,\
	.sc_blogger.layout_list_2 li:after,\
	.sc_blogger.layout_list_3 li:after,\
	.swiper-wrapper .post_info .post_info_posted,\
	.swiper-wrapper .post_info .post_counters_number,\
	.sc_countdown.sc_countdown_style_1 .sc_countdown_digits,\
	.hover_icon:before,\
	.sc_dropcaps.sc_dropcaps_style_4 .sc_dropcaps_item,\
	.home1-sl3-date,\
			a\
		{\
			color:'+clr+';\
		}\
		.menu_main_wrap .menu_main_nav > li ul li a:hover,\
	.sc_highlight_style_3,\
	.sc_events .isotope_item_classic2 h5 a,\
#booking_slot_form > div > a:hover,\
	.rev_slider_wrapper .search_wrap .search_form_wrap .search_submit:hover:before,\
	.rev_slider_wrapper .search_wrap .search_icon:hover:before,\
	.widget_area .widget_product_search .search_button:hover:before,\
	.widget_area .widget_search .search_button:hover:before,\
	.search_wrap .search_form_wrap .search_submit:hover:before,\
	.search_wrap.search_style_regular .search_icon:hover,\
	.menu_left_nav li a:hover, .menu_left_nav li a.active,\
	.menu_left_nav li.current-menu-item a,\
	.custom_options .menu_user_nav > li > a:hover,\
	.custom_options .menu_user_nav > li > a:hover:after,\
	.booking_day_container:hover .booking_day_slots\
		{\
			color:'+clr+' !important;\
		}\
		input[type="submit"]:hover,\
	.sc_price_block.sc_price_block_style_1,\
	.sc_skills_bar .sc_skills_item .sc_skills_count,\
	.sc_skills_counter .sc_skills_item.sc_skills_style_3 .sc_skills_count,\
	.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_count,\
	.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_info,\
	.sc_infobox.sc_infobox_style_regular,\
	.sc_icon_shape_round.sc_icon_bg_link,\
	.sc_icon_shape_square.sc_icon_bg_link,\
	.tribe-events-button, #tribe-events .tribe-events-button,\
#tribe-bar-form .tribe-bar-submit input[type=submit],\
		a.tribe-events-read-more,\
	.tribe-events-button,\
	.tribe-events-nav-previous a,\
	.tribe-events-nav-next a,\
	.tribe-events-widget-link a,\
	.tribe-events-viewmore a,\
	.sc_dropcaps.sc_dropcaps_style_2 .sc_dropcaps_item,\
	.sc_highlight_style_1,\
	.tribe-events-calendar thead th,\
	.woocommerce table.cart thead th, .woocommerce #content table.cart thead th, .woocommerce-page table.cart thead th, .woocommerce-page #content table.cart thead th,\
	.woocommerce ul.products li.product .add_to_cart_button, .woocommerce-page ul.products li.product .add_to_cart_button,\
	.woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,\
	.reviews_block .reviews_max_level_100:nth-child(3n+2) .reviews_stars_hover,\
	.reviews_block .reviews_item:nth-child(3n+2) .reviews_slider,\
	.scroll_to_top:hover,\
	.woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt,\
	.woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover,  .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover,  .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover,  .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover,\
	.woocommerce span.new, .woocommerce-page span.new,\
	.woocommerce span.onsale, .woocommerce-page span.onsale,\
	.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title.ui-state-active .sc_accordion_icon_opened,\
	.sc_contact_form_button button,\
	.sc_button.sc_button_style_filled.sc_button_bg_user,\
	.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date,\
	.sc_scroll_bar .swiper-scrollbar-drag:before,\
	.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title.ui-state-active .sc_toggles_icon_opened,\
	.sc_tooltip_parent .sc_tooltip,\
	.sc_tooltip_parent .sc_tooltip:before,\
	.custom_options .emergency_phone,\
			input[type="submit"], input[type="button"], button, .sc_button,\
	.emergency_call_wrap,\
	.sc_slider_controls_wrap a:hover,\
	.sc_scroll_controls_wrap a:hover,\
	.menu_main_wrap .menu_main_nav_area .menu_main_responsive a:hover,\
#booking_slot_form .booking_float_right .close_booking:hover,\
	.sc_button.sc_button_style_filled.sc_button_bg_link:hover,\
	.top_stripe.top_stripe_bg1,\
	.menu_user_wrap .menu_user_nav li:last-child > a,\
	.menu_user_wrap .menu_user_nav li > a:hover,\
	.pagination_pages > a,\
	.pagination_pages > span,\
			h1.styling1:before,\
	.sc_events .isotope_item_list .startDate,\
	.sc_slider_swiper .sc_slider_pagination_wrap .swiper-active-switch,\
		.sc_slider_swiper .sc_slider_pagination_wrap span:hover,\
		.ancora-paypal-donations button,\
		.sc_button.sc_button_style_filled:hover {\
			background-color: '+clr+';\
		}\
		.woocommerce .price_slider_amount .button,\
		.sc_button.sc_button_style_border.sc_button_bg_link:hover,\
		.vc_btn.vc_btn_sky,\
				a.vc_btn.vc_btn_sky,\
				button.vc_btn.vc_btn_sky,\
		.sc_countdown.sc_countdown_style_2 .sc_countdown_digits span,\
		.rev_slider_wrapper .sc_countdown.sc_countdown_style_1 .sc_countdown_digits,\
		.sc_button.sc_emailer_button:hover  {\
			background-color: '+clr+' !important;\
		}\
		.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {\
			background: '+clr+';\
		}\
		.sc_section.top_stripe.top_stripe_bg2:hover,\
		.sc_section.top_stripe.top_stripe_bg2:hover .sc_icon_shape_round.sc_icon_bg_menu,\
				form .booking_clear_custom:hover,\
		.booking_month_container_all .booking_month_nav_container .booking_mont_nav_button_container a,\
		.booking_ok_button,\
#booking_submit_button,\
		.booking_month_container_all .booking_month_nav_container .booking_mont_nav_button_container a:hover,\
		.booking_day_container.booking_day_black a,\
		.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, \
.mejs-controls .mejs-time-rail .mejs-time-current {	background: '+clr+' !important; }\
		.sc_video_frame.sc_video_play_button:hover:after,\
		.sc_audio.sc_audio_image:after {\
			background:  rgba(rgba('+rgb.r+','+rgb.g+','+rgb.b+', 0.7);\
		}\
			figure figcaption,\
		.sc_image figcaption,\
		.post_content.ih-item.circle.effect1.colored .info,\
		.post_content.ih-item.circle.effect2.colored .info,\
		.post_content.ih-item.circle.effect5.colored .info .info-back,\
		.post_content.ih-item.circle.effect19.colored .info,\
		.post_content.ih-item.square.effect4.colored .mask1,\
		.post_content.ih-item.square.effect4.colored .mask2,\
		.post_content.ih-item.square.effect6.colored .info,\
		.post_content.ih-item.square.effect7.colored .info,\
		.post_content.ih-item.square.effect12.colored .info,\
		.post_content.ih-item.square.effect13.colored .info,\
		.post_content.ih-item.square.effect_dir.colored .info,\
		.post_content.ih-item.square.effect_shift.colored .info  {\
			background:  rgba(rgba('+rgb.r+','+rgb.g+','+rgb.b+', 0.6);\
		}\
		.eg-tavern-skin-container {\
			background-color:  rgba(rgba('+rgb.r+','+rgb.g+','+rgb.b+', 0.8) !important;\
		}\
		.sc_blogger.layout_list_1 li:after,\
		.sc_blogger.layout_list_2 li:after,\
		.sc_blogger.layout_list_3 li:after,\
		.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title .sc_accordion_icon,\
		.sc_icon_shape_round.sc_icon_bg_link,\
		.sc_icon_shape_square.sc_icon_bg_link,\
		.top_panel_style_light .content .search_wrap.search_style_regular.search_opened,\
				pre.code,\
		.post_format_aside.post_item_single .post_content p,\
		.post_format_aside .post_descr,\
		.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title.ui-state-active,\
		.sc_button.sc_button_style_border,\
		.pagination_pages > a,\
		.pagination_pages > span,\
		.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date,\
		.sc_skills_bar .sc_skills_item .sc_skills_count,\
		.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title.ui-state-active {\
			border-color: '+clr+';\
		}\
		.sc_button.sc_button_style_border.sc_button_bg_link:hover,\
		.sc_section.top_stripe.top_stripe_bg2:hover .sc_icon_shape_round.sc_icon_bg_menu {\
			border-color: '+clr+' !important;\
		}\
		.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li a:hover,\
		.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a,\
		.woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message,\
		.woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active,\
		.woocommerce a.button:active, .woocommerce button.button:active, .woocommerce input.button:active, .woocommerce #respond input#submit:active, .woocommerce #content input.button:active, .woocommerce-page a.button:active, .woocommerce-page button.button:active, .woocommerce-page input.button:active, .woocommerce-page #respond input#submit:active, .woocommerce-page #content input.button:active {\
			border-top-color: '+clr+';\
		}\
		.widget_area .widget_calendar .today .day_wrap,\
		.page_top_wrap .breadcrumbs a.breadcrumbs_item:hover,\
		.comments_list_wrap > ul {\
			border-bottom-color: '+clr+';\
		}\
		.post_content.ih-item.circle.effect17.colored:hover .img:before {\
			box-shadow: inset 0 0 0 110px rgba(rgba('+rgb.r+','+rgb.g+','+rgb.b+', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);\
		}\
		';
		// Link dark color
		hsb = axiom_hex2hsb(clr);
		hsb.s = Math.min(100, hsb.s + 15);
		hsb.b = Math.max(0, hsb.b - 20);
		clr = axiom_hsb2hex(hsb);
		custom_style += '\
		';

	} else if (option == 'menu_color') {

		var clr = val;
		var rgb = axiom_hex2rgb(clr);
		// Menu color styles
		custom_style += '\
		';
		// Menu dark color
		hsb = axiom_hex2hsb(clr);
		hsb.s = Math.min(100, hsb.s + 15);
		hsb.b = Math.max(0, hsb.b - 20);
		clr = axiom_hsb2hex(hsb);
		custom_style += '\
.booking_back_today a,\
			.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title.ui-state-active .sc_toggles_icon,\
	.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title.ui-state-active,\
	.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title:hover,\
	.sc_testimonial_author a:hover,\
	.sc_testimonials .sc_slider_controls_wrap a:hover,\
	.sc_icon_shape_round.sc_icon_bg_menu:hover,\
	.sc_icon_shape_square.sc_icon_bg_menu:hover,\
			a:hover .sc_icon_shape_round.sc_icon_bg_menu,\
			a:hover .sc_icon_shape_square.sc_icon_bg_menu,\
	.sc_icon_bg_menu,\
	.sc_icon.sc_icon_bg_link:hover,\
			a:hover .sc_icon.sc_icon_bg_link,\
	.sc_countdown.sc_countdown_style_1 .sc_countdown_separator,\
	.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title:hover,\
	.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current,\
	.sc_team.sc_team_style_2 .sc_socials a.icons span[class^="icon-"]:hover,\
	.widget_area a:hover,\
	.widget_area ul li a:hover,\
	.widget_area button:hover:before,\
	.widget_area .widget_text a:hover,\
	.widget_area .post_info a:hover,\
	.bg_tint_light a:hover,\
	.bg_tint_light .menu_main_responsive_button,\
	.search_results .post_more:hover,\
	.search_results .search_results_close:hover,\
	.pagination_single > .pager_numbers,\
	.pagination_single a:hover,\
	.pagination_slider .pager_cur:hover,\
	.pagination_slider .pager_cur:focus,\
	.reviews_block .reviews_item:nth-child(3n+2) .reviews_stars_hover,\
	.post_item:nth-child(3n+2) .post_rating .reviews_stars_bg,\
	.post_item:nth-child(3n+2) .post_rating .reviews_stars_hover,\
	.post_item:nth-child(3n+2) .post_rating .reviews_value,\
	.post_author .post_author_title a:hover,\
	.widget_area .widget_product_tag_cloud a:hover,\
	.widget_area .widget_tag_cloud a:hover,\
	.footer_wrap .sc_icon,\
	.footer_wrap .dotted_bg .sc_icon,\
	.search_wrap.search_style_regular .search_form_wrap .search_submit:hover:before,\
	.menu_user_wrap .sidemenu_button i,\
	.menu_main_wrap .menu_main_nav > li.booking > a,\
	.copyright_wrap a:hover,\
	.post_info .post_info_item a:hover,\
	.bg_tint_light h1 a, .bg_tint_light h2 a, .bg_tint_light h3 a, .bg_tint_light h4 a,\ .bg_tint_light h5 a, .bg_tint_light h6 a,\
	.sc_title_icon,\
	.sc_hexagon_container .sc_hexagon_text,\
#toc.under_slider .toc_description,\
#toc.under_slider a,\
	.woocommerce ul.products li.product h3 a:hover, .woocommerce-page ul.products li.product h3 a:hover,\
#sidemenu_button.under_slider,\
	.sc_button.sc_button_style_border.sc_button_bg_menu,\
	.menu_copy_nav > li > a:hover,\
	.widget_area .wp-calendar .month_prev a:hover,\
	.widget_area .wp-calendar .month_next a:hover,\
	.sc_team_item .sc_team_item_title a:hover,\
	.pagination_wrap .pager_next:hover,\
	.pagination_wrap .pager_prev:hover,\
	.pagination_wrap .pager_last:hover,\
	.pagination_wrap .pager_first:hover,\
	.reviews_block .reviews_item:nth-child(3n+1) .reviews_stars_hover,\
	.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title,\
	.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title.ui-state-active,\
	.sc_blogger.layout_list_1 li a:hover,\
	.sc_blogger.layout_list_2 li a:hover,\
	.sc_blogger.layout_list_3 li a:hover,\
			a:hover,\
			 .vc_proparty_featured_projects_tmpl h4 a:hover {\
			color: '+clr+';\
		}\
	.rev_slider_wrapper .tp-caption a:not(.sc_button):hover,\
	.sc_events .isotope_item_classic2 h5 a:hover,\
	.flat-light .esg-navigationbutton:hover,\
	.flat-light .esg-navigationbutton.selected,\
	.custom_options .menu_user_nav > li > a,\
	.custom_options .menu_user_nav > li > a:after,\
	.days_container_all .booking_day_slots,\
	.top_panel_style_light .content .search_wrap.search_style_regular .search_form_wrap .search_submit:hover {color:'+clr+' !important;}\
	.menu_main_wrap .menu_main_nav_area .menu_main_responsive,\
	.top_panel_style_dark.article_style_boxed .page_top_wrap .breadcrumbs a.breadcrumbs_item:hover,\
	.pagination_single > .pager_numbers,\
	.pagination_single a,\
	.pagination_slider .pager_cur,\
	.pagination_viewmore > a,\
	.sc_button.sc_button_style_filled.sc_button_bg_menu,\
	.viewmore_loader,\
	.mfp-preloader span,\
	.sc_video_frame.sc_video_active:before,\
	.post_featured .post_nav_item:before,\
	.post_featured .post_nav_item .post_nav_info,\
	.reviews_block .reviews_summary .reviews_item,\
	.sc_dropcaps.sc_dropcaps_style_3 .sc_dropcaps_item,\
	.sc_highlight_style_2,\
	.reviews_block .reviews_max_level_100:nth-child(3n+1) .reviews_stars_hover,\
	.reviews_block .reviews_item:nth-child(3n+1) .reviews_slider,\
	.widget_area .widget_calendar td a:hover,\
	.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title .sc_toggles_icon,\
	.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title:hover .sc_toggles_icon_opened,\
	.tribe-events-button, #tribe-events .tribe-events-button:hover,\
	.sc_icon_shape_round.sc_icon_bg_menu,\
	.sc_icon_shape_square.sc_icon_bg_menu,\
	.tribe-events-button, #tribe-events .tribe-events-button,\
	.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title:hover .sc_accordion_icon_opened,\
	.scroll_to_top,\
	.sc_infobox.sc_infobox_style_success,\
	.sc_popup:before,\
	.sc_price_block.sc_price_block_style_2,\
	.sc_scroll_controls_wrap a,\
	.sc_slider_controls_wrap a,\
	.custom_options #co_toggle,\
	.woocommerce nav.woocommerce-pagination ul li a,\
	.reviews_block .reviews_summary .reviews_stars,\
	.woocommerce ul.products li.product .add_to_cart_button:hover, .woocommerce-page ul.products li.product .add_to_cart_button:hover,\
	.sc_button.sc_button_style_filled:hover,\
	.sc_contact_form_button button:hover,\
	.woocommerce a.button:hover,\
	.woocommerce-page button.button.alt:hover,\
	.woocommerce button.button.alt:hover,\
	.woocommerce-page button.button:hover,\
	.woocommerce-page a.button:hover,\
	.woocommerce button.button:hover,\
	.woocommerce input.button:hover,\
	.woocommerce-page input.button:hover,\
	.woocommerce-page a.button.alt:hover,\
	.woocommerce input.button.alt:hover,\
	.woocommerce-page input.button.alt:hover,\
	.top_stripe.top_stripe_bg2,\
	.menu_user_wrap .menu_user_nav li > a:hover,\
			.menu_user_wrap .menu_user_nav li:last-child > a:hover,\
	.sc_events .isotope_item_classic .sc_button:hover,\
	.woocommerce nav.woocommerce-pagination ul li span.current {\
			background-color: '+clr+';\
		}\
	.sc_button.sc_emailer_button,\
	.booking_ok_button:hover,\
#booking_submit_button:hover,\
	.esg-pagination-button,\
	.esg-sortbutton-order,\
	.custom_options.co_light #co_toggle {\
			background-color: '+clr+' !important;\
		}\
	.post_content.ih-item.circle.effect1.colored .info,\
	.post_content.ih-item.circle.effect2.colored .info,\
	.post_content.ih-item.circle.effect3.colored .info,\
	.post_content.ih-item.circle.effect4.colored .info,\
	.post_content.ih-item.circle.effect5.colored .info .info-back,\
	.post_content.ih-item.circle.effect6.colored .info,\
	.post_content.ih-item.circle.effect7.colored .info,\
	.post_content.ih-item.circle.effect8.colored .info,\
	.post_content.ih-item.circle.effect9.colored .info,\
	.post_content.ih-item.circle.effect10.colored .info,\
	.post_content.ih-item.circle.effect11.colored .info,\
	.post_content.ih-item.circle.effect12.colored .info,\
	.post_content.ih-item.circle.effect13.colored .info,\
	.post_content.ih-item.circle.effect14.colored .info,\
	.post_content.ih-item.circle.effect15.colored .info,\
	.post_content.ih-item.circle.effect16.colored .info,\
	.post_content.ih-item.circle.effect18.colored .info .info-back,\
	.post_content.ih-item.circle.effect19.colored .info,\
	.post_content.ih-item.circle.effect20.colored .info .info-back,\
	.post_content.ih-item.square.effect1.colored .info,\
	.post_content.ih-item.square.effect2.colored .info,\
	.post_content.ih-item.square.effect3.colored .info,\
	.post_content.ih-item.square.effect4.colored .mask1,\
	.post_content.ih-item.square.effect4.colored .mask2,\
	.post_content.ih-item.square.effect5.colored .info,\
	.post_content.ih-item.square.effect6.colored .info,\
	.post_content.ih-item.square.effect7.colored .info,\
	.post_content.ih-item.square.effect8.colored .info,\
	.post_content.ih-item.square.effect9.colored .info .info-back,\
	.post_content.ih-item.square.effect10.colored .info,\
	.post_content.ih-item.square.effect11.colored .info,\
	.post_content.ih-item.square.effect12.colored .info,\
	.post_content.ih-item.square.effect13.colored .info,\
	.post_content.ih-item.square.effect14.colored .info,\
	.post_content.ih-item.square.effect15.colored .info,\
	.post_content.ih-item.circle.effect20.colored .info .info-back,\
	.post_content.ih-item.square.effect_book.colored .info {\
			background: '+clr+';\
		}\
	.esg-pagination-button, .esg-sortbutton-order,\
	.booking_day_container.booking_day_white:hover a {\
			background: '+clr+' !important;\
		}\
	.sc_chat_inner,\
	.pagination > a,\
	.pagination_single > .pager_numbers,\
	.pagination_single a,\
	.pagination_slider .pager_cur,\
	.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title:hover,\
	.woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span.current,\
	.sc_button.sc_button_style_border.sc_button_bg_menu,\
	.sc_icon_shape_round.sc_icon_bg_menu,\
	.sc_icon_shape_square.sc_icon_bg_menu,\
	.sc_testimonials .sc_slider_controls_wrap a:hover,\
	.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title:hover,\
	.bg_tint_dark .sc_testimonials .sc_slider_controls_wrap a:hover,\
#toc .toc_item.current,\
#toc .toc_item:hover,\
	.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title .sc_toggles_icon {\
			border-color: '+clr+';\
		}\
	.esg-pagination-button, .esg-sortbutton-order\
		{\
			border-color: '+clr+' !important;\
		}\
	.post_content.ih-item.circle.effect1 .spinner,\
	.sc_chat:after,\
	.comments_list_wrap .comment-respond {\
			border-bottom-color: '+clr+';\
		}\
	.sc_chat:after {\
			border-left-color: '+clr+';\
		}\
	.post_content.ih-item.circle.effect1 .spinner {\
			border-right-color: '+clr+';\
		}\
	.comments_list_wrap ul.children,\
	.comments_list_wrap ul > li + li {\
			border-top-color: '+clr+';\
		}\
	.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a:hover,\
	.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a {\
			border-bottom-color: rgba('+rgb.r+','+rgb.g+','+rgb.b+', 0.75);\
		}\
		';

	} else if (option == 'user_color') {

		var clr = val;
		var rgb = axiom_hex2rgb(clr);
		// User menu color styles
		custom_style += '\
		';
		// User menu dark color
		hsb = axiom_hex2hsb(clr);
		hsb.s = Math.min(100, hsb.s + 15);
		hsb.b = Math.max(0, hsb.b - 20);
		clr = axiom_hsb2hex(hsb);
		custom_style += '\
		';

	//} else if (option == 'body_style') {

		//Uncomment next row to apply changes without reloading page
		//jQuery('body').removeClass('body_style_boxed body_style_wide body_style_fullwide body_style_fullscreen').addClass('body_style_'+val);
	} else {
		axiom_custom_options_show_loader();
		//location.reload();
		var loc = jQuery('#co_site_url').val();
		var params = AXIOM_GLOBALS['co_add_params']!=undefined ? AXIOM_GLOBALS['co_add_params'] : {};
		params[option] = val;
		var pos = -1, pos2 = -1, pos3 = -1;
		for (var option in params) {
			val = params[option];
			pos = pos2 = pos3 = -1;
			if ((pos = loc.indexOf('?')) > 0) {
				if ((pos2 = loc.indexOf(option, pos)) > 0) {
					if ((pos3 = loc.indexOf('&', pos2)) > 0)
						loc = loc.substr(0, pos2) + option+'='+val + loc.substr(pos3);
					else
						loc = loc.substr(0, pos2) + option+'='+val;
				} else
					loc += '&'+option+'='+val;
			} else
				loc += '?'+option+'='+val;
		}
		window.location.href = loc;
		return;

	}

	if (custom_style != '') {
		var styles = jQuery('#axiom-customizer-styles-'+option).length > 0 ? jQuery('#axiom-customizer-styles-'+option) : '';
		if (styles.length == 0)
			jQuery('head').append('<style id="axiom-customizer-styles-'+option+'" type="text/css">'+custom_style+'</style>');
		else
			styles.html(custom_style);
	}
}
