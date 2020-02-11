<?php
/* BB Press support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('axiom_bbpress_theme_setup')) {
	add_action( 'axiom_action_before_init_theme', 'axiom_bbpress_theme_setup' );
	function axiom_bbpress_theme_setup() {
		if (axiom_is_bbpress_page()) {
			add_action( 'axiom_action_add_styles', 'axiom_bbpress_frontend_scripts' );
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('axiom_filter_detect_inheritance_key',	'axiom_bbpress_detect_inheritance_key', 9, 1);
		}
	}
}
if ( !function_exists( 'axiom_bbpress_settings_theme_setup2' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_bbpress_settings_theme_setup2', 3 );
	function axiom_bbpress_settings_theme_setup2() {
		if (axiom_exists_bbpress()) {
			axiom_add_theme_inheritance( array('bbpress' => array(
				'stream_template' => 'bbpress',
				'single_template' => '',
				'taxonomy' => array(),
				'taxonomy_tags' => array(),
				'post_type' => array(),
				'override' => 'page'
				) )
			);
		}
	}
}


// Check if BB Press installed and activated
if ( !function_exists( 'axiom_exists_bbpress' ) ) {
	function axiom_exists_bbpress() {
		return class_exists( 'bbPress' );
	}
}

// Check if current page is BB Press page
if ( !function_exists( 'axiom_is_bbpress_page' ) ) {
	function axiom_is_bbpress_page() {
		return function_exists('is_bbpress') && is_bbpress();
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'axiom_bbpress_detect_inheritance_key' ) ) {
	//add_filter('axiom_filter_detect_inheritance_key',	'axiom_bbpress_detect_inheritance_key', 9, 1);
	function axiom_bbpress_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return axiom_is_bbpress_page() ? 'bbpress' : '';
	}
}

// Enqueue BB Press custom styles
if ( !function_exists( 'axiom_bbpress_frontend_scripts' ) ) {
	//add_action( 'axiom_action_add_styles', 'axiom_bbpress_frontend_scripts' );
	function axiom_bbpress_frontend_scripts() {
		axiom_enqueue_style( 'bbpress-style',  axiom_get_file_url('css/bbpress-style.css'), array(), null );
	}
}
?>