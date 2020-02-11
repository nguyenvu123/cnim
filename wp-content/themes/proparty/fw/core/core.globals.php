<?php
/**
 * AxiomThemes Framework: global variables storage
 *
 * @package	axiom
 * @since	axiom 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get global variable
if (!function_exists('axiom_get_global')) {
	function axiom_get_global($var_name) {
		global $AXIOM_GLOBALS;
		return isset($AXIOM_GLOBALS[$var_name]) ? $AXIOM_GLOBALS[$var_name] : '';
	}
}

// Set global variable
if (!function_exists('axiom_set_global')) {
	function axiom_set_global($var_name, $value) {
		global $AXIOM_GLOBALS;
		$AXIOM_GLOBALS[$var_name] = $value;
	}
}

// Inc/Dec global variable with specified value
if (!function_exists('axiom_inc_global')) {
	function axiom_inc_global($var_name, $value=1) {
		global $AXIOM_GLOBALS;
		$AXIOM_GLOBALS[$var_name] += $value;
	}
}

// Concatenate global variable with specified value
if (!function_exists('axiom_concat_global')) {
	function axiom_concat_global($var_name, $value) {
		global $AXIOM_GLOBALS;
		$AXIOM_GLOBALS[$var_name] .= $value;
	}
}

// Get global array element
if (!function_exists('axiom_get_global_array')) {
	function axiom_get_global_array($var_name, $key) {
		global $AXIOM_GLOBALS;
		return isset($AXIOM_GLOBALS[$var_name][$key]) ? $AXIOM_GLOBALS[$var_name][$key] : '';
	}
}

// Set global array element
if (!function_exists('axiom_set_global_array')) {
	function axiom_set_global_array($var_name, $key, $value) {
		global $AXIOM_GLOBALS;
		if (!isset($AXIOM_GLOBALS[$var_name])) $AXIOM_GLOBALS[$var_name] = array();
		$AXIOM_GLOBALS[$var_name][$key] = $value;
	}
}

// Inc/Dec global array element with specified value
if (!function_exists('axiom_inc_global_array')) {
	function axiom_inc_global_array($var_name, $key, $value=1) {
		global $AXIOM_GLOBALS;
		$AXIOM_GLOBALS[$var_name][$key] += $value;
	}
}

// Concatenate global array element with specified value
if (!function_exists('axiom_concat_global_array')) {
	function axiom_concat_global_array($var_name, $key, $value) {
		global $AXIOM_GLOBALS;
		$AXIOM_GLOBALS[$var_name][$key] .= $value;
	}
}
?>