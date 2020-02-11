<?php if (axiom_get_custom_option('show_menu_user')=='no') {
	if (axiom_get_custom_option('top_panel_position')=='hide') { ?>
	<div id="sidemenu_button" class="top_panel_hidden"><i class="icon-menu-1"></i></div>
<?php } else { ?>
	<div id="sidemenu_button"><i class="icon-menu-1"></i></div>
<?php } } ?>

<div class="sidemenu_wrap swpLeftPos">
	<div class="menuTranform">
		<div id="sidemenu_scroll" class="co_options sc_scroll sc_scroll_vertical">
			<div class="sc_scroll_wrapper swiper-wrapper">
				<div class="sc_scroll_slide swiper-slide">
					<div id="popup_menu" class="popup_wrapper">
						<nav role="navigation" class="sidemenu_area">
							<?php
							if (empty($AXIOM_GLOBALS['menu_panel'])) $AXIOM_GLOBALS['menu_panel'] = axiom_get_nav_menu('menu_panel');
							if (empty($AXIOM_GLOBALS['menu_panel'])) $AXIOM_GLOBALS['menu_panel'] = axiom_get_nav_menu();
							echo ($AXIOM_GLOBALS['menu_panel']);
							?>
						</nav>
						<div class="sidemenu_close">x</div>
					</div>
				</div>
			</div>
			<div id="sidemenu_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical sidemenu_scroll_bar"></div>
		</div>
	</div>
</div>