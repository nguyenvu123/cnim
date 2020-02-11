<?php
$co_style = 'co_light';    //'co_dark';
$logo_style = axiom_get_custom_option('top_panel_style');
?>
<div class="custom_options_shadow"></div>

<div id="custom_options" class="custom_options <?php echo esc_attr($co_style); ?>">

	<a href="#" id="co_toggle" class="icon-menu-1"></a>



	<div id="sc_custom_scroll" class="co_options sc_scroll sc_scroll_vertical">
		<div class="sc_scroll_wrapper swiper-wrapper">
			<div class="sc_scroll_slide swiper-slide">
				<!--<input type="hidden" id="co_site_url" name="co_site_url" value="<?php /*echo esc_url('http://' . ($_SERVER["HTTP_HOST"]) . ($_SERVER["REQUEST_URI"])); */?>" />-->

				<div class="light_section">

				<?php if ((axiom_get_custom_option('show_contact_info')=='yes') || ((axiom_get_theme_option('show_login')=='yes'))) { ?>
				<div class="co_section phone_login">
					<?php if (axiom_get_custom_option('show_contact_info')=='yes') { ?>
					<div class="menu_user_area menu_user_left menu_user_contact_area"><?php echo force_balance_tags(trim(axiom_get_custom_option('contact_info'))); ?></div>
					<?php } ?>

					<?php if (axiom_get_theme_option('show_login')=='yes') { ?>
					<div class="menu_user_area menu_user_right menu_user_nav_area">
						<?php require( axiom_get_file_dir('templates/parts/user-panel.php') ); ?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>

				<div class="co_section logo_hours">
					<div class="logo">
						<a href="<?php echo esc_url(home_url()); ?>"><?php echo !empty($AXIOM_GLOBALS['logo_'.($logo_style)]) ? '<img src="'.esc_url($AXIOM_GLOBALS['logo_'.($logo_style)]).'" class="logo_main" alt=""><img src="'.esc_url($AXIOM_GLOBALS['logo_fixed']).'" class="logo_fixed" alt="">' : ''; ?></a><?php echo (axiom_get_custom_option('work_hours') ? '<span class="work_hours">'.(axiom_get_custom_option('work_hours')).'</span>' : ''); ?>
					</div>
				</div>

				<?php
				if (axiom_get_theme_option('show_emergency_phone') == 'yes')
					echo '<div class="emergency_phone">' . __('EMERGENCY CALL:','axiom') . ' ' . axiom_get_theme_option('emergency_phone') . '</div>';
				?>

				</div>

				<div class="co_section">
					<?php
					if (empty($AXIOM_GLOBALS['menu_left'])) $AXIOM_GLOBALS['menu_left'] = axiom_get_nav_menu('menu_left');
					if (empty($AXIOM_GLOBALS['menu_left'])) $AXIOM_GLOBALS['menu_left'] = axiom_get_nav_menu();
					echo ($AXIOM_GLOBALS['menu_left']);
					?>
				</div>

				<?php if (axiom_get_custom_option('show_search')=='yes') echo '<div class="co_section">' . do_shortcode('[trx_search style="flat" open="fixed" title="'.__('To search type and hit enter', 'axiom').'"]') . '</div>'; ?>

				<?php echo '<div class="co_section">' . do_shortcode('[trx_socials size="small"][/trx_socials]') . '</div>  '; ?>

			</div><!-- .sc_scroll_slide -->
		</div><!-- .sc_scroll_wrapper -->
		<div id="sc_custom_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical sc_custom_scroll_bar"></div>
	</div><!-- .sc_scroll -->
</div><!-- .custom_options -->