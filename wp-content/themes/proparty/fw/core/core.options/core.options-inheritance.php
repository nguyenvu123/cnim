<?php
//####################################################
//#### Inheritance system (for internal use only) #### 
//####################################################

// Add item to the inheritance settings
if ( !function_exists( 'axiom_add_theme_inheritance' ) ) {
	function axiom_add_theme_inheritance($options, $append=true) {
		global $AXIOM_GLOBALS;
		if (!isset($AXIOM_GLOBALS["inheritance"])) $AXIOM_GLOBALS["inheritance"] = array();
		$AXIOM_GLOBALS['inheritance'] = $append
			? axiom_array_merge($AXIOM_GLOBALS['inheritance'], $options)
			: axiom_array_merge($options, $AXIOM_GLOBALS['inheritance']);
	}
}



// Return inheritance settings
if ( !function_exists( 'axiom_get_theme_inheritance' ) ) {
	function axiom_get_theme_inheritance($key = '') {
		global $AXIOM_GLOBALS;
		return $key ? $AXIOM_GLOBALS['inheritance'][$key] : $AXIOM_GLOBALS['inheritance'];
	}
}



// Detect inheritance key for the current mode
if ( !function_exists( 'axiom_detect_inheritance_key' ) ) {
	function axiom_detect_inheritance_key() {
		static $inheritance_key = '';
		if (!empty($inheritance_key)) return $inheritance_key;
		$inheritance_key = apply_filters('axiom_filter_detect_inheritance_key', '');
		return $inheritance_key;
	}
}


// Return key for override parameter
if ( !function_exists( 'axiom_get_override_key' ) ) {
	function axiom_get_override_key($value, $by) {
		$key = '';
		$inheritance = axiom_get_theme_inheritance();
		if (!empty($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v[$by]) && in_array($value, $v[$by])) {
					$key = $by=='taxonomy' 
						? $value
						: (!empty($v['override']) ? $v['override'] : $k);
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for categories) by post_type from inheritance array
if ( !function_exists( 'axiom_get_taxonomy_categories_by_post_type' ) ) {
	function axiom_get_taxonomy_categories_by_post_type($value) {
		$key = '';
		$inheritance = axiom_get_theme_inheritance();
		if (!empty($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy']) ? $v['taxonomy'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for tags) by post_type from inheritance array
if ( !function_exists( 'axiom_get_taxonomy_tags_by_post_type' ) ) {
	function axiom_get_taxonomy_tags_by_post_type($value) {
		$key = '';
		$inheritance = axiom_get_theme_inheritance();
		if (!empty($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy_tags']) ? $v['taxonomy_tags'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}
?>