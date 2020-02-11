<?php
/* Tribe Events (TE) support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('axiom_tribe_events_theme_setup')) {
	add_action( 'axiom_action_before_init_theme', 'axiom_tribe_events_theme_setup' );
	function axiom_tribe_events_theme_setup() {
		if (axiom_exists_tribe_events()) {

			//if (axiom_is_tribe_events_page()) {
				// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
				add_filter('axiom_filter_get_blog_type',				'axiom_tribe_events_get_blog_type', 9, 2);
				add_filter('axiom_filter_get_blog_title',			'axiom_tribe_events_get_blog_title', 9, 2);
				add_filter('axiom_filter_get_current_taxonomy',		'axiom_tribe_events_get_current_taxonomy', 9, 2);
				add_filter('axiom_filter_is_taxonomy',				'axiom_tribe_events_is_taxonomy', 9, 2);
				add_filter('axiom_filter_get_stream_page_title',		'axiom_tribe_events_get_stream_page_title', 9, 2);
				add_filter('axiom_filter_get_stream_page_link',		'axiom_tribe_events_get_stream_page_link', 9, 2);
				add_filter('axiom_filter_get_stream_page_id',		'axiom_tribe_events_get_stream_page_id', 9, 2);
				add_filter('axiom_filter_get_period_links',			'axiom_tribe_events_get_period_links', 9, 3);
				add_filter('axiom_filter_detect_inheritance_key',	'axiom_tribe_events_detect_inheritance_key', 9, 1);
			//}

			// Add Google API key to the map's link
			add_filter('tribe_events_google_maps_api',     'axiom_tribe_events_google_maps_api');

			add_action( 'axiom_action_add_styles',				'axiom_tribe_events_frontend_scripts' );

			add_filter('axiom_filter_list_post_types', 			'axiom_tribe_events_list_post_types', 10, 1);

			// Advanced Calendar filters
			add_filter('axiom_filter_calendar_get_month_link',		'axiom_tribe_events_calendar_get_month_link', 9, 2);
			add_filter('axiom_filter_calendar_get_prev_month',		'axiom_tribe_events_calendar_get_prev_month', 9, 2);
			add_filter('axiom_filter_calendar_get_next_month',		'axiom_tribe_events_calendar_get_next_month', 9, 2);
			add_filter('axiom_filter_calendar_get_curr_month_posts',	'axiom_tribe_events_calendar_get_curr_month_posts', 9, 2);

			// Extra column for events lists
			if (axiom_get_theme_option('show_overriden_posts')=='yes') {
				add_filter('manage_edit-'.Tribe__Events__Main::POSTTYPE.'_columns',			'axiom_post_add_options_column', 9);
				add_filter('manage_'.Tribe__Events__Main::POSTTYPE.'_posts_custom_column',	'axiom_post_fill_options_column', 9, 2);
			}
		}
	}
}
if ( !function_exists( 'axiom_tribe_events_settings_theme_setup2' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_tribe_events_settings_theme_setup2', 3 );
	function axiom_tribe_events_settings_theme_setup2() {
		if (axiom_exists_tribe_events()) {
			axiom_add_theme_inheritance( array('tribe_events' => array(
				'stream_template' => 'tribe-events/default-template',
				'single_template' => '',
				'taxonomy' => array(Tribe__Events__Main::TAXONOMY),
				'taxonomy_tags' => array(),
				'post_type' => array(
					Tribe__Events__Main::POSTTYPE,
					Tribe__Events__Main::VENUE_POST_TYPE,
					Tribe__Events__Main::ORGANIZER_POST_TYPE
				),
				'override' => 'post'
				) )
			);
		}
	}
}

// Check if Tribe Events installed and activated
if (!function_exists('axiom_exists_tribe_events')) {
	function axiom_exists_tribe_events() {
		return class_exists( 'Tribe__Events__Main' );
	}
}


// Return true, if current page is any TE page
if ( !function_exists( 'axiom_is_tribe_events_page' ) ) {
	function axiom_is_tribe_events_page() {
		return class_exists('Tribe__Events__Main')
			? tribe_is_event() || tribe_is_event_query() || tribe_is_event_category() || tribe_is_event_venue() || tribe_is_event_organizer()
			: false;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'axiom_tribe_events_detect_inheritance_key' ) ) {
	//add_filter('axiom_filter_detect_inheritance_key',	'axiom_tribe_events_detect_inheritance_key', 9, 1);
	function axiom_tribe_events_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return axiom_is_tribe_events_page() ? 'tribe_events' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'axiom_tribe_events_get_blog_type' ) ) {
	//add_filter('axiom_filter_get_blog_type',	'axiom_tribe_events_get_blog_type', 10, 2);
	function axiom_tribe_events_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if (axiom_is_tribe_events_page()) {
			//$tribe_ecp = Tribe__Events__Main::instance();
			if (/*tribe_is_day()*/ isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='day') 			$page = 'tribe_day';
			else if (/*tribe_is_month()*/ isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='month')	$page = 'tribe_month';
			else if (is_single())																									$page = 'tribe_event';
			else if (/*tribe_is_event_venue()*/		isset($query->tribe_is_event_venue) && $query->tribe_is_event_venue)			$page = 'tribe_venue';
			else if (/*tribe_is_event_organizer()*/	isset($query->tribe_is_event_organizer) && $query->tribe_is_event_organizer)	$page = 'tribe_organizer';
			else if (/* tribe_is_event_category()*/	isset($query->tribe_is_event_category) && $query->tribe_is_event_category)		$page = 'tribe_category';
			else if (/*is_tax($tribe_ecp->get_event_taxonomy())*/ is_tag())															$page = 'tribe_tag';
			else if (isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='upcoming')					$page = 'tribe_list';
			else																													$page = 'tribe';
		}
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'axiom_tribe_events_get_blog_title' ) ) {
	//add_filter('axiom_filter_get_blog_title',	'axiom_tribe_events_get_blog_title', 10, 2);
	function axiom_tribe_events_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( axiom_strpos($page, 'tribe')!==false ) {
			//return tribe_get_events_title();
			if ( $page == 'tribe_category' ) {
				$cat = get_term_by( 'slug', get_query_var( 'tribe_events_cat' ), 'tribe_events_cat', ARRAY_A);
				$title = $cat['name'];
			} else if ( $page == 'tribe_tag' ) {
				$title = sprintf( __( 'Tag: %s', 'axiom' ), single_tag_title( '', false ) );
			} else if ( $page == 'tribe_venue' ) {
				$title = sprintf( __( 'Venue: %s', 'axiom' ), tribe_get_venue());
			} else if ( $page == 'tribe_organizer' ) {
				$title = sprintf( __( 'Organizer: %s', 'axiom' ), tribe_get_organizer());
			} else if ( $page == 'tribe_day' ) {
				$title = sprintf( __( 'Daily Events: %s', 'axiom' ), date_i18n(tribe_get_date_format(true), strtotime(get_query_var( 'start_date' ))) );
			} else if ( $page == 'tribe_month' ) {
				$title = sprintf( __( 'Monthly Events: %s', 'axiom' ), date_i18n(tribe_get_option('monthAndYearFormat', 'F Y' ), strtotime(tribe_get_month_view_date())));
			} else if ( $page == 'tribe_event' ) {
				$title = axiom_get_post_title();
			} else {
				$title = __( 'Tribe Events', 'axiom' );
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'axiom_tribe_events_get_stream_page_title' ) ) {
	//add_filter('axiom_filter_get_stream_page_title',	'axiom_tribe_events_get_stream_page_title', 9, 2);
	function axiom_tribe_events_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (axiom_strpos($page, 'tribe')!==false) {
			if (($page_id = axiom_tribe_events_get_stream_page_id(0, $page)) > 0)
				$title = axiom_get_post_title($page_id);
			else
				$title = __( 'All Events', 'axiom');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'axiom_tribe_events_get_stream_page_id' ) ) {
	//add_filter('axiom_filter_get_stream_page_id',	'axiom_tribe_events_get_stream_page_id', 9, 2);
	function axiom_tribe_events_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (axiom_strpos($page, 'tribe')!==false) $id = axiom_get_template_page_id('tribe-events/default-template');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'axiom_tribe_events_get_stream_page_link' ) ) {
	//add_filter('axiom_filter_get_stream_page_link',	'axiom_tribe_events_get_stream_page_link', 9, 2);
	function axiom_tribe_events_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (axiom_strpos($page, 'tribe')!==false) $url = tribe_get_events_link();
		return $url;
	}
}

// Filter to return breadcrumbs links to the parent period
if ( !function_exists( 'axiom_tribe_events_get_period_links' ) ) {
	//add_filter('axiom_filter_get_period_links',	'axiom_tribe_events_get_period_links', 9, 3);
	function axiom_tribe_events_get_period_links($links, $page, $delimiter='') {
		if (!empty($links)) return $links;
		global $post;
		if ($page == 'tribe_day' && is_object($post))
			$links = '<a class="breadcrumbs_item cat_parent" href="' . tribe_get_gridview_link(false) . '">' . date_i18n(tribe_get_option('monthAndYearFormat', 'F Y' ), strtotime(tribe_get_month_view_date())) . '</a>';
		return $links;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'axiom_tribe_events_get_current_taxonomy' ) ) {
	//add_filter('axiom_filter_get_current_taxonomy',	'axiom_tribe_events_get_current_taxonomy', 9, 2);
	function axiom_tribe_events_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( axiom_strpos($page, 'tribe')!==false ) {
			$tax = Tribe__Events__Main::TAXONOMY;
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'axiom_tribe_events_is_taxonomy' ) ) {
	//add_filter('axiom_filter_is_taxonomy',	'axiom_tribe_events_is_taxonomy', 10, 2);
	function axiom_tribe_events_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else
			return $query && isset($query->tribe_is_event_category) && $query->tribe_is_event_category || is_tax(Tribe__Events__Main::TAXONOMY) ? 'courses_group' : '';
	}
}

// Add custom post type into list
if ( !function_exists( 'axiom_tribe_events_list_post_types' ) ) {
	//add_filter('axiom_filter_list_post_types', 	'axiom_tribe_events_list_post_types', 10, 1);
	function axiom_tribe_events_list_post_types($list) {
		$list['tribe_events'] = __('Events', 'axiom');
		return $list;
	}
}



// Return previous month and year with published posts
if ( !function_exists( 'axiom_tribe_events_calendar_get_month_link' ) ) {
	//add_filter('axiom_filter_calendar_get_month_link',	'axiom_tribe_events_calendar_get_month_link', 9, 2);
	function axiom_tribe_events_calendar_get_month_link($link, $opt) {
		if (!empty($opt['posts_types']) && in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types']) && count($opt['posts_types'])==1) {
			$events = Tribe__Events__Main::instance();
			$link = $events->getLink('month', ($opt['year']).'-'.($opt['month']), null);			
		}
		return $link;
	}
}

// Return previous month and year with published posts
if ( !function_exists( 'axiom_tribe_events_calendar_get_prev_month' ) ) {
	//add_filter('axiom_filter_calendar_get_prev_month',	'axiom_tribe_events_calendar_get_prev_month', 9, 2);
	function axiom_tribe_events_calendar_get_prev_month($prev, $opt) {
		if (!empty($opt['posts_types']) && !in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types'])) return;
		if (!empty($prev['done']) && in_array(Tribe__Events__Main::POSTTYPE, $prev['done'])) return;
		$args = array(
			'post_type' => Tribe__Events__Main::POSTTYPE,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => 1,
			'orderby' => 'meta_value',
			'meta_key' => '_EventStartDate',
			'order' => 'desc',
			'meta_query' => array(
				array(
					'key' => '_EventStartDate',
					'value' => ($opt['year']).'-'.($opt['month']).'-01',
					'compare' => '<',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		$month = $year = 0;
		if ($q->have_posts()) {
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), '_EventStartDate', true));
				$year  = date('Y', $dt);
				$month = date('m', $dt);
			}
			wp_reset_postdata();
		}
		if (empty($prev) || ($year+$month > 0 && ($prev['year']+$prev['month']==0 || ($prev['year']).($prev['month']) < ($year).($month)))) {
			$prev['year'] = $year;
			$prev['month'] = $month;
		}
		if (empty($prev['done'])) $prev['done'] = array();
		$prev['done'][] = Tribe__Events__Main::POSTTYPE;
		return $prev;
	}
}

// Return next month and year with published posts
if ( !function_exists( 'axiom_tribe_events_calendar_get_next_month' ) ) {
	//add_filter('axiom_filter_calendar_get_next_month',	'axiom_tribe_events_calendar_get_next_month', 9, 2);
	function axiom_tribe_events_calendar_get_next_month($next, $opt) {
		if (!empty($opt['posts_types']) && !in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types'])) return;
		if (!empty($next['done']) && in_array(Tribe__Events__Main::POSTTYPE, $next['done'])) return;
		$args = array(
			'post_type' => Tribe__Events__Main::POSTTYPE,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => 1,
			'orderby' => 'meta_value',
			'meta_key' => '_EventStartDate',
			'order' => 'asc',
			'meta_query' => array(
				array(
					'key' => '_EventStartDate',
					'value' => ($opt['year']).'-'.($opt['month']).'-'.($opt['last_day']).' 23:59:59',
					'compare' => '>',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		$month = $year = 0;
		if ($q->have_posts()) {
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), '_EventStartDate', true));
				$year  = date('Y', $dt);
				$month = date('m', $dt);
			}
			wp_reset_postdata();
		}
		if (empty($next) || ($year+$month > 0 && ($next['year']+$next['month'] ==0 || ($next['year']).($next['month']) > ($year).($month)))) {
			$next['year'] = $year;
			$next['month'] = $month;
		}
		if (empty($next['done'])) $next['done'] = array();
		$next['done'][] = Tribe__Events__Main::POSTTYPE;
		return $next;
	}
}

// Return current month published posts
if ( !function_exists( 'axiom_tribe_events_calendar_get_curr_month_posts' ) ) {
	//add_filter('axiom_filter_calendar_get_curr_month_posts',	'axiom_tribe_events_calendar_get_curr_month_posts', 9, 2);
	function axiom_tribe_events_calendar_get_curr_month_posts($posts, $opt) {
		if (!empty($opt['posts_types']) && !in_array(Tribe__Events__Main::POSTTYPE, $opt['posts_types'])) return;
		if (!empty($posts['done']) && in_array(Tribe__Events__Main::POSTTYPE, $posts['done'])) return;
		$args = array(
			'post_type' => Tribe__Events__Main::POSTTYPE,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => -1,
			'orderby' => 'meta_value',
			'meta_key' => '_EventStartDate',
			'order' => 'asc',
			'meta_query' => array(
				array(
					'key' => '_EventStartDate',
					'value' => array(($opt['year']).'-'.($opt['month']).'-01', ($opt['year']).'-'.($opt['month']).'-'.($opt['last_day']).' 23:59:59'),
					'compare' => 'BETWEEN',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		if ($q->have_posts()) {
			if (empty($posts)) $posts = array();
			$events = Tribe__Events__Main::instance();
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), '_EventStartDate', true));
				$day = (int) date('d', $dt);
				$title = apply_filters('the_title', get_the_title());
				if (empty($posts[$day])) 
					$posts[$day] = array();
				if (empty($posts[$day]['link']) && count($opt['posts_types'])==1)
					$posts[$day]['link'] = $events->getLink('day', ($opt['year']).'-'.($opt['month']).'-'.($day), null);
				if (empty($posts[$day]['titles']))
					$posts[$day]['titles'] = $title;
				else
					$posts[$day]['titles'] = is_int($posts[$day]['titles']) ? $posts[$day]['titles']+1 : 2;
				if (empty($posts[$day]['posts'])) $posts[$day]['posts'] = array();
				$posts[$day]['posts'][] = array(
					'post_id' => get_the_ID(),
					'post_type' => get_post_type(),
					'post_date' => date(get_option('date_format'), $dt),
					'post_title' => $title,
					'post_link' => get_permalink()
				);
			}
			wp_reset_postdata();
		}
		if (empty($posts['done'])) $posts['done'] = array();
		$posts['done'][] = Tribe__Events__Main::POSTTYPE;
		return $posts;
	}
}



// Enqueue Tribe Events custom styles
if ( !function_exists( 'axiom_tribe_events_frontend_scripts' ) ) {
	//add_action( 'axiom_action_add_styles', 'axiom_tribe_events_frontend_scripts' );
	function axiom_tribe_events_frontend_scripts() {
		global $wp_styles;
		$wp_styles->done[] = 'tribe-events-custom-jquery-styles';
		axiom_enqueue_style( 'tribe-style',  axiom_get_file_url('css/tribe-style.css'), array(), null );
	}
}

// Add Google API key to the map's link
if ( !function_exists( 'axiom_tribe_events_google_maps_api' ) ) {
	//add_filter('tribe_events_google_maps_api', 'axiom_tribe_events_google_maps_api');
	function axiom_tribe_events_google_maps_api($url) {
		$api_key = axiom_get_theme_option('api_google');
		if ($api_key) {
			$url = axiom_add_to_url($url, array(
				'key' => $api_key
			));
		}
		return $url;
	}
}


// Before main content
if ( !function_exists( 'axiom_tribe_events_wrapper_start' ) ) {
	//add_filter('tribe_events_before_html', 'axiom_tribe_events_wrapper_start');
	function axiom_tribe_events_wrapper_start($html) {
		return '
		<section class="post tribe_events_wrapper">
			<article class="post_content">
		' . ($html);
	}
}

// After main content
if ( !function_exists( 'axiom_tribe_events_wrapper_end' ) ) {
	//add_filter('tribe_events_after_html', 'axiom_tribe_events_wrapper_end');
	function axiom_tribe_events_wrapper_end($html) {
		return $html . '
			</article><!-- .post_content -->
		</section>
		';
	}
}
?>