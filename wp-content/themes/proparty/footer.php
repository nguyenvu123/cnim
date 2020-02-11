<?php
/**
 * The template for displaying the footer.
 */

global $AXIOM_GLOBALS;

				axiom_close_wrapper();	// <!-- </.content> -->

				// Show main sidebar
				get_sidebar();

				if (axiom_get_custom_option('body_style')!='fullscreen') axiom_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// User custom footer
			if (axiom_get_custom_option('show_user_footer') == 'yes') {
				$user_footer = axiom_strclear(axiom_get_custom_option('user_footer_content'), 'p');
				if (!empty($user_footer)) {
					$user_footer = axiom_substitute_all($user_footer);
					?>
					<footer class="user_footer_wrap"><?php echo ($user_footer); ?></footer>
					<?php
				}
			}

			// Footer sidebar
			$footer_show  = axiom_get_custom_option('show_sidebar_footer');
			$footer_parts = explode(' ', $footer_show);
			$footer_tint  = !empty($footer_parts[0]) ? $footer_parts[0] : 'dark';
			$footer_style = !empty($footer_parts[1]) ? $footer_parts[1] : $footer_tint;
			if (!axiom_sc_param_is_off($footer_show)) {
				$AXIOM_GLOBALS['current_sidebar'] = 'footer';
				?>
				<footer class="footer_wrap bg_tint_<?php echo esc_attr($footer_tint); ?> footer_style_<?php echo esc_attr($footer_style); ?> widget_area">
					<div class="content_wrap">
						<div class="columns_wrap">
						<?php
						do_action( 'before_sidebar' );
						if ( is_active_sidebar( axiom_get_custom_option('sidebar_footer') ) ) { //remove it so SB can work
							if ( ! dynamic_sidebar( axiom_get_custom_option('sidebar_footer') ) ) {
								// Put here html if user no set widgets in sidebar
							}
						}
						do_action( 'after_sidebar' );
						?>
						</div>	<!-- /.columns_wrap -->
					</div>	<!-- /.content_wrap -->
				</footer>	<!-- /.footer_wrap -->
			<?php
			}

			// Footer Testimonials stream
			$show = axiom_get_custom_option('show_testimonials_in_footer');
			if (!axiom_sc_param_is_off($show)) {
				$count = max(1, axiom_get_custom_option('testimonials_count'));
				$data = do_shortcode('[trx_testimonials count="'.esc_attr($count).'"][/trx_testimonials]');
				if ($data) {
					$bg_image = axiom_get_custom_option('testimonials_bg_image');
					$bg_color = apply_filters('axiom_filter_get_menu_color', axiom_get_custom_option('testimonials_bg_color'));
					$bg_overlay = max(0, min(1, axiom_get_custom_option('testimonials_bg_overlay')));
					if ($bg_overlay > 0) {
						$rgb = axiom_hex2rgb($bg_color);
						$over_color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['b'].','.(int)$rgb['b'].','.(float)$bg_overlay.')';
					}
					?>
					<footer class="testimonials_wrap sc_section bg_tint_<?php echo esc_attr($show); ?>" style="<?php echo ($bg_image ? 'background-image: url('.esc_url($bg_image).');' : '') . ($bg_color ? 'background-color:'.esc_attr($bg_color).';' : ''); ?>">
						<div class="sc_section_overlay" data-bg_color="<?php echo esc_attr($bg_color); ?>" data-overlay="<?php echo esc_attr($bg_overlay); ?>"<?php echo ($bg_overlay > 0 ? ' style="background-color:'. esc_attr($over_color).';"' : ''); ?>>
							<h1 class="sc_title sc_title_regular sc_align_center styling3" style="margin-top:-13px;margin-bottom:35px;text-align:center;" data-animation="animated fadeInUp normal"><?php echo __('Testimonials', 'axiom') ?></h1>
							<div class="content_wrap"><?php echo ($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}


			// Footer Twitter stream
			$show = axiom_get_custom_option('show_twitter_in_footer');
			if (!axiom_sc_param_is_off($show)) {
				$count = max(1, axiom_get_custom_option('twitter_count'));
				$data = do_shortcode('[trx_twitter count="'.esc_attr($count).'"]');
				if ($data) {
					$bg_image = axiom_get_custom_option('twitter_bg_image');
					$bg_color = apply_filters('axiom_filter_get_link_color', axiom_get_custom_option('twitter_bg_color'));
					$bg_overlay = max(0, min(1, axiom_get_custom_option('twitter_bg_overlay')));
					if ($bg_overlay > 0) {
						$rgb = axiom_hex2rgb($bg_color);
						$over_color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['b'].','.(int)$rgb['b'].','.(float)$bg_overlay.')';
					}
					?>
					<footer class="twitter_wrap sc_section bg_tint_<?php echo esc_attr($show); ?>" style="<?php echo ($bg_image ? 'background-image: url('.esc_url($bg_image).');' : '') . ($bg_color ? 'background-color:'.esc_url($bg_color).';' : ''); ?>">
						<div class="sc_section_overlay" data-bg_color="<?php echo esc_attr($bg_color); ?>" data-overlay="<?php echo esc_attr($bg_overlay); ?>"<?php echo ($bg_overlay > 0 ? ' style="background-color:'. esc_attr($over_color).';"' : ''); ?>>
							<div class="content_wrap"><?php echo ($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}

			// Emergency Call
			if ((axiom_get_custom_option('show_emergency_call') == 'yes') && (axiom_get_theme_option('emergency_phone') != '')) { ?>
				<footer class="emergency_call_wrap sc_section">
					<div class="sc_section_overlay">
						<div class="content_wrap">
							<div class="icon-end"></div>
							<h3><?php _e('Emergency call:','axiom'); ?></h3>
							<h4><?php echo axiom_get_theme_option('emergency_phone') ?></h4>
						</div>
					</div>
				</footer>
			<?php }

			// Google map
			if ( axiom_get_custom_option('show_googlemap')=='yes' ) {
				$map_address = axiom_get_custom_option('googlemap_address');
				$map_latlng  = axiom_get_custom_option('googlemap_latlng');
				$map_zoom    = axiom_get_custom_option('googlemap_zoom');
				$map_style   = axiom_get_custom_option('googlemap_style');
				$map_height  = axiom_get_custom_option('googlemap_height');
				if (!empty($map_address) || !empty($map_latlng)) {
					echo do_shortcode('[trx_googlemap'
							. (!empty($map_address) ? ' address="'.esc_attr($map_address).'"' : '')
							. (!empty($map_latlng)  ? ' latlng="'.esc_attr($map_latlng).'"' : '')
							. (!empty($map_style)   ? ' style="'.esc_attr($map_style).'"' : '')
							. (!empty($map_zoom)    ? ' zoom="'.esc_attr($map_zoom).'"' : '')
							. (!empty($map_height)  ? ' height="'.esc_attr($map_height).'"' : '')
							. ']');
				}
			}

			// Footer contacts
			if (($contacts_style = axiom_get_custom_option('show_contacts_in_footer')) != 'hide'  ) {
				$address_1 = axiom_get_theme_option('contact_address_1');
				$address_2 = axiom_get_theme_option('contact_address_2');
				$phone = axiom_get_theme_option('contact_phone');
				$fax = axiom_get_theme_option('contact_fax');
				if (!empty($address_1) || !empty($address_2) || !empty($phone) || !empty($fax)) {
					?>
					<footer class="contacts_wrap bg_tint_<?php echo esc_attr($contacts_style); ?> contacts_style_<?php echo esc_attr($contacts_style); ?>">
						<div class="content_wrap">
							<div class="logo">
								<a href="<?php echo home_url(); ?>"><?php echo ($AXIOM_GLOBALS['logo_footer'] ? '<img src="'.esc_url($AXIOM_GLOBALS['logo_footer']).'" alt="">' : ''); ?><?php echo ($AXIOM_GLOBALS['logo_text'] ? '<span class="logo_text">'.($AXIOM_GLOBALS['logo_text']).'</span>' : ''); ?></a>
							</div>
							<div class="contacts_address">
								<address class="address_right">
									<?php echo __('Phone:', 'axiom') . ' ' . ($phone); ?><br>
									<?php echo __('Fax:', 'axiom') . ' ' . ($fax); ?>
								</address>
								<address class="address_left">
									<?php echo ($address_2); ?><br>
									<?php echo ($address_1); ?>
								</address>
							</div>
							<?php echo do_shortcode('[trx_socials size="big"][/trx_socials]'); ?>
						</div>	<!-- /.content_wrap -->
					</footer>	<!-- /.contacts_wrap -->
					<?php
				}
			}

			// Copyright area
			if (axiom_get_custom_option('show_copyright_in_footer')=='yes') {
			?> 
				<div class="copyright_wrap">
					<div class="content_wrap">
						<div class="copyright"><?php echo force_balance_tags(axiom_get_theme_option('footer_copyright')); ?></div>
						<?php
						global $AXIOM_GLOBALS;
						if (empty($AXIOM_GLOBALS['menu_copy']))
							$AXIOM_GLOBALS['menu_copy'] = axiom_get_nav_menu('menu_copy');
						if (empty($AXIOM_GLOBALS['menu_copy'])) {
						?>
						<ul id="menu_copy">
							<?php
							} else {
								$menu = axiom_substr($AXIOM_GLOBALS['menu_copy'], 0, axiom_strlen($AXIOM_GLOBALS['menu_copy'])-5);
								$pos = axiom_strpos($menu, '<ul');
								if ($pos!==false) $menu = axiom_substr($menu, 0, $pos+3) . axiom_substr($menu, $pos+3);
								echo str_replace('class=""', '', $menu);
							}
							?>
						</ul>
					</div>
				</div>
			<?php } ?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->

<?php
if (axiom_get_custom_option('show_theme_customizer')=='yes') {
	require_once( axiom_get_file_dir('core/core.customizer/front.customizer.php') );
}
?>

<?php
if (axiom_get_custom_option('show_left_panel')=='yes') {
	axiom_enqueue_slider();
	require_once( axiom_get_file_dir('templates/parts/left-panel.php') );
}
?>

<?php
if ((axiom_get_theme_option('show_login')=='yes') || (axiom_get_custom_option('show_left_panel')=='yes')) {
	require_once( axiom_get_file_dir('templates/parts/register.php') );
}?>

<?php
if ((axiom_get_theme_option('show_login')=='yes') || (axiom_get_custom_option('show_left_panel')=='yes')) {
	require_once( axiom_get_file_dir('templates/parts/login.php') );
}?>

<?php
if (axiom_get_custom_option('show_scroll_to_top') == 'yes') { ?>
<a href="#" class="scroll_to_top icon-angle-up-1" title="<?php _e('Scroll to top', 'axiom'); ?>"></a>
<?php } ?>

<?php wp_footer(); ?>

</body>
</html>