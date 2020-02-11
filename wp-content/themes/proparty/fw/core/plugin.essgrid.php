<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */

// Check if Ess. Grid installed and activated
if ( !function_exists( 'axiom_exists_essgrid' ) ) {
	function axiom_exists_essgrid() {
		return is_plugin_active('essential-grid/essential-grid.php');
	}
}
?>