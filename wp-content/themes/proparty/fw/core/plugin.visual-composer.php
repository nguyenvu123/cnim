<?php
/* Visual Composer support functions
------------------------------------------------------------------------------- */

// Check if Visual Composer installed and activated
if ( !function_exists( 'axiom_exists_visual_composer' ) ) {
	function axiom_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'axiom_vc_is_frontend' ) ) {
	function axiom_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
		//return function_exists('vc_is_frontend_editor') && vc_is_frontend_editor();
	}
}
?>