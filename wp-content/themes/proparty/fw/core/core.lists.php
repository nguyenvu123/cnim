<?php
/**
 * AxiomThemes Framework: return lists
 *
 * @package axiom
 * @since axiom 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Return list of the animations
if ( !function_exists( 'axiom_get_list_animations' ) ) {
	function axiom_get_list_animations($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_animations']))
			$list = $AXIOM_GLOBALS['list_animations'];
		else {
			$list = array();
			$list['none']			= __('- None -',	'axiom');
			$list['bounced']		= __('Bounced',		'axiom');
			$list['flash']			= __('Flash',		'axiom');
			$list['flip']			= __('Flip',		'axiom');
			$list['pulse']			= __('Pulse',		'axiom');
			$list['rubberBand']		= __('Rubber Band',	'axiom');
			$list['shake']			= __('Shake',		'axiom');
			$list['swing']			= __('Swing',		'axiom');
			$list['tada']			= __('Tada',		'axiom');
			$list['wobble']			= __('Wobble',		'axiom');
			$AXIOM_GLOBALS['list_animations'] = $list = apply_filters('axiom_filter_list_animations', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'axiom_get_list_animations_in' ) ) {
	function axiom_get_list_animations_in($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_animations_in']))
			$list = $AXIOM_GLOBALS['list_animations_in'];
		else {
			$list = array();
			$list['none']			= __('- None -',	'axiom');
			$list['bounceIn']		= __('Bounce In',			'axiom');
			$list['bounceInUp']		= __('Bounce In Up',		'axiom');
			$list['bounceInDown']	= __('Bounce In Down',		'axiom');
			$list['bounceInLeft']	= __('Bounce In Left',		'axiom');
			$list['bounceInRight']	= __('Bounce In Right',		'axiom');
			$list['fadeIn']			= __('Fade In',				'axiom');
			$list['fadeInUp']		= __('Fade In Up',			'axiom');
			$list['fadeInDown']		= __('Fade In Down',		'axiom');
			$list['fadeInLeft']		= __('Fade In Left',		'axiom');
			$list['fadeInRight']	= __('Fade In Right',		'axiom');
			$list['fadeInUpBig']	= __('Fade In Up Big',		'axiom');
			$list['fadeInDownBig']	= __('Fade In Down Big',	'axiom');
			$list['fadeInLeftBig']	= __('Fade In Left Big',	'axiom');
			$list['fadeInRightBig']	= __('Fade In Right Big',	'axiom');
			$list['flipInX']		= __('Flip In X',			'axiom');
			$list['flipInY']		= __('Flip In Y',			'axiom');
			$list['lightSpeedIn']	= __('Light Speed In',		'axiom');
			$list['rotateIn']		= __('Rotate In',			'axiom');
			$list['rotateInUpLeft']		= __('Rotate In Down Left',	'axiom');
			$list['rotateInUpRight']	= __('Rotate In Up Right',	'axiom');
			$list['rotateInDownLeft']	= __('Rotate In Up Left',	'axiom');
			$list['rotateInDownRight']	= __('Rotate In Down Right','axiom');
			$list['rollIn']				= __('Roll In',			'axiom');
			$list['slideInUp']			= __('Slide In Up',		'axiom');
			$list['slideInDown']		= __('Slide In Down',	'axiom');
			$list['slideInLeft']		= __('Slide In Left',	'axiom');
			$list['slideInRight']		= __('Slide In Right',	'axiom');
			$list['zoomIn']				= __('Zoom In',			'axiom');
			$list['zoomInUp']			= __('Zoom In Up',		'axiom');
			$list['zoomInDown']			= __('Zoom In Down',	'axiom');
			$list['zoomInLeft']			= __('Zoom In Left',	'axiom');
			$list['zoomInRight']		= __('Zoom In Right',	'axiom');
			$AXIOM_GLOBALS['list_animations_in'] = $list = apply_filters('axiom_filter_list_animations_in', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'axiom_get_list_animations_out' ) ) {
	function axiom_get_list_animations_out($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_animations_out']))
			$list = $AXIOM_GLOBALS['list_animations_out'];
		else {
			$list = array();
			$list['none']			= __('- None -',	'axiom');
			$list['bounceOut']		= __('Bounce Out',			'axiom');
			$list['bounceOutUp']	= __('Bounce Out Up',		'axiom');
			$list['bounceOutDown']	= __('Bounce Out Down',		'axiom');
			$list['bounceOutLeft']	= __('Bounce Out Left',		'axiom');
			$list['bounceOutRight']	= __('Bounce Out Right',	'axiom');
			$list['fadeOut']		= __('Fade Out',			'axiom');
			$list['fadeOutUp']		= __('Fade Out Up',			'axiom');
			$list['fadeOutDown']	= __('Fade Out Down',		'axiom');
			$list['fadeOutLeft']	= __('Fade Out Left',		'axiom');
			$list['fadeOutRight']	= __('Fade Out Right',		'axiom');
			$list['fadeOutUpBig']	= __('Fade Out Up Big',		'axiom');
			$list['fadeOutDownBig']	= __('Fade Out Down Big',	'axiom');
			$list['fadeOutLeftBig']	= __('Fade Out Left Big',	'axiom');
			$list['fadeOutRightBig']= __('Fade Out Right Big',	'axiom');
			$list['flipOutX']		= __('Flip Out X',			'axiom');
			$list['flipOutY']		= __('Flip Out Y',			'axiom');
			$list['hinge']			= __('Hinge Out',			'axiom');
			$list['lightSpeedOut']	= __('Light Speed Out',		'axiom');
			$list['rotateOut']		= __('Rotate Out',			'axiom');
			$list['rotateOutUpLeft']	= __('Rotate Out Down Left',	'axiom');
			$list['rotateOutUpRight']	= __('Rotate Out Up Right',		'axiom');
			$list['rotateOutDownLeft']	= __('Rotate Out Up Left',		'axiom');
			$list['rotateOutDownRight']	= __('Rotate Out Down Right',	'axiom');
			$list['rollOut']			= __('Roll Out',		'axiom');
			$list['slideOutUp']			= __('Slide Out Up',		'axiom');
			$list['slideOutDown']		= __('Slide Out Down',	'axiom');
			$list['slideOutLeft']		= __('Slide Out Left',	'axiom');
			$list['slideOutRight']		= __('Slide Out Right',	'axiom');
			$list['zoomOut']			= __('Zoom Out',			'axiom');
			$list['zoomOutUp']			= __('Zoom Out Up',		'axiom');
			$list['zoomOutDown']		= __('Zoom Out Down',	'axiom');
			$list['zoomOutLeft']		= __('Zoom Out Left',	'axiom');
			$list['zoomOutRight']		= __('Zoom Out Right',	'axiom');
			$AXIOM_GLOBALS['list_animations_out'] = $list = apply_filters('axiom_filter_list_animations_out', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}


// Return list of categories
if ( !function_exists( 'axiom_get_list_categories' ) ) {
	function axiom_get_list_categories($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_categories']))
			$list = $AXIOM_GLOBALS['list_categories'];
		else {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			foreach ($taxonomies as $cat) {
				$list[$cat->term_id] = $cat->name;
			}
			$AXIOM_GLOBALS['list_categories'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'axiom_get_list_terms' ) ) {
	function axiom_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_taxonomies_'.($taxonomy)]))
			$list = $AXIOM_GLOBALS['list_taxonomies_'.($taxonomy)];
		else {
			$list = array();
			$args = array(
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => $taxonomy,
				'pad_counts'               => false );
			$taxonomies = get_terms( $taxonomy, $args );
			foreach ($taxonomies as $cat) {
				$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
			}
			$AXIOM_GLOBALS['list_taxonomies_'.($taxonomy)] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'axiom_get_list_posts_types' ) ) {
	function axiom_get_list_posts_types($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_posts_types']))
			$list = $AXIOM_GLOBALS['list_posts_types'];
		else {
			$list = array();
			/* 
			// This way to return all registered post types
			$types = get_post_types();
			if (in_array('post', $types)) $list['post'] = __('Post', 'axiom');
			foreach ($types as $t) {
				if ($t == 'post') continue;
				$list[$t] = axiom_strtoproper($t);
			}
			*/
			// Return only theme inheritance supported post types
			$AXIOM_GLOBALS['list_posts_types'] = $list = apply_filters('axiom_filter_list_post_types', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'axiom_get_list_posts' ) ) {
	function axiom_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		global $AXIOM_GLOBALS;
		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (isset($AXIOM_GLOBALS[$hash]))
			$list = $AXIOM_GLOBALS[$hash];
		else {
			$list = array();
			$list['none'] = __("- Not selected -", 'axiom');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			foreach ($posts as $post) {
				$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
			}
			$AXIOM_GLOBALS[$hash] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}


// Return list of registered users
if ( !function_exists( 'axiom_get_list_users' ) ) {
	function axiom_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_users']))
			$list = $AXIOM_GLOBALS['list_users'];
		else {
			$list = array();
			$list['none'] = __("- Not selected -", 'axiom');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			foreach ($users as $user) {
				$accept = true;
				if (is_array($user->roles)) {
					if (count($user->roles) > 0) {
						$accept = false;
						foreach ($user->roles as $role) {
							if (in_array($role, $roles)) {
								$accept = true;
								break;
							}
						}
					}
				}
				if ($accept) $list[$user->user_login] = $user->display_name;
			}
			$AXIOM_GLOBALS['list_users'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}


// Return sliders list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'axiom_get_list_sliders' ) ) {
	function axiom_get_list_sliders($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_sliders']))
			$list = $AXIOM_GLOBALS['list_sliders'];
		else {
			$list = array();
			$list["swiper"] = __("Posts slider (Swiper)", 'axiom');
			if (axiom_exists_revslider())
				$list["revo"] = __("Layer slider (Revolution)", 'axiom');
			if (axiom_exists_royalslider())
				$list["royal"] = __("Layer slider (Royal)", 'axiom');
			$AXIOM_GLOBALS['list_sliders'] = $list = apply_filters('axiom_filter_list_sliders', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list with popup engines
if ( !function_exists( 'axiom_get_list_popup_engines' ) ) {
	function axiom_get_list_popup_engines($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_popup_engines']))
			$list = $AXIOM_GLOBALS['list_popup_engines'];
		else {
			$list = array();
			$list["pretty"] = __("Pretty photo", 'axiom');
			$list["magnific"] = __("Magnific popup", 'axiom');
			$AXIOM_GLOBALS['list_popup_engines'] = $list = apply_filters('axiom_filter_list_popup_engines', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'axiom_get_list_menus' ) ) {
	function axiom_get_list_menus($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_menus']))
			$list = $AXIOM_GLOBALS['list_menus'];
		else {
			$list = array();
			$list['default'] = __("Default", 'axiom');
			$menus = wp_get_nav_menus();
			if ($menus) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			$AXIOM_GLOBALS['list_menus'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'axiom_get_list_sidebars' ) ) {
	function axiom_get_list_sidebars($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_sidebars'])) {
			$list = $AXIOM_GLOBALS['list_sidebars'];
		} else {
			$list = isset($AXIOM_GLOBALS['registered_sidebars']) ? $AXIOM_GLOBALS['registered_sidebars'] : array();
			$AXIOM_GLOBALS['list_sidebars'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'axiom_get_list_sidebars_positions' ) ) {
	function axiom_get_list_sidebars_positions($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_sidebars_positions']))
			$list = $AXIOM_GLOBALS['list_sidebars_positions'];
		else {
			$list = array();
			$list['left']  = __('Left',  'axiom');
			$list['right'] = __('Right', 'axiom');
			$AXIOM_GLOBALS['list_sidebars_positions'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'axiom_get_sidebar_class' ) ) {
	function axiom_get_sidebar_class($style, $pos) {
		return axiom_sc_param_is_off($style) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($pos);
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'axiom_get_list_body_styles' ) ) {
	function axiom_get_list_body_styles($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_body_styles']))
			$list = $AXIOM_GLOBALS['list_body_styles'];
		else {
			$list = array();
			$list['boxed']		= __('Boxed',		'axiom');
			$list['wide']		= __('Wide',		'axiom');
			$list['fullwide']	= __('Fullwide',	'axiom');
			$list['fullscreen']	= __('Fullscreen',	'axiom');
			$AXIOM_GLOBALS['list_body_styles'] = $list = apply_filters('axiom_filter_list_body_styles', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'axiom_get_list_skins' ) ) {
	function axiom_get_list_skins($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_skins']))
			$list = $AXIOM_GLOBALS['list_skins'];
		else
			$AXIOM_GLOBALS['list_skins'] = $list = axiom_get_list_folders("skins");
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'axiom_get_list_themes' ) ) {
	function axiom_get_list_themes($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_themes']))
			$list = $AXIOM_GLOBALS['list_themes'];
		else
			$AXIOM_GLOBALS['list_themes'] = $list = axiom_get_list_files("css/themes");
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'axiom_get_list_templates' ) ) {
	function axiom_get_list_templates($mode='') {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_templates_'.($mode)]))
			$list = $AXIOM_GLOBALS['list_templates_'.($mode)];
		else {
			$list = array();
			foreach ($AXIOM_GLOBALS['registered_templates'] as $k=>$v) {
				if ($mode=='' || axiom_strpos($v['mode'], $mode)!==false)
					$list[$k] = !empty($v['title']) ? $v['title'] : axiom_strtoproper($v['layout']);
			}
			$AXIOM_GLOBALS['list_templates_'.($mode)] = $list;
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'axiom_get_list_templates_blog' ) ) {
	function axiom_get_list_templates_blog($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_templates_blog']))
			$list = $AXIOM_GLOBALS['list_templates_blog'];
		else {
			$list = axiom_get_list_templates('blog');
			$AXIOM_GLOBALS['list_templates_blog'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'axiom_get_list_templates_blogger' ) ) {
	function axiom_get_list_templates_blogger($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_templates_blogger']))
			$list = $AXIOM_GLOBALS['list_templates_blogger'];
		else {
			$list = axiom_array_merge(axiom_get_list_templates('blogger'), axiom_get_list_templates('blog'));
			$AXIOM_GLOBALS['list_templates_blogger'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'axiom_get_list_templates_single' ) ) {
	function axiom_get_list_templates_single($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_templates_single']))
			$list = $AXIOM_GLOBALS['list_templates_single'];
		else {
			$list = axiom_get_list_templates('single');
			$AXIOM_GLOBALS['list_templates_single'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'axiom_get_list_article_styles' ) ) {
	function axiom_get_list_article_styles($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_article_styles']))
			$list = $AXIOM_GLOBALS['list_article_styles'];
		else {
			$list = array();
			$list["boxed"]   = __('Boxed', 'axiom');
			$list["stretch"] = __('Stretch', 'axiom');
			$AXIOM_GLOBALS['list_article_styles'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return color schemes list, prepended inherit
if ( !function_exists( 'axiom_get_list_color_schemes' ) ) {
	function axiom_get_list_color_schemes($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_color_schemes']))
			$list = $AXIOM_GLOBALS['list_color_schemes'];
		else {
			$list = array();
			if (!empty($AXIOM_GLOBALS['color_schemes'])) {
				foreach ($AXIOM_GLOBALS['color_schemes'] as $k=>$v) {
					$list[$k] = $v['title'];
				}
			}
			$AXIOM_GLOBALS['list_color_schemes'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return button styles list, prepended inherit
if ( !function_exists( 'axiom_get_list_button_styles' ) ) {
	function axiom_get_list_button_styles($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_button_styles']))
			$list = $AXIOM_GLOBALS['list_button_styles'];
		else {
			$list = array();
			$list["custom"]	= __('Custom', 'axiom');
			$list["link"] 	= __('Scheme 1', 'axiom');
			$list["menu"] 	= __('Scheme 2', 'axiom');
			$list["user"] 	= __('Scheme 3', 'axiom');
			$AXIOM_GLOBALS['list_button_styles'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'axiom_get_list_post_formats_filters' ) ) {
	function axiom_get_list_post_formats_filters($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_post_formats_filters']))
			$list = $AXIOM_GLOBALS['list_post_formats_filters'];
		else {
			$list = array();
			$list["no"]      = __('All posts', 'axiom');
			$list["thumbs"]  = __('With thumbs', 'axiom');
			$list["reviews"] = __('With reviews', 'axiom');
			$list["video"]   = __('With videos', 'axiom');
			$list["audio"]   = __('With audios', 'axiom');
			$list["gallery"] = __('With galleries', 'axiom');
			$AXIOM_GLOBALS['list_post_formats_filters'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return scheme color
if (!function_exists('axiom_get_scheme_color')) {
	function axiom_get_scheme_color($clr) {
		global $AXIOM_GLOBALS;
		$scheme = axiom_get_custom_option('color_scheme');
		if (empty($scheme) || empty($AXIOM_GLOBALS['color_schemes'][$scheme])) $scheme = 'original';
		return isset($AXIOM_GLOBALS['color_schemes'][$scheme][$clr]) ? $AXIOM_GLOBALS['color_schemes'][$scheme][$clr] : '';
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'axiom_get_list_portfolio_filters' ) ) {
	function axiom_get_list_portfolio_filters($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_portfolio_filters']))
			$list = $AXIOM_GLOBALS['list_portfolio_filters'];
		else {
			$list = array();
			$list["hide"] = __('Hide', 'axiom');
			$list["tags"] = __('Tags', 'axiom');
			$list["categories"] = __('Categories', 'axiom');
			$AXIOM_GLOBALS['list_portfolio_filters'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'axiom_get_list_hovers' ) ) {
	function axiom_get_list_hovers($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_hovers']))
			$list = $AXIOM_GLOBALS['list_hovers'];
		else {
			$list = array();
			$list['circle effect1']  = __('Circle Effect 1',  'axiom');
			$list['circle effect2']  = __('Circle Effect 2',  'axiom');
			$list['circle effect3']  = __('Circle Effect 3',  'axiom');
			$list['circle effect4']  = __('Circle Effect 4',  'axiom');
			$list['circle effect5']  = __('Circle Effect 5',  'axiom');
			$list['circle effect6']  = __('Circle Effect 6',  'axiom');
			$list['circle effect7']  = __('Circle Effect 7',  'axiom');
			$list['circle effect8']  = __('Circle Effect 8',  'axiom');
			$list['circle effect9']  = __('Circle Effect 9',  'axiom');
			$list['circle effect10'] = __('Circle Effect 10',  'axiom');
			$list['circle effect11'] = __('Circle Effect 11',  'axiom');
			$list['circle effect12'] = __('Circle Effect 12',  'axiom');
			$list['circle effect13'] = __('Circle Effect 13',  'axiom');
			$list['circle effect14'] = __('Circle Effect 14',  'axiom');
			$list['circle effect15'] = __('Circle Effect 15',  'axiom');
			$list['circle effect16'] = __('Circle Effect 16',  'axiom');
			$list['circle effect17'] = __('Circle Effect 17',  'axiom');
			$list['circle effect18'] = __('Circle Effect 18',  'axiom');
			$list['circle effect19'] = __('Circle Effect 19',  'axiom');
			$list['circle effect20'] = __('Circle Effect 20',  'axiom');
			$list['square effect1']  = __('Square Effect 1',  'axiom');
			$list['square effect2']  = __('Square Effect 2',  'axiom');
			$list['square effect3']  = __('Square Effect 3',  'axiom');
	//		$list['square effect4']  = __('Square Effect 4',  'axiom');
			$list['square effect5']  = __('Square Effect 5',  'axiom');
			$list['square effect6']  = __('Square Effect 6',  'axiom');
			$list['square effect7']  = __('Square Effect 7',  'axiom');
			$list['square effect8']  = __('Square Effect 8',  'axiom');
			$list['square effect9']  = __('Square Effect 9',  'axiom');
			$list['square effect10'] = __('Square Effect 10',  'axiom');
			$list['square effect11'] = __('Square Effect 11',  'axiom');
			$list['square effect12'] = __('Square Effect 12',  'axiom');
			$list['square effect13'] = __('Square Effect 13',  'axiom');
			$list['square effect14'] = __('Square Effect 14',  'axiom');
			$list['square effect15'] = __('Square Effect 15',  'axiom');
			$list['square effect_dir']   = __('Square Effect Dir',   'axiom');
			$list['square effect_shift'] = __('Square Effect Shift', 'axiom');
			$list['square effect_book']  = __('Square Effect Book',  'axiom');
			$AXIOM_GLOBALS['list_hovers'] = $list = apply_filters('axiom_filter_portfolio_hovers', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'axiom_get_list_hovers_directions' ) ) {
	function axiom_get_list_hovers_directions($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_hovers_directions']))
			$list = $AXIOM_GLOBALS['list_hovers_directions'];
		else {
			$list = array();
			$list['left_to_right'] = __('Left to Right',  'axiom');
			$list['right_to_left'] = __('Right to Left',  'axiom');
			$list['top_to_bottom'] = __('Top to Bottom',  'axiom');
			$list['bottom_to_top'] = __('Bottom to Top',  'axiom');
			$list['scale_up']      = __('Scale Up',  'axiom');
			$list['scale_down']    = __('Scale Down',  'axiom');
			$list['scale_down_up'] = __('Scale Down-Up',  'axiom');
			$list['from_left_and_right'] = __('From Left and Right',  'axiom');
			$list['from_top_and_bottom'] = __('From Top and Bottom',  'axiom');
			$AXIOM_GLOBALS['list_hovers_directions'] = $list = apply_filters('axiom_filter_portfolio_hovers_directions', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'axiom_get_list_label_positions' ) ) {
	function axiom_get_list_label_positions($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_label_positions']))
			$list = $AXIOM_GLOBALS['list_label_positions'];
		else {
			$list = array();
			$list['top']	= __('Top',		'axiom');
			$list['bottom']	= __('Bottom',		'axiom');
			$list['left']	= __('Left',		'axiom');
			$list['over']	= __('Over',		'axiom');
			$AXIOM_GLOBALS['list_label_positions'] = $list = apply_filters('axiom_filter_label_positions', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return background tints list, prepended inherit
if ( !function_exists( 'axiom_get_list_bg_tints' ) ) {
	function axiom_get_list_bg_tints($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_bg_tints']))
			$list = $AXIOM_GLOBALS['list_bg_tints'];
		else {
			$list = array();
			$list['none']  = __('None',  'axiom');
			$list['light'] = __('Light','axiom');
			$list['dark']  = __('Dark',  'axiom');
			$AXIOM_GLOBALS['list_bg_tints'] = $list = apply_filters('axiom_filter_bg_tints', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return background sizes list, prepended inherit
if ( !function_exists( 'axiom_get_list_bg_sizes' ) ) {
	function axiom_get_list_bg_sizes($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_bg_sizes']))
			$list = $AXIOM_GLOBALS['list_bg_sizes'];
		else {
			$list = array();
			$list['auto']  = __('Auto',  'axiom');
			$list['cover'] = __('Cover','axiom');
			$list['contain']  = __('Contain',  'axiom');
			$AXIOM_GLOBALS['list_bg_sizes'] = $list = apply_filters('axiom_filter_bg_sizes', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return background tints list for sidebars, prepended inherit
if ( !function_exists( 'axiom_get_list_sidebar_styles' ) ) {
	function axiom_get_list_sidebar_styles($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_sidebar_styles']))
			$list = $AXIOM_GLOBALS['list_sidebar_styles'];
		else {
			$list = array();
			$list['none']  = __('None',  'axiom');
			$list['light white'] = __('White','axiom');
			$list['light'] = __('Light','axiom');
			$list['dark']  = __('Dark',  'axiom');
			$AXIOM_GLOBALS['list_sidebar_styles'] = $list = apply_filters('axiom_filter_sidebar_styles', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'axiom_get_list_field_types' ) ) {
	function axiom_get_list_field_types($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_field_types']))
			$list = $AXIOM_GLOBALS['list_field_types'];
		else {
			$list = array();
			$list['text']     = __('Text',  'axiom');
			$list['textarea'] = __('Text Area','axiom');
			$list['password'] = __('Password',  'axiom');
			$list['radio']    = __('Radio',  'axiom');
			$list['checkbox'] = __('Checkbox',  'axiom');
			$list['button']   = __('Button','axiom');
			$AXIOM_GLOBALS['list_field_types'] = $list = apply_filters('axiom_filter_field_types', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'axiom_get_list_googlemap_styles' ) ) {
	function axiom_get_list_googlemap_styles($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_googlemap_styles']))
			$list = $AXIOM_GLOBALS['list_googlemap_styles'];
		else {
			$list = array();
			$list['default'] = __('Default', 'axiom');
			$list['simple'] = __('Simple', 'axiom');
			$list['greyscale'] = __('Greyscale', 'axiom');
			$list['greyscale2'] = __('Greyscale 2', 'axiom');
			$list['invert'] = __('Invert', 'axiom');
			$list['dark'] = __('Dark', 'axiom');
			$list['style1'] = __('Custom style 1', 'axiom');
			$list['style2'] = __('Custom style 2', 'axiom');
			$list['style3'] = __('Custom style 3', 'axiom');
			$list['style4'] = __('Custom style 4', 'axiom');
			$AXIOM_GLOBALS['list_googlemap_styles'] = $list = apply_filters('axiom_filter_googlemap_styles', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'axiom_get_list_icons' ) ) {
	function axiom_get_list_icons($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_icons']))
			$list = $AXIOM_GLOBALS['list_icons'];
		else
			$AXIOM_GLOBALS['list_icons'] = $list = axiom_parse_icons_classes(axiom_get_file_dir("css/fontello/css/fontello-codes.css"));
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'axiom_get_list_socials' ) ) {
	function axiom_get_list_socials($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_socials']))
			$list = $AXIOM_GLOBALS['list_socials'];
		else
			$AXIOM_GLOBALS['list_socials'] = $list = axiom_get_list_files("images/socials", "png");
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'axiom_get_list_flags' ) ) {
	function axiom_get_list_flags($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_flags']))
			$list = $AXIOM_GLOBALS['list_flags'];
		else
			$AXIOM_GLOBALS['list_flags'] = $list = axiom_get_list_files("images/flags", "png");
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'axiom_get_list_yesno' ) ) {
	function axiom_get_list_yesno($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_yesno']))
			$list = $AXIOM_GLOBALS['list_yesno'];
		else {
			$list = array();
			$list["yes"] = __("Yes", 'axiom');
			$list["no"]  = __("No", 'axiom');
			$AXIOM_GLOBALS['list_yesno'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'axiom_get_list_onoff' ) ) {
	function axiom_get_list_onoff($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_onoff']))
			$list = $AXIOM_GLOBALS['list_onoff'];
		else {
			$list = array();
			$list["on"] = __("On", 'axiom');
			$list["off"] = __("Off", 'axiom');
			$AXIOM_GLOBALS['list_onoff'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'axiom_get_list_showhide' ) ) {
	function axiom_get_list_showhide($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_showhide']))
			$list = $AXIOM_GLOBALS['list_showhide'];
		else {
			$list = array();
			$list["show"] = __("Show", 'axiom');
			$list["hide"] = __("Hide", 'axiom');
			$AXIOM_GLOBALS['list_showhide'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'axiom_get_list_orderings' ) ) {
	function axiom_get_list_orderings($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_orderings']))
			$list = $AXIOM_GLOBALS['list_orderings'];
		else {
			$list = array();
			$list["asc"] = __("Ascending", 'axiom');
			$list["desc"] = __("Descending", 'axiom');
			$AXIOM_GLOBALS['list_orderings'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'axiom_get_list_directions' ) ) {
	function axiom_get_list_directions($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_directions']))
			$list = $AXIOM_GLOBALS['list_directions'];
		else {
			$list = array();
			$list["horizontal"] = __("Horizontal", 'axiom');
			$list["vertical"] = __("Vertical", 'axiom');
			$AXIOM_GLOBALS['list_directions'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'axiom_get_list_floats' ) ) {
	function axiom_get_list_floats($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_floats']))
			$list = $AXIOM_GLOBALS['list_floats'];
		else {
			$list = array();
			$list["none"] = __("None", 'axiom');
			$list["left"] = __("Float Left", 'axiom');
			$list["right"] = __("Float Right", 'axiom');
			$AXIOM_GLOBALS['list_floats'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'axiom_get_list_alignments' ) ) {
	function axiom_get_list_alignments($justify=false, $prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_alignments']))
			$list = $AXIOM_GLOBALS['list_alignments'];
		else {
			$list = array();
			$list["none"] = __("None", 'axiom');
			$list["left"] = __("Left", 'axiom');
			$list["center"] = __("Center", 'axiom');
			$list["right"] = __("Right", 'axiom');
			if ($justify) $list["justify"] = __("Justify", 'axiom');
			$AXIOM_GLOBALS['list_alignments'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'axiom_get_list_sortings' ) ) {
	function axiom_get_list_sortings($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_sortings']))
			$list = $AXIOM_GLOBALS['list_sortings'];
		else {
			$list = array();
			$list["date"] = __("Date", 'axiom');
			$list["title"] = __("Alphabetically", 'axiom');
			$list["views"] = __("Popular (views count)", 'axiom');
			$list["comments"] = __("Most commented (comments count)", 'axiom');
			$list["author_rating"] = __("Author rating", 'axiom');
			$list["users_rating"] = __("Visitors (users) rating", 'axiom');
			$list["random"] = __("Random", 'axiom');
			$AXIOM_GLOBALS['list_sortings'] = $list = apply_filters('axiom_filter_list_sortings', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'axiom_get_list_columns' ) ) {
	function axiom_get_list_columns($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_columns']))
			$list = $AXIOM_GLOBALS['list_columns'];
		else {
			$list = array();
			$list["none"] = __("None", 'axiom');
			$list["1_1"] = __("100%", 'axiom');
			$list["1_2"] = __("1/2", 'axiom');
			$list["1_3"] = __("1/3", 'axiom');
			$list["2_3"] = __("2/3", 'axiom');
			$list["1_4"] = __("1/4", 'axiom');
			$list["3_4"] = __("3/4", 'axiom');
			$list["1_5"] = __("1/5", 'axiom');
			$list["2_5"] = __("2/5", 'axiom');
			$list["3_5"] = __("3/5", 'axiom');
			$list["4_5"] = __("4/5", 'axiom');
			$list["1_6"] = __("1/6", 'axiom');
			$list["5_6"] = __("5/6", 'axiom');
			$list["1_7"] = __("1/7", 'axiom');
			$list["2_7"] = __("2/7", 'axiom');
			$list["3_7"] = __("3/7", 'axiom');
			$list["4_7"] = __("4/7", 'axiom');
			$list["5_7"] = __("5/7", 'axiom');
			$list["6_7"] = __("6/7", 'axiom');
			$list["1_8"] = __("1/8", 'axiom');
			$list["3_8"] = __("3/8", 'axiom');
			$list["5_8"] = __("5/8", 'axiom');
			$list["7_8"] = __("7/8", 'axiom');
			$list["1_9"] = __("1/9", 'axiom');
			$list["2_9"] = __("2/9", 'axiom');
			$list["4_9"] = __("4/9", 'axiom');
			$list["5_9"] = __("5/9", 'axiom');
			$list["7_9"] = __("7/9", 'axiom');
			$list["8_9"] = __("8/9", 'axiom');
			$list["1_10"]= __("1/10", 'axiom');
			$list["3_10"]= __("3/10", 'axiom');
			$list["7_10"]= __("7/10", 'axiom');
			$list["9_10"]= __("9/10", 'axiom');
			$list["1_11"]= __("1/11", 'axiom');
			$list["2_11"]= __("2/11", 'axiom');
			$list["3_11"]= __("3/11", 'axiom');
			$list["4_11"]= __("4/11", 'axiom');
			$list["5_11"]= __("5/11", 'axiom');
			$list["6_11"]= __("6/11", 'axiom');
			$list["7_11"]= __("7/11", 'axiom');
			$list["8_11"]= __("8/11", 'axiom');
			$list["9_11"]= __("9/11", 'axiom');
			$list["10_11"]= __("10/11", 'axiom');
			$list["1_12"]= __("1/12", 'axiom');
			$list["5_12"]= __("5/12", 'axiom');
			$list["7_12"]= __("7/12", 'axiom');
			$list["10_12"]= __("10/12", 'axiom');
			$list["11_12"]= __("11/12", 'axiom');
			$AXIOM_GLOBALS['list_columns'] = $list = apply_filters('axiom_filter_list_columns', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'axiom_get_list_dedicated_locations' ) ) {
	function axiom_get_list_dedicated_locations($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_dedicated_locations']))
			$list = $AXIOM_GLOBALS['list_dedicated_locations'];
		else {
			$list = array();
			$list["default"] = __('As in the post defined', 'axiom');
			$list["center"]  = __('Above the text of the post', 'axiom');
			$list["left"]    = __('To the left the text of the post', 'axiom');
			$list["right"]   = __('To the right the text of the post', 'axiom');
			$list["alter"]   = __('Alternates for each post', 'axiom');
			$AXIOM_GLOBALS['list_dedicated_locations'] = $list = apply_filters('axiom_filter_list_dedicated_locations', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'axiom_get_post_format_name' ) ) {
	function axiom_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? __('gallery', 'axiom') : __('galleries', 'axiom');
		else if ($format=='video')	$name = $single ? __('video', 'axiom') : __('videos', 'axiom');
		else if ($format=='audio')	$name = $single ? __('audio', 'axiom') : __('audios', 'axiom');
		else if ($format=='image')	$name = $single ? __('image', 'axiom') : __('images', 'axiom');
		else if ($format=='quote')	$name = $single ? __('quote', 'axiom') : __('quotes', 'axiom');
		else if ($format=='link')	$name = $single ? __('link', 'axiom') : __('links', 'axiom');
		else if ($format=='status')	$name = $single ? __('status', 'axiom') : __('statuses', 'axiom');
		else if ($format=='aside')	$name = $single ? __('aside', 'axiom') : __('asides', 'axiom');
		else if ($format=='chat')	$name = $single ? __('chat', 'axiom') : __('chats', 'axiom');
		else						$name = $single ? __('standard', 'axiom') : __('standards', 'axiom');
		return apply_filters('axiom_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'axiom_get_post_format_icon' ) ) {
	function axiom_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'picture-2';
		else if ($format=='video')	$icon .= 'video-2';
		else if ($format=='audio')	$icon .= 'musical-2';
		else if ($format=='image')	$icon .= 'picture-boxed-2';
		else if ($format=='quote')	$icon .= 'quote-2';
		else if ($format=='link')	$icon .= 'link-2';
		else if ($format=='status')	$icon .= 'agenda-2';
		else if ($format=='aside')	$icon .= 'chat-2';
		else if ($format=='chat')	$icon .= 'chat-all-2';
		else						$icon .= 'book-2';
		return apply_filters('axiom_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'axiom_get_list_fonts_styles' ) ) {
	function axiom_get_list_fonts_styles($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_fonts_styles']))
			$list = $AXIOM_GLOBALS['list_fonts_styles'];
		else {
			$list = array();
			$list['i'] = __('I','axiom');
			$list['u'] = __('U', 'axiom');
			$AXIOM_GLOBALS['list_fonts_styles'] = $list;
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'axiom_get_list_fonts' ) ) {
	function axiom_get_list_fonts($prepend_inherit=false) {
		global $AXIOM_GLOBALS;
		if (isset($AXIOM_GLOBALS['list_fonts']))
			$list = $AXIOM_GLOBALS['list_fonts'];
		else {
			$list = array();
			$list = axiom_array_merge($list, axiom_get_list_fonts_custom());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>axiom_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list['Advent Pro'] = array('family'=>'sans-serif');
			$list['Alegreya Sans'] = array('family'=>'sans-serif');
			$list['Arimo'] = array('family'=>'sans-serif');
			$list['Asap'] = array('family'=>'sans-serif');
			$list['Averia Sans Libre'] = array('family'=>'cursive');
			$list['Averia Serif Libre'] = array('family'=>'cursive');
			$list['Bree Serif'] = array('family'=>'serif',);
			$list['Cabin'] = array('family'=>'sans-serif');
			$list['Cabin Condensed'] = array('family'=>'sans-serif');
			$list['Caudex'] = array('family'=>'serif');
			$list['Comfortaa'] = array('family'=>'cursive');
			$list['Cousine'] = array('family'=>'sans-serif');
			$list['Crimson Text'] = array('family'=>'serif');
			$list['Cuprum'] = array('family'=>'sans-serif');
			$list['Dosis'] = array('family'=>'sans-serif');
			$list['Economica'] = array('family'=>'sans-serif');
			$list['Exo'] = array('family'=>'sans-serif');
			$list['Expletus Sans'] = array('family'=>'cursive');
			$list['Karla'] = array('family'=>'sans-serif');
			$list['Lato'] = array('family'=>'sans-serif');
			$list['Lekton'] = array('family'=>'sans-serif');
			$list['Lobster Two'] = array('family'=>'cursive');
			$list['Lora'] = array('family'=>'serif');
			$list['Maven Pro'] = array('family'=>'sans-serif');
			$list['Merriweather'] = array('family'=>'serif');
			$list['Montserrat'] = array('family'=>'sans-serif');
			$list['Neuton'] = array('family'=>'serif');
			$list['Noticia Text'] = array('family'=>'serif');
			$list['Old Standard TT'] = array('family'=>'serif');
			$list['Open Sans'] = array('family'=>'sans-serif');
			$list['Orbitron'] = array('family'=>'sans-serif');
			$list['Oswald'] = array('family'=>'sans-serif');
			$list['Overlock'] = array('family'=>'cursive');
			$list['Oxygen'] = array('family'=>'sans-serif');
			$list['PT Serif'] = array('family'=>'serif');
			$list['Puritan'] = array('family'=>'sans-serif');
			$list['Raleway'] = array('family'=>'sans-serif');
			$list['Roboto'] = array('family'=>'sans-serif');
			$list['Roboto Slab'] = array('family'=>'sans-serif');
			$list['Roboto Condensed'] = array('family'=>'sans-serif');
			$list['Rosario'] = array('family'=>'sans-serif');
			$list['Share'] = array('family'=>'cursive');
			$list['Signika'] = array('family'=>'sans-serif');
			$list['Signika Negative'] = array('family'=>'sans-serif');
			$list['Source Sans Pro'] = array('family'=>'sans-serif');
			$list['Tinos'] = array('family'=>'serif');
			$list['Ubuntu'] = array('family'=>'sans-serif');
			$list['Vollkorn'] = array('family'=>'serif');
			$AXIOM_GLOBALS['list_fonts'] = $list = apply_filters('axiom_filter_list_fonts', $list);
		}
		return $prepend_inherit ? axiom_array_merge(array('inherit' => __("Inherit", 'axiom')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'axiom_get_list_fonts_custom' ) ) {
	function axiom_get_list_fonts_custom($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = axiom_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? axiom_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? axiom_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.__('uploaded font', 'axiom').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>