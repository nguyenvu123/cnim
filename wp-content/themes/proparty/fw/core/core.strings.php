<?php
/**
 * AxiomThemes Framework: strings manipulations
 *
 * @package	axiom
 * @since	axiom 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'AXIOM_MULTIBYTE' ) ) define( 'AXIOM_MULTIBYTE', function_exists('mb_strlen') ? 'UTF-8' : false );

if (!function_exists('axiom_strlen')) {
	function axiom_strlen($text) {
		return AXIOM_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('axiom_strpos')) {
	function axiom_strpos($text, $char, $from=0) {
		return AXIOM_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('axiom_strrpos')) {
	function axiom_strrpos($text, $char, $from=0) {
		return AXIOM_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('axiom_substr')) {
	function axiom_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = axiom_strlen($text)-$from;
		}
		return AXIOM_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('axiom_strtolower')) {
	function axiom_strtolower($text) {
		return AXIOM_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('axiom_strtoupper')) {
	function axiom_strtoupper($text) {
		return AXIOM_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('axiom_strtoproper')) {
	function axiom_strtoproper($text) {
		$rez = ''; $last = ' ';
		for ($i=0; $i<axiom_strlen($text); $i++) {
			$ch = axiom_substr($text, $i, 1);
			$rez .= axiom_strpos(' .,:;?!()[]{}+=', $last)!==false ? axiom_strtoupper($ch) : axiom_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('axiom_strrepeat')) {
	function axiom_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('axiom_strshort')) {
	function axiom_strshort($str, $maxlength, $add='...') {
	//	if ($add && axiom_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return '';
		if ($maxlength < 1 || $maxlength >= axiom_strlen($str))
			return strip_tags($str);
		$str = axiom_substr(strip_tags($str), 0, $maxlength - axiom_strlen($add));
		$ch = axiom_substr($str, $maxlength - axiom_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = axiom_strlen($str) - 1; $i > 0; $i--)
				if (axiom_substr($str, $i, 1) == ' ') break;
			$str = trim(axiom_substr($str, 0, $i));
		}
		if (!empty($str) && axiom_strpos(',.:;-', axiom_substr($str, -1))!==false) $str = axiom_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('axiom_strclear')) {
	function axiom_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (axiom_substr($text, 0, axiom_strlen($open))==$open) {
					$pos = axiom_strpos($text, '>');
					if ($pos!==false) $text = axiom_substr($text, $pos+1);
				}
				if (axiom_substr($text, -axiom_strlen($close))==$close) $text = axiom_substr($text, 0, axiom_strlen($text) - axiom_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}
?>