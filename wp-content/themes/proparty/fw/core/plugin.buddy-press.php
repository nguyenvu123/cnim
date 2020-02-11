<?php
/* BuddyPress support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('axiom_buddypress_theme_setup')) {
	add_action( 'axiom_action_before_init_theme', 'axiom_buddypress_theme_setup' );
	function axiom_buddypress_theme_setup() {
		if (axiom_is_buddypress_page()) {
			add_action( 'axiom_action_add_styles', 'axiom_buddypress_frontend_scripts' );
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('axiom_filter_detect_inheritance_key',	'axiom_buddypress_detect_inheritance_key', 9, 1);
		}
	}
}
if ( !function_exists( 'axiom_buddypress_settings_theme_setup2' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_buddypress_settings_theme_setup2', 3 );
	function axiom_buddypress_settings_theme_setup2() {
		if (axiom_exists_buddypress()) {
			axiom_add_theme_inheritance( array('bbpress' => array(
				'stream_template' => 'buddypress',
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

// Check if BuddyPress installed and activated
if ( !function_exists( 'axiom_exists_buddypress' ) ) {
	function axiom_exists_buddypress() {
		return class_exists( 'BuddyPress' );
	}
}

// Check if current page is BuddyPress page
if ( !function_exists( 'axiom_is_buddypress_page' ) ) {
	function axiom_is_buddypress_page() {
		return function_exists('is_buddypress') && is_buddypress();
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'axiom_buddypress_detect_inheritance_key' ) ) {
	//add_filter('axiom_filter_detect_inheritance_key',	'axiom_buddypress_detect_inheritance_key', 9, 1);
	function axiom_buddypress_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return axiom_is_buddypress_page() ? 'buddypress' : '';
	}
}

// Enqueue BuddyPress custom styles
if ( !function_exists( 'axiom_buddypress_frontend_scripts' ) ) {
	//add_action( 'axiom_action_add_styles', 'axiom_buddypress_frontend_scripts' );
	function axiom_buddypress_frontend_scripts() {
		axiom_enqueue_style( 'buddypress-style',  axiom_get_file_url('css/buddypress-style.css'), array(), null );
	}
}

?>