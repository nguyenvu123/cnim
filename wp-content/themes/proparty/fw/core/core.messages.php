<?php
/**
 * AxiomThemes Framework: messages subsystem
 *
 * @package	axiom
 * @since	axiom 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('axiom_messages_theme_setup')) {
	add_action( 'axiom_action_before_init_theme', 'axiom_messages_theme_setup' );
	function axiom_messages_theme_setup() {
		// Core messages strings
		add_action('axiom_action_add_scripts_inline', 'axiom_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('axiom_get_error_msg')) {
	function axiom_get_error_msg() {
		global $AXIOM_GLOBALS;
		return !empty($AXIOM_GLOBALS['error_msg']) ? $AXIOM_GLOBALS['error_msg'] : '';
	}
}

if (!function_exists('axiom_set_error_msg')) {
	function axiom_set_error_msg($msg) {
		global $AXIOM_GLOBALS;
		$msg2 = axiom_get_error_msg();
		$AXIOM_GLOBALS['error_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('axiom_get_success_msg')) {
	function axiom_get_success_msg() {
		global $AXIOM_GLOBALS;
		return !empty($AXIOM_GLOBALS['success_msg']) ? $AXIOM_GLOBALS['success_msg'] : '';
	}
}

if (!function_exists('axiom_set_success_msg')) {
	function axiom_set_success_msg($msg) {
		global $AXIOM_GLOBALS;
		$msg2 = axiom_get_success_msg();
		$AXIOM_GLOBALS['success_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('axiom_get_notice_msg')) {
	function axiom_get_notice_msg() {
		global $AXIOM_GLOBALS;
		return !empty($AXIOM_GLOBALS['notice_msg']) ? $AXIOM_GLOBALS['notice_msg'] : '';
	}
}

if (!function_exists('axiom_set_notice_msg')) {
	function axiom_set_notice_msg($msg) {
		global $AXIOM_GLOBALS;
		$msg2 = axiom_get_notice_msg();
		$AXIOM_GLOBALS['notice_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('axiom_set_system_msg')) {
	function axiom_set_system_msg($msg, $status='info', $hdr='') {
		update_option('axiom_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('axiom_get_system_msg')) {
	function axiom_get_system_msg($del=false) {
		$msg = get_option('axiom_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			axiom_del_system_msg();
		return $msg;
	}
}

if (!function_exists('axiom_del_system_msg')) {
	function axiom_del_system_msg() {
		delete_option('axiom_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('axiom_messages_add_scripts_inline')) {
	function axiom_messages_add_scripts_inline() {
		global $AXIOM_GLOBALS;
		echo '<script type="text/javascript">'
			. 'jQuery(document).ready(function() {'
			// Strings for translation
			. 'AXIOM_GLOBALS["strings"] = {'
				. 'bookmark_add: 		"' . addslashes(__('Add the bookmark', 'axiom')) . '",'
				. 'bookmark_added:		"' . addslashes(__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'axiom')) . '",'
				. 'bookmark_del: 		"' . addslashes(__('Delete this bookmark', 'axiom')) . '",'
				. 'bookmark_title:		"' . addslashes(__('Enter bookmark title', 'axiom')) . '",'
				. 'bookmark_exists:		"' . addslashes(__('Current page already exists in the bookmarks list', 'axiom')) . '",'
				. 'search_error:		"' . addslashes(__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'axiom')) . '",'
				. 'email_confirm:		"' . addslashes(__('On the e-mail address <b>%s</b> we sent a confirmation email.<br>Please, open it and click on the link.', 'axiom')) . '",'
				. 'reviews_vote:		"' . addslashes(__('Thanks for your vote! New average rating is:', 'axiom')) . '",'
				. 'reviews_error:		"' . addslashes(__('Error saving your vote! Please, try again later.', 'axiom')) . '",'
				. 'error_like:			"' . addslashes(__('Error saving your like! Please, try again later.', 'axiom')) . '",'
				. 'error_global:		"' . addslashes(__('Global error text', 'axiom')) . '",'
				. 'name_empty:			"' . addslashes(__('The name can\'t be empty', 'axiom')) . '",'
				. 'name_long:			"' . addslashes(__('Too long name', 'axiom')) . '",'
				. 'email_empty:			"' . addslashes(__('Too short (or empty) email address', 'axiom')) . '",'
				. 'email_long:			"' . addslashes(__('Too long email address', 'axiom')) . '",'
				. 'email_not_valid:		"' . addslashes(__('Invalid email address', 'axiom')) . '",'
				. 'subject_empty:		"' . addslashes(__('The subject can\'t be empty', 'axiom')) . '",'
				. 'subject_long:		"' . addslashes(__('Too long subject', 'axiom')) . '",'
				. 'text_empty:			"' . addslashes(__('The message text can\'t be empty', 'axiom')) . '",'
				. 'text_long:			"' . addslashes(__('Too long message text', 'axiom')) . '",'
				. 'send_complete:		"' . addslashes(__("Send message complete!", 'axiom')) . '",'
				. 'send_error:			"' . addslashes(__('Transmit failed!', 'axiom')) . '",'
				. 'login_empty:			"' . addslashes(__('The Login field can\'t be empty', 'axiom')) . '",'
				. 'login_long:			"' . addslashes(__('Too long login field', 'axiom')) . '",'
				. 'password_empty:		"' . addslashes(__('The password can\'t be empty and shorter then 5 characters', 'axiom')) . '",'
				. 'password_long:		"' . addslashes(__('Too long password', 'axiom')) . '",'
				. 'password_not_equal:	"' . addslashes(__('The passwords in both fields are not equal', 'axiom')) . '",'
				. 'registration_success:"' . addslashes(__('Registration success! Please log in!', 'axiom')) . '",'
				. 'registration_failed:	"' . addslashes(__('Registration failed!', 'axiom')) . '",'
				. 'geocode_error:		"' . addslashes(__('Geocode was not successful for the following reason:', 'wspace')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(__('Google map API not available!', 'axiom')) . '",'
				. 'editor_save_success:	"' . addslashes(__("Post content saved!", 'axiom')) . '",'
				. 'editor_save_error:	"' . addslashes(__("Error saving post data!", 'axiom')) . '",'
				. 'editor_delete_post:	"' . addslashes(__("You really want to delete the current post?", 'axiom')) . '",'
				. 'editor_delete_post_header:"' . addslashes(__("Delete post", 'axiom')) . '",'
				. 'editor_delete_success:	"' . addslashes(__("Post deleted!", 'axiom')) . '",'
				. 'editor_delete_error:		"' . addslashes(__("Error deleting post!", 'axiom')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(__('Cancel', 'axiom')) . '",'
				. 'editor_caption_close:	"' . addslashes(__('Close', 'axiom')) . '"'
				. '};'
			. '});'
			. '</script>';
	}
}
?>