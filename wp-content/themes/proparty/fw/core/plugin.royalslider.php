<?php
/* Royal Slider support functions
------------------------------------------------------------------------------- */

// Check if Royal Slider installed and activated
if ( !function_exists( 'axiom_exists_royalslider' ) ) {
	function axiom_exists_royalslider() {
		return class_exists("NewRoyalSliderMain");
		//return is_plugin_active('new-royalslider/newroyalslider.php');
	}
}
?>