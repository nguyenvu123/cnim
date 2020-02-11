<?php
/**
 * AxiomThemes Framework
 *
 * @package axiomthemes
 * @since axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'AXIOM_FW_DIR' ) )		define( 'AXIOM_FW_DIR', '/fw/' );

// Theme timing
if ( ! defined( 'AXIOM_START_TIME' ) )	define( 'AXIOM_START_TIME', microtime());			// Framework start time
if ( ! defined( 'AXIOM_START_MEMORY' ) )	define( 'AXIOM_START_MEMORY', memory_get_usage());	// Memory usage before core loading

// Global variables storage
global $AXIOM_GLOBALS;
$AXIOM_GLOBALS = array();

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'axiom_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'axiom_loader_theme_setup', 20 );
	function axiom_loader_theme_setup() {
		// Before init theme
		do_action('axiom_action_before_init_theme');

		// Load current values for main theme options
		axiom_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			axiom_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */
// core.strings must be first - we use axiom_str...() in the axiom_get_file_dir()
// core.files must be first - we use axiom_get_file_dir() to include all rest parts
require_once( (file_exists(get_stylesheet_directory().(AXIOM_FW_DIR).'core/core.strings.php') ? get_stylesheet_directory() : get_template_directory()).(AXIOM_FW_DIR).'core/core.strings.php' );
require_once( (file_exists(get_stylesheet_directory().(AXIOM_FW_DIR).'core/core.files.php') ? get_stylesheet_directory() : get_template_directory()).(AXIOM_FW_DIR).'core/core.files.php' );
axiom_autoload_folder( 'core' );

// Include custom theme files
axiom_autoload_folder( 'includes' );

// Include theme templates
axiom_autoload_folder( 'templates' );

// Include theme widgets
axiom_autoload_folder( 'widgets' );
?>