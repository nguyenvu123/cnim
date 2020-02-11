<?php
/**
 * AxiomThemes Framework: Theme specific actions
 *
 * @package	axiom
 * @since	axiom 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_core_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_core_theme_setup', 11 );
	function axiom_core_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add user menu
		add_theme_support('nav-menus');
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Editor custom stylesheet - for user
		add_editor_style(axiom_get_file_url('css/editor-style.css'));
		
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'axiom', axiom_get_folder_dir('languages') );


		/* Front and Admin actions and filters:
		------------------------------------------------------------------------ */

		if ( !is_admin() ) {
			
			/* Front actions and filters:
			------------------------------------------------------------------------ */

			// Get theme calendar (instead standard WP calendar) to support Events
			add_filter( 'get_calendar',						'axiom_get_calendar' );
	
			// Filters wp_title to print a neat <title> tag based on what is being viewed
			if (floatval(get_bloginfo('version')) < "4.1") {
				add_filter('wp_title',						'axiom_wp_title', 10, 2);
			}

			// Add main menu classes
			//add_filter('wp_nav_menu_objects', 			'axiom_add_mainmenu_classes', 10, 2);
	
			// Prepare logo text
			add_filter('axiom_filter_prepare_logo_text',	'axiom_prepare_logo_text', 10, 1);
	
			// Add class "widget_number_#' for each widget
			add_filter('dynamic_sidebar_params', 			'axiom_add_widget_number', 10, 1);

			// Frontend editor: Save post data
			add_action('wp_ajax_frontend_editor_save',		'axiom_callback_frontend_editor_save');
			add_action('wp_ajax_nopriv_frontend_editor_save', 'axiom_callback_frontend_editor_save');

			// Frontend editor: Delete post
			add_action('wp_ajax_frontend_editor_delete', 	'axiom_callback_frontend_editor_delete');
			add_action('wp_ajax_nopriv_frontend_editor_delete', 'axiom_callback_frontend_editor_delete');
	
			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 				'axiom_core_frontend_scripts');
			add_action('wp_footer',		 					'axiom_core_frontend_scripts_inline');
			add_action('axiom_action_add_scripts_inline','axiom_core_add_scripts_inline');

			// Prepare theme core global variables
			add_action('axiom_action_prepare_globals',	'axiom_core_prepare_globals');

		}

		// Register theme specific nav menus
		axiom_register_theme_menus();

		// Register theme specific sidebars
		axiom_register_theme_sidebars();
	}
}




/* Theme init
------------------------------------------------------------------------ */

// Init theme template
function axiom_core_init_theme() {
	global $AXIOM_GLOBALS;
	if (!empty($AXIOM_GLOBALS['theme_inited'])) return;
	$AXIOM_GLOBALS['theme_inited'] = true;

	// Load custom options from GET and post/page/cat options
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (axiom_get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop / event
	axiom_load_custom_options();

	// Load skin
	$skin = axiom_esc(axiom_get_custom_option('theme_skin'));
	$AXIOM_GLOBALS['theme_skin'] = $skin;
	if ( file_exists(axiom_get_file_dir('skins/'.($skin).'/skin.php')) ) {
		require_once( axiom_get_file_dir('skins/'.($skin).'/skin.php') );
	}

	// Fire init theme actions (after custom options loaded)
	do_action('axiom_action_init_theme');

	// Prepare theme core global variables
	do_action('axiom_action_prepare_globals');

	// Fire after init theme actions
	do_action('axiom_action_after_init_theme');
}


// Prepare theme global variables
if ( !function_exists( 'axiom_core_prepare_globals' ) ) {
	function axiom_core_prepare_globals() {
		if (!is_admin()) {
			// AJAX Queries settings
			global $AXIOM_GLOBALS;
			$AXIOM_GLOBALS['ajax_nonce'] = wp_create_nonce('ajax_nonce');
			$AXIOM_GLOBALS['ajax_url'] = admin_url('admin-ajax.php');
		
			// Logo text and slogan
			$AXIOM_GLOBALS['logo_text'] = apply_filters('axiom_filter_prepare_logo_text', axiom_get_custom_option('logo_text'));
			$slogan = axiom_get_custom_option('logo_slogan');
			if (!$slogan) $slogan = get_bloginfo ( 'description' );
			$AXIOM_GLOBALS['logo_slogan'] = $slogan;
			
			// Logo image and icons from skin
			$logo_side   = axiom_get_logo_icon('logo_side');
			$logo_fixed  = axiom_get_logo_icon('logo_fixed');
			$logo_footer = axiom_get_logo_icon('logo_footer');
			$AXIOM_GLOBALS['logo_icon']   = axiom_get_logo_icon('logo_icon');
			$AXIOM_GLOBALS['logo_dark']   = axiom_get_logo_icon('logo_dark');
			$AXIOM_GLOBALS['logo_light']  = axiom_get_logo_icon('logo_light');
			$AXIOM_GLOBALS['logo_side']   = $logo_side   ? $logo_side   : $AXIOM_GLOBALS['logo_dark'];
			$AXIOM_GLOBALS['logo_fixed']  = $logo_fixed  ? $logo_fixed  : $AXIOM_GLOBALS['logo_dark'];
			$AXIOM_GLOBALS['logo_footer'] = $logo_footer ? $logo_footer : $AXIOM_GLOBALS['logo_dark'];
	
			$shop_mode = '';
			if (axiom_get_custom_option('show_mode_buttons')=='yes')
				$shop_mode = axiom_get_value_gpc('axiom_shop_mode');
			if (empty($shop_mode))
				$shop_mode = axiom_get_custom_option('shop_mode', '');
			if (empty($shop_mode) || !is_archive())
				$shop_mode = 'thumbs';
			$AXIOM_GLOBALS['shop_mode'] = $shop_mode;
		}
	}
}


// Return url for the uploaded logo image or (if not uploaded) - to image from skin folder
if ( !function_exists( 'axiom_get_logo_icon' ) ) {
	function axiom_get_logo_icon($slug) {
		global $AXIOM_GLOBALS;
		$skin = axiom_esc($AXIOM_GLOBALS['theme_skin']);
		if (($logo_icon = axiom_get_custom_option($slug)) == '' && file_exists(axiom_get_file_dir('skins/' . ($skin) . '/images/' . ($slug) . '.png')))
			$logo_icon = axiom_get_file_url('skins/' . ($skin) . '/images/' . ($slug) . '.png');
		return $logo_icon;
	}
}


// Add menu locations
if ( !function_exists( 'axiom_register_theme_menus' ) ) {
	function axiom_register_theme_menus() {
		register_nav_menus(apply_filters('axiom_filter_add_theme_menus', array(
			'menu_main' => __('Main Menu', 'axiom'),
			'menu_user' => __('User Menu', 'axiom'),
			'menu_copy' => __('Copyright Menu', 'axiom'),
			'menu_side' => __('Side Menu', 'axiom')
		)));
	}
}


// Register widgetized area
if ( !function_exists( 'axiom_register_theme_sidebars' ) ) {
	function axiom_register_theme_sidebars($sidebars=array()) {
		global $AXIOM_GLOBALS;
		if (!is_array($sidebars)) $sidebars = array();
		// Custom sidebars
		$custom = axiom_get_theme_option('custom_sidebars');
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$sidebars['sidebar_custom_'.($i)]  = $sb;
			}
		}
		$sidebars = apply_filters( 'axiom_filter_add_theme_sidebars', $sidebars );
		$AXIOM_GLOBALS['registered_sidebars'] = $sidebars;
		if (count($sidebars) > 0) {
			foreach ($sidebars as $id=>$name) {
				register_sidebar( array(
					'name'          => $name,
					'id'            => $id,
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h5 class="widget_title">',
					'after_title'   => '</h5>',
				) );
			}
		}
	}
}





/* Front actions and filters:
------------------------------------------------------------------------ */

//  Enqueue scripts and styles
if ( !function_exists( 'axiom_core_frontend_scripts' ) ) {
	function axiom_core_frontend_scripts() {
		global $wp_styles, $AXIOM_GLOBALS;
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		
		// Prepare custom fonts
		$fonts = axiom_get_list_fonts(false);
		$theme_fonts = array();
		if (axiom_get_custom_option('typography_custom')=='yes') {
			$selectors = array('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
			foreach ($selectors as $s) {
				$font = axiom_get_custom_option('typography_'.($s).'_font');
				if (!empty($font)) $theme_fonts[$font] = 1;
			}
		}
		// Prepare current skin fonts
		$theme_fonts = apply_filters('axiom_filter_used_fonts', $theme_fonts);
		// Link to selected fonts
		foreach ($theme_fonts as $font=>$v) {
			if (isset($fonts[$font])) {
				$font_name = ($pos=axiom_strpos($font,' ('))!==false ? axiom_substr($font, 0, $pos) : $font;
				$css = !empty($fonts[$font]['css']) 
					? $fonts[$font]['css'] 
					: 'https://fonts.googleapis.com/css?family='
						.(!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name).':100,100italic,300,300italic,400,400italic,700,700italic')
						.(empty($fonts[$font]['link']) || axiom_strpos($fonts[$font]['link'], 'subset=')===false ? '&subset=latin,latin-ext,cyrillic,cyrillic-ext' : '');
				axiom_enqueue_style( 'theme-font-'.str_replace(' ', '-', $font_name), $css, array(), null );
			}
		}
		
		// Fontello styles must be loaded before main stylesheet
		axiom_enqueue_style( 'axiom-fontello-style',  axiom_get_file_url('css/fontello/css/fontello.css'),  array(), null);
		//axiom_enqueue_style( 'axiom-fontello-animation-style', axiom_get_file_url('css/fontello/css/animation.css'), array(), null);

		// Main stylesheet
		axiom_enqueue_style( 'axiom-main-style', get_stylesheet_uri(), array(), null );
		
		if (axiom_get_theme_option('debug_mode')=='no' && axiom_get_theme_option('packed_scripts')=='yes' && file_exists(axiom_get_file_dir('css/__packed.css'))) {
			// Load packed styles
			axiom_enqueue_style( 'axiom-packed-style',  		axiom_get_file_url('css/__packed.css'), array(), null );
		} else {
			// Shortcodes
			axiom_enqueue_style( 'axiom-shortcodes-style',	axiom_get_file_url('shortcodes/shortcodes.css'), array(), null );
			// Animations
			if (axiom_get_theme_option('css_animation')=='yes')
				axiom_enqueue_style( 'axiom-animation-style',	axiom_get_file_url('css/core.animation.css'), array(), null );
		}
		// Theme skin stylesheet
		do_action('axiom_action_add_styles');
		
		// Theme customizer stylesheet and inline styles
		axiom_enqueue_custom_styles();

		// Responsive
		if (axiom_get_theme_option('responsive_layouts') == 'yes') {
			axiom_enqueue_style( 'axiom-responsive-style', axiom_get_file_url('css/responsive.css'), array(), null );
			do_action('axiom_action_add_responsive');
			if (axiom_get_custom_option('theme_skin')!='') {
				$css = apply_filters('axiom_filter_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style( 'axiom-responsive-style', $css );
			}
		}


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		
		if (axiom_get_theme_option('debug_mode')=='no' && axiom_get_theme_option('packed_scripts')=='yes' && file_exists(axiom_get_file_dir('js/__packed.js'))) {
			// Load packed theme scripts
			axiom_enqueue_script( 'axiom-packed-scripts', axiom_get_file_url('js/__packed.js'), array('jquery'), null, true);
		} else {
			// Load separate theme scripts
			axiom_enqueue_script( 'superfish', axiom_get_file_url('js/superfish.min.js'), array('jquery'), null, true );
			if (axiom_get_theme_option('menu_slider')=='yes') {
				axiom_enqueue_script( 'axiom-slidemenu-script', axiom_get_file_url('js/jquery.slidemenu.js'), array('jquery'), null, true );
				//axiom_enqueue_script( 'axiom-jquery-easing-script', axiom_get_file_url('js/jquery.easing.js'), array('jquery'), null, true );
			}
			
			// Load this script only if any shortcode run
			//axiom_enqueue_script( 'axiom-shortcodes-script', axiom_get_file_url('shortcodes/shortcodes.js'), array('jquery'), null, true );

			if ( is_single() && axiom_get_custom_option('show_reviews')=='yes' ) {
				axiom_enqueue_script( 'axiom-core-reviews-script', axiom_get_file_url('js/core.reviews.js'), array('jquery'), null, true );
			}

			axiom_enqueue_script( 'axiom-core-utils-script', axiom_get_file_url('js/core.utils.js'), array('jquery'), null, true );
			axiom_enqueue_script( 'axiom-core-init-script', axiom_get_file_url('js/core.init.js'), array('jquery'), null, true );
		}

		// Media elements library	
		if (axiom_get_theme_option('use_mediaelement')=='yes') {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
			global $wp_scripts;
			$wp_scripts->done[]	= 'mediaelement';
			$wp_scripts->done[]	= 'wp-mediaelement';
			$wp_styles->done[]	= 'mediaelement';
			$wp_styles->done[]	= 'wp-mediaelement';
		}
		
		// Video background
		if (axiom_get_custom_option('show_video_bg') == 'yes' && axiom_get_custom_option('video_bg_youtube_code') != '') {
			axiom_enqueue_script( 'axiom-video-bg-script', axiom_get_file_url('js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}

		// Google map
		if ( axiom_get_custom_option('show_googlemap')=='yes' ) {
			$api_key = axiom_get_theme_option('api_google');
			axiom_enqueue_script( 'googlemap', axiom_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
			axiom_enqueue_script( 'axiom-googlemap-script', axiom_get_file_url('js/core.googlemap.js'), array(), null, true );
		}

		// Social share buttons
		if (is_singular() && !axiom_get_global('blog_streampage') && axiom_get_custom_option('show_share')!='hide') {
			axiom_enqueue_script( 'axiom-social-share-script', axiom_get_file_url('js/social/social-share.js'), array('jquery'), null, true );
		}

		// Comments
		if ( is_singular() && !axiom_get_global('blog_streampage') && comments_open() && get_option( 'thread_comments' ) ) {
			axiom_enqueue_script( 'comment-reply', false, array(), null, true );
		}

		// Custom panel
		if (axiom_get_custom_option('show_theme_customizer') == 'yes') {
			if (file_exists(axiom_get_file_dir('core/core.customizer/front.customizer.css')))
				axiom_enqueue_style(  'axiom-customizer-style',  axiom_get_file_url('core/core.customizer/front.customizer.css'), array(), null );
			if (file_exists(axiom_get_file_dir('core/core.customizer/front.customizer.js')))
				axiom_enqueue_script( 'axiom-customizer-script', axiom_get_file_url('core/core.customizer/front.customizer.js'), array(), null, true );
		}
		
		//Debug utils
		if (axiom_get_theme_option('debug_mode')=='yes') {
			axiom_enqueue_script( 'axiom-core-debug-script', axiom_get_file_url('js/core.debug.js'), array(), null, true );
		}

		// Theme skin script
		do_action('axiom_action_add_scripts');
	}
}

//  Enqueue Swiper Slider scripts and styles
if ( !function_exists( 'axiom_enqueue_slider' ) ) {
	function axiom_enqueue_slider($engine='all') {
		if ($engine=='all' || $engine=='swiper') {
			if (axiom_get_theme_option('debug_mode')=='yes' || axiom_get_theme_option('packed_scripts')=='no' || !file_exists(axiom_get_file_dir('css/__packed.css'))) {
				axiom_enqueue_style( 'axiom-swiperslider-style', axiom_get_file_url('js/swiper/idangerous.swiper.css'), array(), null );
			}
			if (axiom_get_theme_option('debug_mode')=='yes' || axiom_get_theme_option('packed_scripts')=='no' || !file_exists(axiom_get_file_dir('js/__packed.js'))) {
				axiom_enqueue_script( 'axiom-swiperslider-script', 			axiom_get_file_url('js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
				axiom_enqueue_script( 'axiom-swiperslider-scrollbar-script',	axiom_get_file_url('js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );
			}
		}
	}
}

//  Enqueue Messages scripts and styles
if ( !function_exists( 'axiom_enqueue_messages' ) ) {
	function axiom_enqueue_messages() {
		if (axiom_get_theme_option('debug_mode')=='yes' || axiom_get_theme_option('packed_scripts')=='no' || !file_exists(axiom_get_file_dir('css/__packed.css'))) {
			axiom_enqueue_style( 'axiom-messages-style',		axiom_get_file_url('js/core.messages/core.messages.css'), array(), null );
		}
		if (axiom_get_theme_option('debug_mode')=='yes' || axiom_get_theme_option('packed_scripts')=='no' || !file_exists(axiom_get_file_dir('js/__packed.js'))) {
			axiom_enqueue_script( 'axiom-messages-script',	axiom_get_file_url('js/core.messages/core.messages.js'),  array('jquery'), null, true );
		}
	}
}

//  Enqueue Portfolio hover scripts and styles
if ( !function_exists( 'axiom_enqueue_portfolio' ) ) {
	function axiom_enqueue_portfolio($hover='') {
		if (axiom_get_theme_option('debug_mode')=='yes' || axiom_get_theme_option('packed_scripts')=='no' || !file_exists(axiom_get_file_dir('css/__packed.css'))) {
			axiom_enqueue_style( 'axiom-portfolio-style',  axiom_get_file_url('css/core.portfolio.css'), array(), null );
			if (axiom_strpos($hover, 'effect_dir')!==false)
				axiom_enqueue_script( 'hoverdir', axiom_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
		}
	}
}

//  Enqueue Charts and Diagrams scripts and styles
if ( !function_exists( 'axiom_enqueue_diagram' ) ) {
	function axiom_enqueue_diagram($type='all') {
		if (axiom_get_theme_option('debug_mode')=='yes' || axiom_get_theme_option('packed_scripts')=='no' || !file_exists(axiom_get_file_dir('js/__packed.js'))) {
			if ($type=='all' || $type=='pie') axiom_enqueue_script( 'axiom-diagram-chart-script',	axiom_get_file_url('js/diagram/chart.min.js'), array(), null, true );
			if ($type=='all' || $type=='arc') axiom_enqueue_script( 'axiom-diagram-raphael-script',	axiom_get_file_url('js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );
		}
	}
}

/*// Enqueue Select Styler
if (function_exists('is_woocommerce')) {
	axiom_enqueue_script( 'formstyler', axiom_get_file_url('/js/form_styler/jquery.formstyler.js'),  array(), null, true );
}*/

// Enqueue Theme Popup scripts and styles
// Link must have attribute: data-rel="popup" or data-rel="popup[gallery]"
if ( !function_exists( 'axiom_enqueue_popup' ) ) {
	function axiom_enqueue_popup($engine='') {
		if ($engine=='pretty' || (empty($engine) && axiom_get_theme_option('popup_engine')=='pretty')) {
			axiom_enqueue_style(  'axiom-prettyphoto-style',	axiom_get_file_url('js/prettyphoto/css/prettyPhoto.css'), array(), null );
			axiom_enqueue_script( 'axiom-prettyphoto-script',	axiom_get_file_url('js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		} else if ($engine=='magnific' || (empty($engine) && axiom_get_theme_option('popup_engine')=='magnific')) {
			axiom_enqueue_style(  'axiom-magnific-style',	axiom_get_file_url('js/magnific/magnific-popup.css'), array(), null );
			axiom_enqueue_script( 'axiom-magnific-script',axiom_get_file_url('js/magnific/jquery.magnific-popup.min.js'), array('jquery'), '', true );
		} else if ($engine=='internal' || (empty($engine) && axiom_get_theme_option('popup_engine')=='internal')) {
			axiom_enqueue_messages();
		}
	}
}

//  Add inline scripts in the footer hook
if ( !function_exists( 'axiom_core_frontend_scripts_inline' ) ) {
	function axiom_core_frontend_scripts_inline() {
		do_action('axiom_action_add_scripts_inline');
	}
}

//  Add inline scripts in the footer
if (!function_exists('axiom_core_add_scripts_inline')) {
	function axiom_core_add_scripts_inline() {
		global $AXIOM_GLOBALS;

		$msg = axiom_get_system_msg(true);
		if (!empty($msg['message'])) axiom_enqueue_messages();

		echo "<script type=\"text/javascript\">"
			. "jQuery(document).ready(function() {"
			
			// AJAX parameters
			. "AXIOM_GLOBALS['ajax_url']		= '" . esc_url($AXIOM_GLOBALS['ajax_url']) . "';"
			. "AXIOM_GLOBALS['ajax_nonce']		= '" . esc_attr($AXIOM_GLOBALS['ajax_nonce']) . "';"
			. "AXIOM_GLOBALS['ajax_nonce_editor'] = '" . esc_attr(wp_create_nonce('axiom_editor_nonce')) . "';"
			
			// Site base url
			. "AXIOM_GLOBALS['site_url']		= '" . get_site_url() . "';"
			
			// VC frontend edit mode
			. "AXIOM_GLOBALS['vc_edit_mode']	= " . (axiom_vc_is_frontend() ? 'true' : 'false') . ";"
			
			// Theme base font
			. "AXIOM_GLOBALS['theme_font']		= '" . (axiom_get_custom_option('typography_custom')=='yes' ? axiom_get_custom_option('typography_p_font') : '') . "';"
			
			// Theme skin
			. "AXIOM_GLOBALS['theme_skin']		= '" . esc_attr(axiom_get_custom_option('theme_skin')) . "';"
			. "AXIOM_GLOBALS['theme_skin_bg']	= '" . apply_filters('axiom_filter_get_theme_bgcolor', '') . "';"
			
			// Slider height
			. "AXIOM_GLOBALS['slider_height']	= " . max(100, axiom_get_custom_option('slider_height')) . ";"
			
			// System message
			. "AXIOM_GLOBALS['system_message']	= {"
				. "message: '" . addslashes($msg['message']) . "',"
				. "status: '"  . addslashes($msg['status'])  . "',"
				. "header: '"  . addslashes($msg['header'])  . "'"
				. "};"
			
			// User logged in
			. "AXIOM_GLOBALS['user_logged_in']	= " . (is_user_logged_in() ? 'true' : 'false') . ";"
			
			// Show table of content for the current page
			. "AXIOM_GLOBALS['toc_menu']		= '" . esc_attr(axiom_get_custom_option('menu_toc')) . "';"
			. "AXIOM_GLOBALS['toc_menu_home']	= " . (axiom_get_custom_option('menu_toc')!='hide' && axiom_get_custom_option('menu_toc_home')=='yes' ? 'true' : 'false') . ";"
			. "AXIOM_GLOBALS['toc_menu_top']	= " . (axiom_get_custom_option('menu_toc')!='hide' && axiom_get_custom_option('menu_toc_top')=='yes' ? 'true' : 'false') . ";"
			
			// Fix main menu
			. "AXIOM_GLOBALS['menu_fixed']		= " . (axiom_get_custom_option('menu_position')=='fixed' ? 'true' : 'false') . ";"
			
			// Use responsive version for main menu
			. "AXIOM_GLOBALS['menu_relayout']	= " . max(0, (int) axiom_get_theme_option('menu_relayout')) . ";"
			. "AXIOM_GLOBALS['menu_responsive']	= " . (axiom_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) axiom_get_theme_option('menu_responsive')) : 0) . ";"
			. "AXIOM_GLOBALS['menu_slider']     = " . (axiom_get_theme_option('menu_slider')=='yes' ? 'true' : 'false') . ";"

			// Right panel demo timer
			. "AXIOM_GLOBALS['demo_time']		=  500;"
			//. "AXIOM_GLOBALS['demo_time']		= " . (axiom_get_theme_option('show_theme_customizer')=='yes' ? max(0, (int) axiom_get_theme_option('customizer_demo')) : 0) . ";"

			// Video and Audio tag wrapper
			. "AXIOM_GLOBALS['media_elements_enabled'] = " . (axiom_get_theme_option('use_mediaelement')=='yes' ? 'true' : 'false') . ";"
			
			// Use AJAX search
			. "AXIOM_GLOBALS['ajax_search_enabled'] 	= " . (axiom_get_theme_option('use_ajax_search')=='yes' ? 'true' : 'false') . ";"
			. "AXIOM_GLOBALS['ajax_search_min_length']	= " . min(3, axiom_get_theme_option('ajax_search_min_length')) . ";"
			. "AXIOM_GLOBALS['ajax_search_delay']		= " . min(200, max(1000, axiom_get_theme_option('ajax_search_delay'))) . ";"

			// Use CSS animation
			. "AXIOM_GLOBALS['css_animation']      = " . (axiom_get_theme_option('css_animation')=='yes' ? 'true' : 'false') . ";"
			. "AXIOM_GLOBALS['menu_animation_in']  = '" . esc_attr(axiom_get_theme_option('menu_animation_in')) . "';"
			. "AXIOM_GLOBALS['menu_animation_out'] = '" . esc_attr(axiom_get_theme_option('menu_animation_out')) . "';"

			// Popup windows engine
			. "AXIOM_GLOBALS['popup_engine']	= '" . esc_attr(axiom_get_theme_option('popup_engine')) . "';"
			. "AXIOM_GLOBALS['popup_gallery']	= " . (axiom_get_theme_option('popup_gallery')=='yes' ? 'true' : 'false') . ";"

			// E-mail mask
			. "AXIOM_GLOBALS['email_mask']		= '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';"
			
			// Messages max length
			. "AXIOM_GLOBALS['contacts_maxlength']	= " . intval(axiom_get_theme_option('message_maxlength_contacts')) . ";"
			. "AXIOM_GLOBALS['comments_maxlength']	= " . intval(axiom_get_theme_option('message_maxlength_comments')) . ";"

			// Remember visitors settings
			. "AXIOM_GLOBALS['remember_visitors_settings']	= " . (axiom_get_theme_option('remember_visitors_settings')=='yes' ? 'true' : 'false') . ";"

			// Internal vars - do not change it!
			// Flag for review mechanism
			. "AXIOM_GLOBALS['admin_mode']			= false;"
			// Max scale factor for the portfolio and other isotope elements before relayout
			. "AXIOM_GLOBALS['isotope_resize_delta']	= 0.3;"
			// jQuery object for the message box in the form
			. "AXIOM_GLOBALS['error_message_box']	= null;"
			// Waiting for the viewmore results
			. "AXIOM_GLOBALS['viewmore_busy']		= false;"
			. "AXIOM_GLOBALS['video_resize_inited']	= false;"
			. "AXIOM_GLOBALS['top_panel_height']		= 0;"
			. "});"
			. "</script>";
	}
}


//  Enqueue Custom styles (main Theme options settings)
if ( !function_exists( 'axiom_enqueue_custom_styles' ) ) {
	function axiom_enqueue_custom_styles() {
		// Custom stylesheet
		$custom_css = '';	//axiom_get_custom_option('custom_stylesheet_url');
		axiom_enqueue_style( 'axiom-custom-style', $custom_css ? $custom_css : axiom_get_file_url('css/custom-style.css'), array(), null );
		// Custom inline styles
		wp_add_inline_style( 'axiom-custom-style', axiom_prepare_custom_styles() );
	}
}

// Add class "widget_number_#' for each widget
if ( !function_exists( 'axiom_add_widget_number' ) ) {
	function axiom_add_widget_number($prm) {
		global $AXIOM_GLOBALS;
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_id='', $last_sidebar_columns=0, $last_sidebar_count=0, $sidebars_widgets=array();
		$cur_sidebar = $AXIOM_GLOBALS['current_sidebar'];
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $cur_sidebar) {
			$num = 0;
			$last_sidebar = $cur_sidebar;
			$last_sidebar_id = $prm[0]['id'];
			$last_sidebar_columns = max(1, (int) axiom_get_custom_option('sidebar_'.($cur_sidebar).'_columns'));
			$last_sidebar_count = count($sidebars_widgets[$last_sidebar_id]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget_number_'.esc_attr($num).($last_sidebar_columns > 1 ? ' column-1_'.esc_attr($last_sidebar_columns) : '').' ', $prm[0]['before_widget']);
		return $prm;
	}
}


// Filters wp_title to print a neat <title> tag based on what is being viewed.
// add_filter( 'wp_title', 'axiom_wp_title', 10, 2 );
if ( !function_exists( 'axiom_wp_title' ) ) {
	function axiom_wp_title( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		if ( is_home() || is_front_page() ) {
			$site_description = axiom_get_custom_option('logo_slogan');
			if (empty($site_description)) 
				$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description )
				$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( __( 'Page %s', 'axiom' ), max( $paged, $page ) );
		return $title;
	}
}

// Add main menu classes
// add_filter('wp_nav_menu_objects', 'axiom_add_mainmenu_classes', 10, 2);
if ( !function_exists( 'axiom_add_mainmenu_classes' ) ) {
	function axiom_add_mainmenu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && axiom_get_theme_option('menu_colored')=='yes') {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_tint = axiom_taxonomy_get_inherited_property('category', $item->object_id, 'bg_tint');
						if (!empty($cur_tint) && !axiom_is_inherit_option($cur_tint))
							$items[$k]->classes[] = 'bg_tint_'.esc_attr($cur_tint);
					}
				}
			}
		}
		return $items;
	}
}


// Save post data from frontend editor
if ( !function_exists( 'axiom_callback_frontend_editor_save' ) ) {
	function axiom_callback_frontend_editor_save() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'axiom_editor_nonce' ) )
			die();

		$response = array('error'=>'');

		parse_str($_REQUEST['data'], $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( axiom_get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0) 
					$response['error'] = __('Post update error!', 'axiom');
			} else {
				$response['error'] = __('Post update error!', 'axiom');
			}
		} else
			$response['error'] = __('Post update denied!', 'axiom');
		
		echo json_encode($response);
		die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'axiom_callback_frontend_editor_delete' ) ) {
	function axiom_callback_frontend_editor_delete() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'axiom_editor_nonce' ) )
			die();

		$response = array('error'=>'');
		
		$post_id = $_REQUEST['post_id'];

		if ( axiom_get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = __('Post delete error!', 'axiom');
			} else {
				$response['error'] = __('Post delete error!', 'axiom');
			}
		} else
			$response['error'] = __('Post delete denied!', 'axiom');

		echo json_encode($response);
		die();
	}
}


// Prepare logo text
if ( !function_exists( 'axiom_prepare_logo_text' ) ) {
	function axiom_prepare_logo_text($text) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}
?>
