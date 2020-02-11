<?php
if (axiom_get_custom_option('show_slider')=='yes') {
	$slider = axiom_get_custom_option('slider_engine');
	$slider_alias = $slider_ids = $slider_html = '';

	if ($slider == 'revo' && axiom_exists_revslider()) {
		$slider_alias = axiom_get_custom_option('slider_alias');
		if (!empty($slider_alias)) $slider_html = do_shortcode('[rev_slider '.esc_attr($slider_alias).']');

	} else if ($slider == 'royal' && axiom_exists_royalslider()) {
		$slider_alias = get_new_royalslider($slider_alias);
		if (!empty($slider_alias)) $slider_html = do_shortcode('[rev_slider '.esc_attr($slider_alias).']');
		axiom_enqueue_style(  'new-royalslider-core-css', NEW_ROYALSLIDER_PLUGIN_URL . 'lib/royalslider/royalslider.css', array(), null );
		axiom_enqueue_script( 'new-royalslider-main-js', NEW_ROYALSLIDER_PLUGIN_URL . 'lib/royalslider/jquery.royalslider.min.js', array('jquery'), NEW_ROYALSLIDER_WP_VERSION, true );

	} else if ($slider == 'swiper') {
		$slider_pagination = axiom_get_custom_option("slider_pagination");
		$slider_alias = axiom_get_custom_option("slider_category");
		$slider_orderby = axiom_get_custom_option("slider_orderby");
		$slider_order = axiom_get_custom_option("slider_order");
		$slider_count = $slider_ids = axiom_get_custom_option("slider_posts");

		if (axiom_strpos($slider_ids, ',')!==false) {
			$slider_alias = '';
			$slider_count = 0;
		} else {
			$slider_ids = '';
			if (empty($slider_count)) $slider_count = 3;
		}

		$slider_interval = axiom_get_custom_option("slider_interval");

		if ($slider_count > 0 || !empty($slider_ids)) {
			$slider_html = do_shortcode('[trx_slider'
							. ' custom="no"'
							. ' crop="no"'
							. ' controls="0"'
							. ' engine="'.esc_attr($slider).'"'
							. ' height="'.max(100, axiom_get_custom_option('slider_height')).'"'
							. ' titles="'.esc_attr(axiom_get_custom_option("slider_infobox")).'"'
							. ($slider_interval		? ' interval="'.esc_attr($slider_interval).'"' : '') 
							. ($slider_alias		? ' cat="'.esc_attr($slider_alias).'"' : '') 
							. ($slider_ids			? ' ids="'.esc_attr($slider_ids).'"' : '') 
							. ($slider_count		? ' count="'.esc_attr($slider_count).'"' : '') 
							. ($slider_orderby		? ' orderby="'.esc_attr($slider_orderby).'"' : '') 
							. ($slider_order		? ' order="'.esc_attr($slider_order).'"' : '') 
							. ($slider_pagination	? ' pagination="'.esc_attr($slider_pagination).'"' : '') 
							. '][/trx_slider]');
		}
	}

	// if slider selected
	if (!empty($slider_html)) {
		?>
		<section class="slider_wrap slider_<?php echo esc_attr(axiom_get_custom_option('slider_display')); ?> slider_engine_<?php echo esc_attr($slider); ?> slider_alias_<?php echo esc_attr($slider_alias); ?>">
			<?php echo ($slider_html); ?>
		</section>
		<?php 
	}
}
?>