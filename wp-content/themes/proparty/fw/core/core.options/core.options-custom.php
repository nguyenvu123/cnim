<?php
/**
 * AxiomThemes Framework: Theme options custom fields
 *
 * @package	axiom
 * @since	axiom 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_options_custom_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_options_custom_theme_setup' );
	function axiom_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'axiom_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'axiom_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'axiom_options_custom_load_scripts');
	function axiom_options_custom_load_scripts() {
		axiom_enqueue_script( 'axiom-options-custom-script',	axiom_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
function axiom_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviews_block">' . trim(axiom_reviews_get_markup($field, $value, true)) . '</div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? __( 'Choose Images', 'axiom') : __( 'Choose Image', 'axiom')).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? __( 'Add to Gallery', 'axiom') : __( 'Choose Image', 'axiom')).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.esc_attr($field['media_field_id']).'"
				onclick="axiom_show_media_manager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? __( 'Choose Images', 'axiom') : __( 'Choose Image', 'axiom')) . '</a>';
			break;
	}
	return apply_filters('axiom_filter_show_custom_field', $output, $id, $field, $value);
}
?>