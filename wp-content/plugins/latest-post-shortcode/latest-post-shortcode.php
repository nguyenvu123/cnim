<?php
/**
 * Plugin Name: Latest Post Shortcode
 * Plugin URI: https://iuliacazan.ro/latest-post-shortcode/
 * Description: This plugin allows you to create a dynamic content selection from your posts, pages and custom post types that can be embedded with a UI configurable shortcode. When used with WordPress >= 5.0 + Gutenberg, the plugin shortcode can be configured from any Classic block, using the plugin button.
 * Text Domain: lps
 * Domain Path: /langs
 * Version: 9.3
 * Author: Iulia Cazan
 * Author URI: https://profiles.wordpress.org/iulia-cazan
 * Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ
 * License: GPL2
 *
 * @package LPS
 *
 * Copyright (C) 2015-2019 Iulia Cazan
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Define the plugin version.
define( 'LPS_PLUGIN_VERSION', 9.3 );

/**
 * Class for Latest Post Shortcode.
 */
class Latest_Post_Shortcode {

	const PLUGIN_NAME        = 'Latest Post Shortcode';
	const PLUGIN_SUPPORT_URL = 'https://wordpress.org/support/plugin/latest-post-shortcode/';
	const PLUGIN_TRANSIENT   = 'lps-plugin-notice';

	/**
	 * Class instance.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Check that configurator wrapper was set or not.
	 *
	 * @var array
	 */
	public static $wrapper_was_set = false;

	/**
	 * Tile pattern.
	 *
	 * @var array
	 */
	public static $tile_pattern = array();

	/**
	 * Taxonomy positions.
	 *
	 * @var array
	 */
	public static $tax_positions = array();

	/**
	 * Pugin tags replaceable.
	 *
	 * @var array
	 */
	public static $replaceable_tags = array( 'date', 'title', 'text', 'image', 'read_more_text', 'author', 'category', 'tags', 'show_mime', 'caption' );

	/**
	 * Pugin order by options.
	 *
	 * @var array
	 */
	public static $orderby_options = array();

	/**
	 * Tile pattern links.
	 *
	 * @var array
	 */
	public static $tile_pattern_links;

	/**
	 * Tile pattern with no links.
	 *
	 * @var array
	 */
	public static $tile_pattern_nolinks;

	/**
	 * Title tags.
	 *
	 * @var array
	 */
	public static $title_tags = array();

	/**
	 * Date limit units.
	 *
	 * @var array
	 */
	public static $date_limit_units = array();

	/**
	 * Current query statuses list.
	 *
	 * @var array
	 */
	public static $current_query_statuses_list = array();

	/**
	 * Get active object instance
	 *
	 * @access public
	 * @static
	 * @return object
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new Latest_Post_Shortcode();
		}
		return self::$instance;
	}

	/**
	 * Class constructor. Includes constants and init methods.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Run action and filter hooks.
	 *
	 * @access private
	 * @return void
	 */
	private function init() {
		$class = get_called_class();

		// Allow to hook into tile patterns.
		add_action( 'init', array( $class, 'tile_pattern_setup' ), 1 );

		// Text domain load.
		add_action( 'plugins_loaded', array( $class, 'load_textdomain' ) );

		// Apply the tiles shortcodes.
		add_shortcode( 'latest-selected-content', array( $class, 'latest_selected_content' ) );

		if ( is_admin() ) {
			add_action( 'admin_footer', array( $class, 'add_shortcode_popup_container' ) );
			add_action( 'admin_head', array( $class, 'load_admin_assets' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $class, 'plugin_action_links' ) );
		} else {
			add_action( 'wp_head', array( $class, 'load_assets' ) );
		}

		add_action( 'wp_ajax_nopriv_lps_navigate_to_page', array( $class, 'lps_navigate_callback' ) );
		add_action( 'wp_ajax_lps_navigate_to_page', array( $class, 'lps_navigate_callback' ) );

		add_action( 'admin_notices', array( $class, 'plugin_admin_notices' ) );
		add_action( 'wp_ajax_lps-plugin-deactivate-notice', array( $class, 'plugin_admin_notices_cleanup' ) );
		add_action( 'plugins_loaded', array( $class, 'plugin_ver_check' ) );
	}

	/**
	 * Define the tile patterns.
	 *
	 * @return void
	 */
	public static function tile_pattern_setup() {
		if ( class_exists( 'Latest_Post_Shortcode_Slider' ) ) {
			// Deactivate the extension, it is no longer supported.
			if ( function_exists( 'deactivate_plugins' ) ) {
				deactivate_plugins( '/latest-post-shortcode-slider-extension/latest-post-shortcode-slider.php' );
			}

			// Mention to the user that the extension is not supported anymore.
			add_action( 'admin_notices', function() {
				$class   = 'notice notice-error';
				$message = __( 'The Latest Post Shortcode Slider Extension is no longer supported, and it has been deactivated. If you need to display posts as a slider you can find the settings integrated in the Latest Post Shortcode plugin', 'lps' );

				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
			}, 99 );
		}

		self::$tile_pattern = array(
			// No link.
			0  => '[image][title][text][read_more_text]',
			1  => '[title][image][text][read_more_text]',
			2  => '[title][text][image][read_more_text]',
			18 => '[title][text][read_more_text][image]',

			// Full link.
			3  => '[a][image][title][text][read_more_text][/a]',
			11 => '[a][title][image][text][read_more_text][/a]',
			14 => '[a][title][text][image][read_more_text][/a]',
			19 => '[a][title][text][read_more_text][image][/a]',

			// Partial link.
			5  => '[image][title][text][a-r][read_more_text][/a]',
			13 => '[title][image][text][a-r][read_more_text][/a]',
			17 => '[title][text][image][a-r][read_more_text][/a]',
			22 => '[title][text][a-r][read_more_text][/a][image]',
			25 => '[image][a][title][/a][text][read_more_text]',
			26 => '[image][a][title][/a][text][a-r][read_more_text][/a]',
			27 => '[a][image][title][/a][text][read_more_text]',
			28 => '[a][image][title][/a][text][a-r][read_more_text][/a]',
		);

		// Allow to hook into tile patterns.
		self::$tile_pattern         = apply_filters( 'lps_filter_tile_patterns', self::$tile_pattern );
		self::$tile_pattern_links   = array();
		self::$tile_pattern_nolinks = array();
		self::$title_tags           = array( 'h3', 'h2', 'h1', 'h4', 'h5', 'h6', 'b', 'strong', 'em', 'p', 'div', 'span' );
		self::$date_limit_units     = array(
			'months' => esc_html__( 'months', 'lps' ),
			'weeks'  => esc_html__( 'weeks', 'lps' ),
			'days'   => esc_html__( 'days', 'lps' ),
			'hours'  => esc_html__( 'hours', 'lps' ),
		);

		foreach ( self::$tile_pattern as $k => $v ) {
			if ( substr_count( $v, '[a]' ) !== 0 || substr_count( $v, '[a-r]' ) !== 0 ) {
				array_push( self::$tile_pattern_links, $k );
			} else {
				array_push( self::$tile_pattern_nolinks, $k );
			}
		}

		self::$orderby_options = array(
			'dateD'  => array(
				'title'   => esc_html__( 'Date Descending', 'lps' ),
				'order'   => 'DESC',
				'orderby' => 'date',
			),
			'dateA'  => array(
				'title'   => esc_html__( 'Date Ascending', 'lps' ),
				'order'   => 'ASC',
				'orderby' => 'date',
			),
			'menuD'  => array(
				'title'   => esc_html__( 'Menu Order Descending', 'lps' ),
				'order'   => 'DESC',
				'orderby' => 'menu_order',
			),
			'menuA'  => array(
				'title'   => esc_html__( 'Menu Order Ascending', 'lps' ),
				'order'   => 'ASC',
				'orderby' => 'menu_order',
			),
			'titleD' => array(
				'title'   => esc_html__( 'Title Descending', 'lps' ),
				'order'   => 'DESC',
				'orderby' => 'title',
			),
			'titleA' => array(
				'title'   => esc_html__( 'Title Ascending', 'lps' ),
				'order'   => 'ASC',
				'orderby' => 'title',
			),
			'random' => array(
				'title'   => esc_html__( 'Random *', 'lps' ),
				'order'   => 'DESC',
				'orderby' => 'rand',
			),
		);

		self::$tax_positions = array(
			'before-title'          => esc_html__( 'before title', 'lps' ),
			'after-title'           => esc_html__( 'after title', 'lps' ),
			'before-image'          => esc_html__( 'before image', 'lps' ),
			'after-image'           => esc_html__( 'after image', 'lps' ),
			'before-text'           => esc_html__( 'before text', 'lps' ),
			'after-text'            => esc_html__( 'after text', 'lps' ),
			'before-read_more_text' => esc_html__( 'before read more text', 'lps' ),
			'after-read_more_text'  => esc_html__( 'after read more text', 'lps' ),
			'before-date'           => esc_html__( 'before date', 'lps' ),
			'after-date'            => esc_html__( 'after date', 'lps' ),
		);

		$filtered_tax = self::filtered_taxonomies();
		if ( ! empty( $filtered_tax ) ) {
			foreach ( $filtered_tax as $key => $value ) {
				array_push( self::$replaceable_tags, $key );
			}
		}
		self::$replaceable_tags = array_unique( self::$replaceable_tags );
	}

	/**
	 * Load text domain for internalization.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'lps', false, basename( dirname( __FILE__ ) ) . '/langs' );
	}

	/**
	 * Load the plugin assets.
	 *
	 * @return void
	 */
	public static function load_assets() {
		wp_enqueue_style(
			'lps-style',
			plugins_url( '/assets/css/style.min.css', __FILE__ ),
			array(),
			LPS_PLUGIN_VERSION,
			false
		);

		wp_register_script(
			'lps-ajax-pagination-js',
			plugins_url( '/assets/js/custom-pagination.js', __FILE__ ),
			array( 'jquery' ),
			LPS_PLUGIN_VERSION,
			true
		);
		wp_localize_script(
			'lps-ajax-pagination-js',
			'LPS',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);
		wp_enqueue_script( 'lps-ajax-pagination-js' );
	}

	/**
	 * Load the admin assets.
	 *
	 * @return void
	 */
	public static function load_admin_assets() {
		wp_enqueue_style(
			'lps-admin-style',
			plugins_url( '/assets/css/admin-style.css', __FILE__ ),
			array(),
			LPS_PLUGIN_VERSION,
			false
		);

		// Register the custom script first.
		wp_register_script(
			'lps-admin-shortcode-button',
			plugins_url( '/assets/js/custom.js', __FILE__ ),
			array( 'jquery' ),
			LPS_PLUGIN_VERSION,
			false
		);

		// Localize the custom module script with our data.
		wp_localize_script(
			'lps-admin-shortcode-button',
			'lpsGenVars',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'icon'    => plugins_url( '/assets/js/lps-button-icon.png', __FILE__ ),
				'title'   => esc_html__( 'Latest Post Shortcode', 'lps' ),
			)
		);

		// The script can be enqueued now.
		wp_enqueue_script( 'lps-admin-shortcode-button' );
	}

	/**
	 * Load the slider assets from local files instead of CDN, to make it faster, and available offline.
	 *
	 * @return void
	 */
	public static function load_slider_assets() {
		wp_enqueue_style(
			'lps-slick-style',
			plugins_url( '/assets/css/slick-custom-theme.min.css', __FILE__ ),
			array(),
			LPS_PLUGIN_VERSION,
			false
		);

		// Slick style from //cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css.
		wp_enqueue_style(
			'lps-slick',
			plugins_url( '/assets/slick-1.8.1/slick.min.css', __FILE__ ),
			array(),
			LPS_PLUGIN_VERSION,
			false
		);

		// Slick js from //cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js.
		wp_enqueue_script(
			'lps-slick',
			plugins_url( '/assets/slick-1.8.1/slick.min.js', __FILE__ ),
			array( 'jquery' ),
			LPS_PLUGIN_VERSION,
			false
		);
	}

	/**
	 * Return all private and all public statuses defined.
	 *
	 * @return array
	 */
	public static function get_statuses() {
		global $wp_post_statuses;
		$arr = array(
			'public'  => array(),
			'private' => array(),
		);
		if ( ! empty( $wp_post_statuses ) ) {
			foreach ( $wp_post_statuses as $t => $v ) {
				if ( $v->public ) {
					$arr['public'][] = $t;
				} else {
					if ( ! in_array( $t, array( 'auto-draft', 'request-confirmed', 'request-pending', 'request-failed', 'request-completed', 'trash' ), true ) ) {
						$arr['private'][] = $t;
					}
				}
			}
		}
		return $arr;
	}

	/**
	 * The custom patterns start with _custom_.
	 *
	 * @param  string $tile_pattern A tile pattern.
	 * @return boolean
	 */
	public static function tile_markup_is_custom( $tile_pattern ) {
		$use_custom_markup = false;
		if ( '_custom_' === substr( $tile_pattern, 1, 8 ) ) {
			$use_custom_markup = true;
		}
		return $use_custom_markup;
	}

	/**
	 * The list of filtered taxonomies.
	 *
	 * @return array
	 */
	public static function filtered_taxonomies() {
		$list        = array();
		$exclude_tax = array( 'post_tag', 'nav_menu', 'link_category', 'post_format' );
		$tax         = get_taxonomies( array(), 'objects' );
		if ( ! empty( $tax ) ) {
			foreach ( $tax as $k => $v ) {
				if ( ! in_array( $k, $exclude_tax, true ) ) {
					$list[ $k ] = $v;
				}
			}
		}
		return $list;
	}

	/**
	 * Add some content to the bottom of the page.
	 * This will be shown in the inline modal.
	 */
	public static function add_shortcode_popup_container() {
		if ( true === self::$wrapper_was_set ) {
			// Fail-fast, this was used.
			return;
		}

		self::$wrapper_was_set = true;
		$display_posts_list = array(
			'title'                     => esc_html__( 'Title', 'lps' ),
			'title,excerpt'             => esc_html__( 'Title + Post Excerpt', 'lps' ),
			'title,content'             => esc_html__( 'Title + Post Content', 'lps' ),
			'title,excerpt-small'       => esc_html__( 'Title + Few Chars From The Excerpt', 'lps' ),
			'title,content-small'       => esc_html__( 'Title + Few Chars From The Content', 'lps' ),
			'date'                      => esc_html__( 'Date', 'lps' ),
			'title,date'                => esc_html__( 'Title + Date', 'lps' ),
			'title,date,excerpt'        => esc_html__( 'Title + Date + Post Excerpt', 'lps' ),
			'title,date,content'        => esc_html__( 'Title + Date + Post Content', 'lps' ),
			'title,date,excerpt-small'  => esc_html__( 'Title + Date + Few Chars From The Excerpt', 'lps' ),
			'title,date,content-small'  => esc_html__( 'Title + Date + Few Chars From The Content', 'lps' ),
			'date,title'                => esc_html__( 'Date + Title', 'lps' ),
			'date,title,excerpt'        => esc_html__( 'Date + Title + Post Excerpt', 'lps' ),
			'date,title,content'        => esc_html__( 'Date + Title + Post Content', 'lps' ),
			'date,title,excerpt-small'  => esc_html__( 'Date + Title + Few Chars From The Excerpt', 'lps' ),
			'date,title,content-small'  => esc_html__( 'Date + Title + Few Chars From The Content', 'lps' ),
			'date,title,excerptcontent' => esc_html__( 'Date + Title + Post Excerpt + Post Content', 'lps' ),
			'date,title,contentexcerpt' => esc_html__( 'Date + Title + Post Content + Post Excerpt', 'lps' ),
		);
		// Maybe apply custom extra type.
		$display_posts_list = apply_filters( 'lps_filter_display_posts_list', $display_posts_list );
		?>

		<div id="lps_shortcode_popup_container_bg" style="display:none;"></div>
		<div id="lps_shortcode_popup_container" style="display:none; width:100%; height:100%">

			<table width="100%" cellpadding="0" cellspacing="0" class="lps_shortcode_popup_container_table">
				<tr>
					<td class="shortcode-preview">
						<button type="button" id="wp-link-close" onclick="lpsClose();"><span class="screen-reader-text">Close</span></button>

						<h1 class="lps_shortcode_popup_container_title"><img src="<?php echo esc_url( plugins_url( '/assets/js/lps-button-icon-white.png', __FILE__ ) ); ?>"> <?php esc_html_e( 'Latest Post Shortcode', 'lps' ); ?></h1>
						<div class="clear"></div>
						<hr>

						<a class="button button-primary float-right" onclick="lpsEmbed();"><?php esc_html_e( 'Embed Shortcode', 'lps' ); ?></a>
						<h3><?php esc_html_e( 'Preview', 'lps' ); ?></h3>
						<div id="lps-preview">
							<div id="lps_preview_embed_shortcode">[latest-selected-content type="post" limit="1" tag="news"]</div>
						</div>
						<div class="clear"></div>

						<h3><?php esc_html_e( 'Settings', 'lps' ); ?></h3>
						<ul class="lps-ui-menu">
							<li id="menu-tabs-0" class="selected"><a onclick="lpsMenu('#tabs-0');"><?php esc_html_e( 'Output Type', 'lps' ); ?></a></li>
							<li id="menu-tabs-1"><a onclick="lpsMenu('#tabs-1');"><?php esc_html_e( 'Content & Filters', 'lps' ); ?></a></li>
							<li id="menu-tabs-2"><a onclick="lpsMenu('#tabs-2');"><?php esc_html_e( 'Limit & Pagination', 'lps' ); ?></a></li>
							<li id="menu-tabs-3"><a onclick="lpsMenu('#tabs-3');"><?php esc_html_e( 'Display Settings', 'lps' ); ?></a></li>
							<li id="menu-tabs-4"><a onclick="lpsMenu('#tabs-4');"><?php esc_html_e( 'Extra Options', 'lps' ); ?></a></li>
						</ul>
						<input type="hidden" name="lps_shortcode_popup_container_current_menu" id="lps_shortcode_popup_container_current_menu" value="#menu-tabs-0">
					</td>
					<td class="shortcode-settings">
						<div class="inner">
							<div id="tabs-0" class="settings-group">
								<h1>1. <?php esc_html_e( 'Output Type', 'lps' ); ?></h1>

								<div class="settings-block">
									<table width="100%" cellspacing="0" cellpadding="2">
										<tr>
											<td>
												<?php esc_html_e( 'Display as', 'lps' ); ?>
											</td>
											<td>
												<img src="<?php echo esc_url( plugins_url( '/assets/images/type-grid.jpg', __FILE__ ) ); ?>" onclick="selectTypeImg('');" width="48%">

												<img src="<?php echo esc_url( plugins_url( '/assets/images/type-slider.jpg', __FILE__ ) ); ?>" onclick="selectTypeImg('slider')" width="48%">

												<select name="lps_output" id="lps_output" onchange="lpsRefresh()">
													<option value=""><?php esc_html_e( 'post grid/list/tiles', 'lps' ); ?></option>
													<option value="slider"><?php esc_html_e( 'slider', 'lps' ); ?></option>
												</select>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<br>

							<div id="tabs-1" class="settings-group">
								<h1>2. <?php esc_html_e( 'Content & Filters', 'lps' ); ?></h1>
								<div class="settings-block"><h3>
									<?php esc_html_e( 'Post Types, Status & Order', 'lps' ); ?></h3><hr>
									<table width="100%" cellspacing="0" cellpadding="2">
										<tr>
											<td><?php esc_html_e( 'Post Type', 'lps' ); ?></td>
											<td>
												<select name="lps_post_type" id="lps_post_type" onchange="lpsRefresh()">
													<option value=""><?php esc_html_e( 'Any', 'lps' ); ?></option>
													<?php $post_types = get_post_types( array(), 'objects' ); ?>
													<?php if ( ! empty( $post_types ) ) : ?>
														<?php foreach ( $post_types as $k => $v ) : ?>
															<?php if ( ! in_array( $k, array( 'revision', 'nav_menu_item', 'oembed_cache', 'custom_css', 'customize_changeset', 'user_request', 'wp_block' ), true ) ) : ?>
																<option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $k ); ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'Post Status', 'lps' ); ?></td>
											<td>
												<?php $st = self::get_statuses(); ?>
												<?php foreach ( $st['public'] as $pu ) : ?>
													<label><input type="checkbox" name="lps_status[]" id="lps_status_<?php echo esc_attr( $pu ); ?>" value="<?php echo esc_attr( $pu ); ?>" onclick="lpsRefresh()" class="lps_status"><b><?php echo esc_html( $pu ); ?></b></label>
												<?php endforeach; ?>
												<?php foreach ( $st['private'] as $pr ) : ?>
													<label><input type="checkbox" name="lps_status[]" id="lps_status_<?php echo esc_attr( $pr ); ?>" value="<?php echo esc_attr( $pr ); ?>" onclick="lpsRefresh()" class="lps_status"><em><?php echo esc_html( $pr ); ?></em></label>
												<?php endforeach; ?>
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'Sticky Posts', 'lps' ); ?></td>
											<td>
												<label class="wide"><input type="radio" name="lps_show_extra_sticky[]" id="lps_show_extra_nosticky_restriction" value="" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'no restriction', 'lps' ); ?></label>

												<label class="wide"><input type="radio" name="lps_show_extra_sticky[]" id="lps_show_extra_sticky" value="sticky" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'only sticky posts', 'lps' ); ?></label>

												<label class="wide"><input type="radio" name="lps_show_extra_sticky[]" id="lps_show_extra_nosticky" value="nosticky" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'no sticky posts', 'lps' ); ?></label>
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'Order by', 'lps' ); ?></td>
											<td>
												<select name="lps_orderby" id="lps_orderby" onchange="lpsRefresh()">
													<?php foreach ( self::$orderby_options as $k => $v ) : ?>
														<option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $v['title'] ); ?></option>
													<?php endforeach; ?>
												</select>
												<p class="comment"><?php esc_html_e( '* Please note that ordering the items by random might present performance risks, please use this careful.', 'lps' ); ?><span class="block-use available-for-tiles"> <?php esc_html_e( 'Also, using a random order and pagination will output unexpected and potentially redundant content.', 'lps' ); ?></span></p>
											</td>
										</tr>
									</table>
								</div>

								<div class="settings-block">
									<h3><?php esc_html_e( 'Filter By Taxonomy', 'lps' ); ?></h3><hr>
									<table width="100%" cellspacing="0" cellpadding="2">
										<tr>
											<td><?php esc_html_e( 'Taxonomy', 'lps' ); ?></td>
											<td>
												<select name="lps_taxonomy" id="lps_taxonomy" onchange="lpsRefresh()">
													<option value=""><?php esc_html_e( 'Any', 'lps' ); ?></option>
													<?php $tax = self::filtered_taxonomies(); ?>
													<?php if ( ! empty( $tax ) ) : ?>
														<?php foreach ( $tax as $k => $v ) : ?>
															<option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $v->labels->name ); ?> (<?php echo esc_attr( $k ); ?>)</option>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'Term', 'lps' ); ?></td>
											<td>
												<input type="text" name="lps_term" id="lps_term"  placeholder="<?php esc_attr_e( 'Term slug (ex: news)', 'lps' ); ?>" onchange="lpsRefresh()">
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'Taxonomy', 'lps' ); ?> 2</td>
											<td>
												<select name="lps_taxonomy2" id="lps_taxonomy2" onchange="lpsRefresh()">
													<option value=""><?php esc_html_e( 'Any', 'lps' ); ?></option>
													<?php if ( ! empty( $tax ) ) : ?>
														<?php foreach ( $tax as $k => $v ) : ?>
															<option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $v->labels->name ); ?> (<?php echo esc_attr( $k ); ?>)</option>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'Term', 'lps' ); ?> 2</td>
											<td>
												<input type="text" name="lps_term2" id="lps_term2"  placeholder="<?php esc_attr_e( 'Term slug (ex: news)', 'lps' ); ?>" onchange="lpsRefresh()">
											</td>
										</tr>
									</table>
								</div>

								<div class="settings-block">
									<h3><?php esc_html_e( 'Filter By Tag', 'lps' ); ?></h3><hr>
									<table width="100%" cellspacing="0" cellpadding="2">
										<tr>
											<td><?php esc_html_e( 'Tag', 'lps' ); ?></b></td>
											<td><input type="text" name="lps_tag" id="lps_tag" onchange="lpsRefresh()"></td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'Dynamic', 'lps' ); ?></td>
											<td>
												<select name="lps_dtag" id="lps_dtag" onchange="lpsRefresh()">
													<option value=""><?php esc_html_e( 'No, use the selected ones', 'lps' ); ?></option>
													<option value="yes"><?php esc_html_e( 'Yes, use the current post tags', 'lps' ); ?></option>
												</select>
											</td>
										</tr>
									</table>
								</div>

								<div class="settings-block">
									<h3><?php esc_html_e( 'Filter By Specific IDs', 'lps' ); ?></h3><hr>
									<table width="100%" cellspacing="0" cellpadding="2">
										<tr>
											<td><?php esc_html_e( 'Post ID', 'lps' ); ?></td>
											<td>
												<input type="text" name="lps_post_id" id="lps_post_id" onchange="lpsRefresh()" placeholder="<?php esc_attr_e( 'Separate IDs with comma', 'lps' ); ?>"><p class="comment"><?php esc_attr_e( 'Show only objects with the selected IDs.', 'lps' ); ?></p></td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'Parent ID', 'lps' ); ?></td>
											<td>
												<input type="text" name="lps_parent_id" id="lps_parent_id" onchange="lpsRefresh()" placeholder="<?php esc_attr_e( 'Separate IDs with comma', 'lps' ); ?>"><p class="comment"><?php esc_attr_e( 'Show only objects with the selected parents.', 'lps' ); ?></p>
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'Author ID', 'lps' ); ?></td>
											<td>
												<input type="text" name="lps_author_id" id="lps_author_id" onchange="lpsRefresh()" placeholder="<?php esc_attr_e( 'Separate IDs with comma', 'lps' ); ?>"><p class="comment"><?php esc_attr_e( 'Show only objects with the selected author IDs.', 'lps' ); ?></p>
											</td>
										</tr>
									</table>
								</div>

								<div class="settings-block">
									<h3><?php esc_html_e( 'Exclude Content', 'lps' ); ?></h3><hr>
									<table width="100%" cellspacing="0" cellpadding="2">
										<tr>
											<td><?php esc_html_e( 'Current', 'lps' ); ?></td>
											<td>
												<label class="wide"><input type="checkbox" name="lps_show_extra_current_id" id="lps_show_extra_current_id" value="current_id" checked="checked" disabled="disabled" readonly="readonly"> <?php esc_html_e( 'the current post', 'lps' ); ?></label>
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'Dynamic', 'lps' ); ?></td>
											<td>
												<label class="wide"><input type="checkbox" name="lps_show_extra[]" id="lps_show_extra_exclude_previous_content" value="exclude_previous_content" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'previous shortcodes*', 'lps' ); ?></label>
												<p class="comment"><?php esc_html_e( '* The exclude content dynamic option will filter the content so that the posts that were already embedded by previous shortcodes on this page will not show up (so that the content does not repeat).', 'lps' ); ?></p>
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'By Post ID', 'lps' ); ?></td>
											<td>
												<input type="text" name="lps_excludepost_id" id="lps_excludepost_id" onchange="lpsRefresh()" placeholder="<?php esc_attr_e( 'Separate IDs with comma', 'lps' ); ?>">
												<p class="comment"><?php esc_attr_e( 'Exclude the objects with the selected IDs.', 'lps' ); ?></p>
											</td>
										</tr>
										<tr>
											<td><?php esc_html_e( 'By Author ID', 'lps' ); ?></td>
											<td>
												<input type="text" name="lps_excludeauthor_id" id="lps_excludeauthor_id" onchange="lpsRefresh()" placeholder="<?php esc_attr_e( 'Separate IDs with comma', 'lps' ); ?>">
												<p class="comment"><?php esc_attr_e( 'Exclude the objects with the selected author IDs.', 'lps' ); ?></p>
											</td>
										</tr>

										<tr>
											<td><?php esc_html_e( 'By Tags', 'lps' ); ?></td>
											<td>
												<input type="text" name="lps_exclude_tags" id="lps_exclude_tags" onchange="lpsRefresh()" placeholder="<?php esc_attr_e( 'Separate slugs with comma', 'lps' ); ?>">
												<p class="comment"><?php esc_attr_e( 'Exclude the objects with the selected tags.', 'lps' ); ?></p>
											</td>
										</tr>

										<tr>
											<td><?php esc_html_e( 'By Categories', 'lps' ); ?></td>
											<td>
												<input type="text" name="lps_exclude_categories" id="lps_exclude_categories" onchange="lpsRefresh()" placeholder="<?php esc_attr_e( 'Separate slugs with comma', 'lps' ); ?>">
												<p class="comment"><?php esc_attr_e( 'Exclude the objects with the selected categories.', 'lps' ); ?></p>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<br>
							<div id="tabs-2" class="settings-group">
								<h1>3. <?php esc_html_e( 'Limit & Pagination', 'lps' ); ?></h1>

								<div class="settings-block">
									<h3><?php esc_html_e( 'Posts Limit', 'lps' ); ?></h3><hr>
									<table width="100%" cellspacing="0" cellpadding="2">
										<tr>
											<td><?php esc_html_e( 'Number of Posts', 'lps' ); ?></td>
											<td>
												<input type="text" name="lps_limit" id="lps_limit" value="1" onchange="lpsRefresh()" size="5">
												<p class="comment"><?php esc_html_e( 'This is the maximum number of posts the shortcode will expose. However, if you are using pagination, the number of posts does not apply, it will be overwritten by the number of records per page.', 'lps' ); ?></p>
											</td>
										</tr>
									</table>

									<h3><?php esc_html_e( 'Date Limit', 'lps' ); ?></h3><hr>
									<table width="100%" cellspacing="0" cellpadding="2">
										<tr>
											<td><?php esc_html_e( 'Date Limit Type', 'lps' ); ?></td>
											<td>
												<select name="lps_date_limit" id="lps_date_limit" onchange="lpsRefresh()">
													<option value=""><?php esc_html_e( 'Date Range', 'lps' ); ?></option>
													<option value="1"><?php esc_html_e( 'Dynamic Date', 'lps' ); ?></option>
												</select>
											</td>
										</tr>
									</table>
									<div id="lps_date_limit_options_0">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td> </td>
												<td><?php esc_html_e( 'Published After', 'lps' ); ?></td>
												<td>
													<input type="date" name="lps_date_after" id="lps_date_after" value="" onchange="lpsRefresh()">
												</td>
											</tr>
											<tr>
												<td> </td>
												<td><?php esc_html_e( 'Published Before', 'lps' ); ?></td>
												<td>
													<input type="date" name="lps_date_before" id="lps_date_before" value="" onchange="lpsRefresh()">
												</td>
											</tr>
										</table>
									</div>
									<div id="lps_date_limit_options_1">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td> </td>
												<td><?php esc_html_e( 'Since', 'lps' ); ?></td>
												<td class="small-input">
													<input type="number" name="lps_date_start" id="lps_date_start" value="0" onchange="lpsRefresh()" size="2">
												</td>
												<td>
													<select name="lps_date_start_type" id="lps_date_start_type" onchange="lpsRefresh()">
														<?php foreach ( self::$date_limit_units as $k => $v ) : ?>
															<option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $v ); ?> </option>
														<?php endforeach; ?>
													</select>
												</td>
												<td><?php esc_html_e( 'ago', 'lps' ); ?></td>
											</tr>
										</table>
									</div>
								</div>

								<div class="settings-block">
									<h3><?php esc_html_e( 'Pagination Settings', 'lps' ); ?></h3><hr>
									<div class="block-use available-for-tiles">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td><?php esc_html_e( 'Use Pagination', 'lps' ); ?></td>
												<td>
													<select name="lps_use_pagination" id="lps_use_pagination" onchange="lpsRefresh()">
														<option value=""><?php esc_html_e( 'No', 'lps' ); ?></option>
														<option value="yes"><?php esc_html_e( 'Yes, paginate results', 'lps' ); ?></option>
													</select>
												</td>
											</tr>
										</table>

										<div id="lps_pagination_options">
											<table width="100%" cellspacing="0" cellpadding="2">
												<tr>
													<td><?php esc_html_e( 'Records Per Page', 'lps' ); ?></td>
													<td>
														<input type="text" name="lps_per_page" id="lps_per_page" value="0" onchange="lpsRefresh()" size="5">
													</td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Offset', 'lps' ); ?></td>
													<td>
														<input type="text" name="lps_offset" id="lps_offset" value="0" onchange="lpsRefresh()" size="5">
													</td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Visibility', 'lps' ); ?></td>
													<td>
														<select name="lps_showpages" id="lps_showpages" onchange="lpsRefresh()">
															<option value=""><?php esc_html_e( 'Hide Navigation', 'lps' ); ?></option>
															<option value="4"><?php esc_html_e( 'Show Navigation (range of 4)', 'lps' ); ?></option>
															<option value="5"><?php esc_html_e( 'Show Navigation (range of 5)', 'lps' ); ?></option>
															<option value="10"><?php esc_html_e( 'Show Navigation (range of 10)', 'lps' ); ?></option>
															<option value="more"><?php esc_html_e( 'Show Navigation (load more button)', 'lps' ); ?></option>
														</select>
													</td>
												</tr>
												<tr id="lps_showpages_options">
													<td><?php esc_html_e( 'Load More Text', 'lps' ); ?></td>
													<td>
														<input type="text" name="lps_loadtext" id="lps_loadtext" onchange="lpsRefresh()" placeholder="<?php esc_html_e( 'Custom \'Load more\' button text', 'lps' ); ?>" value="<?php esc_html_e( 'Load more', 'lps' ); ?>" size="32">
														<p class="comment"><?php esc_html_e( 'This is the text that will be displayed on the button on the front-end. Do not use brackets for the custom load more message, these are shortcodes delimiters.', 'lps' ); ?></p>

													</td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Position', 'lps' ); ?></td>
													<td>
														<select name="lps_showpages_pos" id="lps_showpages_pos" onchange="lpsRefresh()">
															<option value=""><?php esc_html_e( 'Above the results', 'lps' ); ?></option>
															<option value="1"><?php esc_html_e( 'Below the results', 'lps' ); ?></option>
															<option value="2"><?php esc_html_e( 'Above & below the result', 'lps' ); ?></option>
														</select>
													</td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'AJAX Pagination', 'lps' ); ?></td>
													<td>
														<label><input type="checkbox" name="lps_show_extra[]" id="lps_show_extra_ajax_pagination" value="ajax_pagination" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'yes', 'lps' ); ?></label>
														<label><input type="radio" name="lps_show_extra[]" id="lps_show_extra_no_spinner" value="" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'no spinner', 'lps' ); ?></label>
														<br>
														<label><input type="radio" name="lps_show_extra[]" id="lps_show_extra_light_spinner" value="light_spinner" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'light spinner', 'lps' ); ?></label>
														<label><input type="radio" name="lps_show_extra[]" id="lps_show_extra_dark_spinner" value="dark_spinner" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'dark spinner', 'lps' ); ?></label>

													</td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Pagination Style', 'lps' ); ?></td>
													<td>
														<label class="wide"><input type="checkbox" name="lps_show_extra[]" id="lps_show_extra_pagination_all" value="pagination_all" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'all pagination elements', 'lps' ); ?> </label>
														<p class="comment"><?php esc_html_e( 'Tick this option if you need to display the pagination elements all the time, including the disabled elements like: go to first, previous, next, and last page, even if these are disabled.', 'lps' ); ?></p>
													</td>
												</tr>
											</table>
										</div>
									</div>
									<div class="block-use available-for-slider">
										<p class="comment"><?php esc_html_e( 'The pagination is not available for sliders, only for list/tiles output.', 'lps' ); ?></p>
									</div>
								</div>
							</div>
							<br>
							<div id="tabs-3" class="settings-group">
								<h1>4. <?php esc_html_e( 'Display Settings', 'lps' ); ?></h1>

								<?php
								// Introduce the slider extension options.
								self::output_slider_configuration();
								?>

								<div class="settings-block">
									<h3><?php esc_html_e( 'Post Appearance', 'lps' ); ?></h3><hr>
									<div class="block-use available-for-tiles">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td><?php esc_html_e( 'Display Post', 'lps' ); ?></td>
												<td>
													<select name="lps_display" id="lps_display" onchange="lpsRefresh()">
														<?php foreach ( $display_posts_list as $k => $v ) : ?>
															<?php
															$key = array_keys( self::$tile_pattern, '[' . $k . ']', true );
															if ( ! empty( $key ) ) {
																$key = reset( $key );
															} else {
																$key = '';
															}
															?>
															<option value="<?php echo esc_attr( $k ); ?>" data-template-id="<?php echo esc_attr( $key ); ?>" <?php selected( 'title', $k ); ?>><?php echo esc_html( $v ); ?> </option>
														<?php endforeach; ?>
													</select>
												</td>
											</tr>
										</table>
									</div>
									<div id="lps_display_titletag">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td><?php esc_html_e( 'Title Wrap', 'lps' ); ?></td>
												<td>
													<select name="lps_titletag" id="lps_titletag" onchange="lpsRefresh()">
														<?php foreach ( self::$title_tags as $s ) : ?>
															<option value="<?php echo esc_attr( $s ); ?>"><?php echo esc_html( $s ); ?></option>
														<?php endforeach; ?>
													</select>
													<p class="comment"><?php esc_html_e( 'This is the HTML tag used to wrap the post title in the output (defaults to h3).', 'lps' ); ?></p>
												</td>
											</tr>
										</table>
									</div>
									<div id="lps_display_limit">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td><?php esc_html_e( 'Chars Limit', 'lps' ); ?></td>
												<td>
													<input type="text" name="lps_chrlimit" id="lps_chrlimit" onchange="lpsRefresh()" placeholder="Ex: 120" value="120" size="5">
													<p class="comment"><?php esc_html_e( 'Maximum number of chars from excerpt / content to be displayed (the text will be truncated, but will not break words).', 'lps' ); ?></p>
												</td>
											</tr>
										</table>
									</div>
									<div id="lps_display_raw">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td><?php esc_html_e( 'Raw Content', 'lps' ); ?></td>
												<td>
													<label><input type="checkbox" name="lps_show_extra_raw[]" id="lps_show_extra_raw" value="raw" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'show raw content', 'lps' ); ?></label>
													<p class="comment"><?php esc_html_e( 'This option is forcing the content output without stripping the markup. This might produce unexpected content layout on the front end, use wisely.', 'lps' ); ?></p>
												</td>
											</tr>
										</table>
									</div>
									<div id="lps_display_date_diff">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td><?php esc_html_e( 'Date Option', 'lps' ); ?></td>
												<td>
													<label><input type="checkbox" name="lps_show_extra[]" id="lps_show_extra_date_diff" value="date_diff" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'as date difference', 'lps' ); ?></label>
													<p class="comment"><?php esc_html_e( 'If you check this option, the date for the tile (if that is included in the tile format) will display in date difference format (like 2 hours ago or 1 day ago, etc.).', 'lps' ); ?></p>
												</td>
											</tr>
										</table>
									</div>

									<div id="lps_url_wrap">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td class="lps_title_td"><?php esc_html_e( 'Use Post URL', 'lps' ); ?></td>
												<td>
													<select name="lps_url" id="lps_url" onchange="lpsRefresh()">
														<option value=""><?php esc_html_e( 'No link to the post', 'lps' ); ?></option>
														<option value="yes"><?php esc_html_e( 'Link to the post', 'lps' ); ?></option>
														<option value="yes_blank"><?php esc_html_e( 'Link to the post (_blank)', 'lps' ); ?></option>
														<option value="yes_media"><?php esc_html_e( 'Link to the media file', 'lps' ); ?></option>
														<option value="yes_media_blank"><?php esc_html_e( 'Link to the media file (_blank)', 'lps' ); ?></option>
														<option value="yes_media_lightbox" disabled><?php esc_html_e( 'Link to the media file with lightbox', 'lps' ); ?></option>
													</select>
													<div id="lps_url_options" class="block-use available-for-tiles">
														<p class="comment"><?php esc_html_e( 'See below the available tile patterns and select to one you want.', 'lps' ); ?></p>
														<b class="lps_title_td"><?php esc_html_e( 'Custom \'Read more\' message', 'lps' ); ?></b>
														<input type="text" name="lps_linktext" id="lps_linktext" onchange="lpsRefresh()" placeholder="<?php esc_html_e( 'Custom \'Read more\' message', 'lps' ); ?>" size="32">
														<p class="comment"><?php esc_html_e( 'Do not use brackets for the custom read more message, these are shortcodes delimiters.', 'lps' ); ?></p>
													</div>
												</td>
											</tr>
										</table>
										<div class="block-use available-for-tiles">
											<div id="lps_lightbox_options" class="lps-experimental">
												<table width="100%" cellspacing="0" cellpadding="2">
													<tr>
														<td class="lps_title_td"><?php esc_html_e( 'Lightbox Attributes', 'lps' ); ?></td>
														<td colspan="2">
															<p class="comment"><?php esc_html_e( 'If you want to use a lightbox for the images, you can setup below the image size to be available in the lightbox and the selector.', 'lps' ); ?></p>
														</td>
													</tr>
													<tr>
														<td><?php esc_html_e( 'Lightbox Image', 'lps' ); ?></td>
														<td colspan="2">
															<select name="lps_lightbox_size" id="lps_lightbox_size" onchange="lpsRefresh()">
																<option value="full"><?php esc_html_e( 'full (original size)', 'lps' ); ?></option>
																<?php $app_sizes = get_intermediate_image_sizes(); ?>
																<?php if ( ! empty( $app_sizes ) ) : ?>
																	<?php foreach ( $app_sizes as $s ) : ?>
																		<option value="<?php echo esc_attr( $s ); ?>"><?php echo esc_html( $s ); ?></option>
																	<?php endforeach; ?>
																<?php endif; ?>
															</select>
														</td>
													</tr>
													<tr>
														<td><?php esc_html_e( 'Selector attribute and value', 'lps' ); ?></td>
														<td>
															<input type="text" name="lps_lightbox_attr" id="lps_lightbox_attr" onchange="lpsRefresh()" placeholder="<?php esc_html_e( 'Ex: class', 'lps' ); ?>" size="32">
														</td>
														<td>
															<input type="text" name="lps_lightbox_val" id="lps_lightbox_val" onchange="lpsRefresh()" placeholder="<?php esc_html_e( 'Ex: fancybox image', 'lps' ); ?>" size="32">
														</td>
													</tr>
													<tr>
														<td> </td>
														<td colspan="2">
															<p class="comment"><?php esc_html_e( 'This feature has been tested and is recommended to be used with Easy FancyBox plugin (>=1.8) or FooBox Image Lightbox plugin (>=2.6).', 'lps' ); ?></p>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
									<div id="lps_image_wrap">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td><?php esc_html_e( 'Use Image', 'lps' ); ?></td>
												<td>
													<select name="lps_image" id="lps_image" onchange="lpsRefresh()">
														<option value=""><?php esc_html_e( 'No Image', 'lps' ); ?></option>
														<?php $app_sizes = get_intermediate_image_sizes(); ?>
														<?php if ( ! empty( $app_sizes ) ) : ?>
															<?php foreach ( $app_sizes as $s ) : ?>
																<option value="<?php echo esc_attr( $s ); ?>"><?php echo esc_html( $s ); ?></option>
															<?php endforeach; ?>
														<?php endif; ?>
														<option value="full"><?php esc_html_e( 'full (original size)', 'lps' ); ?></option>
													</select>
												</td>
											</tr>
											<tr>
												<td><?php esc_html_e( 'Image Placeholder', 'lps' ); ?></td>
												<td>
													<input type="text" name="lps_image_placeholder" id="lps_image_placeholder" onchange="lpsRefresh()">
													<p class="comment"><?php esc_html_e( 'Define an image to be used for the posts that do not have a featured image.', 'lps' ); ?></p>
												</td>
											</tr>
										</table>
									</div>

									<div class="block-use available-for-tiles">
										<table width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td colspan="2"><?php esc_html_e( 'Tile Pattern', 'lps' ); ?></td>
											</tr>
											<tr>
												<td colspan="2" class="normal">
													<input type="hidden" name="lps_elements" id="lps_elements" value="0" onchange="lpsRefresh()">
													<?php
													foreach ( self::$tile_pattern as $k => $p ) :
														$cl = ( in_array( $k, self::$tile_pattern_links, true ) ) ? 'with-link' : 'without-link';
														$cl = ( self::tile_markup_is_custom( $p ) ) ? 'custom-type wide' : $cl;
														?>
														<label class="<?php echo esc_attr( $cl ); ?>" onclick="LPS_generator.updateElements('<?php echo esc_attr( $k ); ?>');">
															<?php if ( self::tile_markup_is_custom( $p ) ) : ?>
																<input type="radio" name="lps_elements_img" id="lps_elements_img_<?php echo esc_attr( $k ); ?>" value="<?php echo esc_attr( $k ); ?>" readonly="readonly">
																<?php echo esc_html( $display_posts_list[ str_replace( ']', '', str_replace( '[', '', $p ) ) ] ); ?> <?php esc_html_e( 'markup', 'lps' ); ?>
															<?php else : ?>
																<img src="<?php echo esc_url( plugins_url( '/assets/images/post_tiles' . esc_attr( $k ) . '.png', __FILE__ ) ); ?>" title="<?php echo esc_attr( str_replace( '[a-r]', '[a]', $p ) ); ?>">
																<input type="radio" name="lps_elements_img" id="lps_elements_img_<?php echo esc_attr( $k ); ?>" value="<?php echo esc_attr( $k ); ?>">
															<?php endif; ?>
														</label>
													<?php endforeach; ?>
													<div class="clear"></div>
													<p id="tile_description_wrap" class="comment"><?php esc_html_e( 'The icons represent the order of the HTML tags, the links are marked with red.', 'lps' ); ?></p>
													<p id="custom_tile_description_wrap" class="comment"><?php esc_html_e( 'You are using a custom output, the markup is handled programatically, in your custom code.', 'lps' ); ?></p>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<br>
							<div id="tabs-4" class="settings-group">
								<h1>5. <?php esc_html_e( 'Extra Options', 'lps' ); ?></h1>
								<div class="block-use available-for-tiles"><p class="comment left-padd"><?php esc_html_e( 'Please note that if you are using a custom output template defined in your theme, the author, the taxonomies and the tags extra options will not function, since your custom template is overriding the output and the default behavior.', 'lps' ); ?></p></div>

								<?php
								$tax    = self::filtered_taxonomies();
								$tax    = wp_list_pluck( $tax, 'label', 'name' );
								$alltax = array(
									'author'    => esc_html__( 'Author', 'lps' ),
									'caption'   => esc_html__( 'Caption', 'lps' ) . ' ' . esc_html__( '(only for attachments)', 'lps' ),
									'show_mime' => esc_html__( 'Mime Type', 'lps' ) . ' ' . esc_html__( '(only for attachments)', 'lps' ),
								);
								if ( ! empty( $tax ) ) {
									$alltax = array_merge( $alltax, $tax );
								}

								$alltax['tags'] = esc_html__( 'Tags', 'lps' );
								?>
								<?php if ( ! empty( $alltax ) ) : ?>
									<div class="block-use available-for-tiles">
										<?php foreach ( $alltax as $slug => $name ) : ?>
											<div class="settings-block">
												<h3><?php echo esc_html( $name ); ?> <?php esc_html_e( 'Options', 'lps' ); ?></h3><hr>
												<label class="title wide"><input type="checkbox" name="lps_show_extra[]" id="lps_show_extra_<?php echo esc_attr( $slug ); ?>" value="<?php echo esc_attr( $slug ); ?>" onclick="lpsRefresh();" class="lps_show_extra lps-is-taxonomy"> <b><?php esc_html_e( 'Show ', 'lps' ); ?> <?php echo esc_html( $name ); ?></b></label>

												<div id="lps_show_extra_<?php echo esc_attr( $slug ); ?>_pos_wrap" class="extra-options-wrap">
													<?php if ( 'category' === $slug ) : ?>
														<label class="wide"><input type="checkbox" name="lps_show_extra[]" id="lps_show_extra_hide_uncategorized_<?php echo esc_attr( $slug ); ?>" value="hide_uncategorized_<?php echo esc_attr( $slug ); ?>" onclick="lpsRefresh();" class="lps_show_extra"> <b><?php esc_html_e( 'Do not display Uncategorized term', 'lps' ); ?></b></label><br>
													<?php endif; ?>
													<p><?php esc_html_e( 'Display position', 'lps' ); ?></p>

													<label class="show-options"><input type="radio" name="lps_show_extra_pos_<?php echo esc_attr( $slug ); ?>" id="lps_show_extra_taxpos_<?php echo esc_attr( $slug ); ?>_default" value="" checked="checked" onclick="lpsRefresh();" class="lps_show_extra"><?php esc_html_e( 'default', 'lps' ); ?>,</label>
													<?php foreach ( self::$tax_positions as $pos => $pos_title ) : ?>
														<label class="show-options"><input type="radio" name="lps_show_extra_pos_<?php echo esc_attr( $slug ); ?>" id="lps_show_extra_taxpos_<?php echo esc_attr( $slug ); ?>_<?php echo esc_attr( $pos ); ?>" value="taxpos_<?php echo esc_attr( $slug ); ?>_<?php echo esc_attr( $pos ); ?>" onclick="lpsRefresh();" class="lps_show_extra"><?php echo esc_html( $pos_title ); ?>,</label>
													<?php endforeach; ?>
												</div>
											</div>
										<?php endforeach; ?>

										<div class="settings-block">
											<table width="100%" cellspacing="0" cellpadding="2">
												<tr>
													<td><?php esc_html_e( 'Mime Type', 'lps' ); ?></td>
													<td>
														<label class="wide"><input type="checkbox" name="lps_show_extra[]" id="lps_show_extra_show_mime_class" value="show_mime_class" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'Show mime type as CSS class', 'lps' ); ?> </label>
														<p class="comment"><?php esc_html_e( 'The extra options will apply only to attachment post type.', 'lps' ); ?></p>
													</td>
												</tr>
												<tr>
													<td><?php esc_html_e( 'Line Break', 'lps' ); ?></td>
													<td>
														<label class="wide"><input type="checkbox" name="lps_show_extra[]" id="lps_show_extra_clearall" value="linebreak" onclick="lpsRefresh()" class="lps_show_extra"> <?php esc_html_e( 'clear the content below', 'lps' ); ?> </label>
														<p class="comment"><?php esc_html_e( 'The extra options will clear the content below by adding a line break after the shorcode.', 'lps' ); ?></p>
													</td>
												</tr>
											</table>
										</div>
									</div>
								<?php endif; ?>

								<div class="settings-block">
									<h3><?php esc_html_e( 'Style', 'lps' ); ?></h3><hr>
									<table width="100%" cellspacing="0" cellpadding="2">
										<tr>
											<td><?php esc_html_e( 'CSS Class', 'lps' ); ?></td>
											<td>
												<input type="text" name="lps_css" id="lps_css" onchange="lpsRefresh()" placeholder="<?php esc_attr_e( 'Ex: two-columns, three-columns, four-columns', 'lps' ); ?>" size="32">
												<p class="comment"><?php esc_html_e( 'The CSS class/classes you can use to customize the appearance of the shortcode output.', 'lps' ); ?></p>
											</td>
										</tr>
									</table>
									<p class="comment"><?php esc_html_e( 'Currently, the plugin offers out of the box support for two, three and four columns for the tiles and the overlay option. If, for example, you would like to display the tiles as four columns and the images as backgrounds, you can user `four-columns as-overlay`. Extra options for the overlay usage: `light` (for the overlay color), `tall` (to make the height of the tiles a bit bigger, so it fits more content).', 'lps' ); ?></p>
								</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Latest_Post_Shortcode_Slider::output_slider_shortcode()
	 */
	public static function output_slider_configuration() {
		?>
		<div id="lps_display_slider">
			<div class="settings-block">
				<h3><?php esc_html_e( 'Slider Settings', 'lps' ); ?></h3><hr>
				<table width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td><?php esc_html_e( 'Slides Transition', 'lps' ); ?></td>
						<td>
							<select name="lps_slidermode" id="lps_slidermode" onchange="lpsRefresh()">
								<option value="horizontal"><?php esc_html_e( 'horizontal', 'lps' ); ?></option>
								<option value="vertical"><?php esc_html_e( 'vertical', 'lps' ); ?></option>
								<option value="fade"><?php esc_html_e( 'fade', 'lps' ); ?></option>
							</select>
						</td>
					</tr>

					<tr>
						<td><?php esc_html_e( 'Center Mode', 'lps' ); ?></td>
						<td>
							<select name="lps_centermode" id="lps_centermode" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no', 'lps' ); ?></option>
								<option value="true"><?php esc_html_e( 'center mode', 'lps' ); ?></option>
							</select>
						</td>
					</tr>
					<tbody id="lps_centermode_options">
						<tr>
							<td colspan="2" class="normal comment">
								<p class="comment"><?php esc_html_e( 'The center mode is intended to works for the cases when you are showing more slides per row, and works better when you use an odd number of slides (3, 5, etc.), so, if your slider is presenting one slide at a time, you might want to switch this option off.', 'lps' ); ?></p>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Center Mode Padding', 'lps' ); ?></td>
							<td>
								<input type="number" name="lps_centerpadd" id="lps_centerpadd" onchange="lpsRefresh()" value="5" min="5" max="50">
									<p class="comment"><?php esc_html_e( 'The value in pixels for the padding of the center slide.', 'lps' ); ?></p>
							</td>
						</tr>
					</tbody>

					<tr>
						<td><?php esc_html_e( 'Auto Play', 'lps' ); ?></td>
						<td>
							<select name="lps_sliderauto" id="lps_sliderauto" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no', 'lps' ); ?></option>
								<option value="true"><?php esc_html_e( 'yes', 'lps' ); ?></option>
							</select>
						</td>
					</tr>
					<tbody id="lps_sliderauto_options">
						<tr>
							<td><?php esc_html_e( 'Speed', 'lps' ); ?></td>
							<td>
								<input type="number" name="lps_sliderspeed" id="lps_sliderspeed" onchange="lpsRefresh()" value="3000" min="1000" max="20000">
									<p class="comment"><?php esc_html_e( 'Autoplay speed in milliseconds.', 'lps' ); ?></p>
							</td>
						</tr>
					</tbody>
					<tr>
						<td><?php esc_html_e( 'Height', 'lps' ); ?></td>
						<td>
							<select name="lps_sliderheight" id="lps_sliderheight" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'adaptive', 'lps' ); ?></option>
								<option value="fixed"><?php esc_html_e( 'fixed', 'lps' ); ?></option>
							</select>
							<p class="comment"><?php esc_html_e( 'This depends on the image size you select', 'lps' ); ?></p>
						</td>
					</tr>
					<tbody id="lps_sliderheight_options">
						<tr>
							<td><?php esc_html_e( 'Maximum Height', 'lps' ); ?></td>
							<td>
								<input type="number" name="lps_slidermaxheight" id="lps_slidermaxheight" onchange="lpsRefresh()" value="280" min="1" max="1200">
								<p class="comment"><?php esc_html_e( 'Provide the value in pixels for the maximum height of the slider.', 'lps' ); ?></p>
							</td>
						</tr>
					</tbody>
					<tr>
						<td><?php esc_html_e( 'Show Slide Overlay', 'lps' ); ?></td>
						<td>
							<select name="lps_slideoverlay" id="lps_slideoverlay" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'title + few chars from the excerpt', 'lps' ); ?></option>
								<option value="title"><?php esc_html_e( 'only title ', 'lps' ); ?></option>
								<option value="text"><?php esc_html_e( 'only few chars from the excerpt', 'lps' ); ?></option>
								<option value="no"><?php esc_html_e( 'no overlay, just the image', 'lps' ); ?></option>
							</select>
							<p class="comment"><?php esc_html_e( 'The slide will display over the image the title and few chars from the excerpt (what you define for the Post Appearance > Chars Limit below).', 'lps' ); ?></p>
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Slides Gaps', 'lps' ); ?></td>
						<td>
							<select name="lps_slidegap" id="lps_slidegap" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no gaps', 'lps' ); ?></option>
								<option value="5"><?php esc_html_e( '5px', 'lps' ); ?></option>
								<option value="10"><?php esc_html_e( '10px', 'lps' ); ?></option>
								<option value="15"><?php esc_html_e( '15px', 'lps' ); ?></option>
								<option value="20"><?php esc_html_e( '20px', 'lps' ); ?></option>
								<option value="25"><?php esc_html_e( '25px', 'lps' ); ?></option>
								<option value="30"><?php esc_html_e( '30px', 'lps' ); ?></option>
								<option value="50"><?php esc_html_e( '50px', 'lps' ); ?></option>
							</select>
							<p class="comment"><?php esc_html_e( 'This is useful when you want to display more visible slides in the row.', 'lps' ); ?></p>
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Slides to Show', 'lps' ); ?></td>
						<td>
							<input type="number" name="lps_slideslides" id="lps_slideslides" onchange="lpsRefresh()" value="1" min="1" max="12">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Slides to Scroll', 'lps' ); ?></td>
						<td>
							<input type="number" name="lps_slidescroll" id="lps_slidescroll" onchange="lpsRefresh()" value="1" min="1" max="12">
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Show Dots', 'lps' ); ?></td>
						<td>
							<select name="lps_sliderdots" id="lps_sliderdots" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no', 'lps' ); ?></option>
								<option value="true"><?php esc_html_e( 'yes', 'lps' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Infinite Scroll', 'lps' ); ?></td>
						<td>
							<select name="lps_sliderinfinite" id="lps_sliderinfinite" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no', 'lps' ); ?></option>
								<option value="true"><?php esc_html_e( 'yes', 'lps' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Next/Previous', 'lps' ); ?></td>
						<td>
							<select name="lps_slidercontrols" id="lps_slidercontrols" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no', 'lps' ); ?></option>
								<option value="true"><?php esc_html_e( 'yes', 'lps' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Responsive', 'lps' ); ?></td>
						<td>
							<select name="lps_slidersponsive" id="lps_slidersponsive" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no', 'lps' ); ?></option>
								<option value="yes"><?php esc_html_e( 'yes', 'lps' ); ?></option>
							</select>
						</td>
					</tr>
				</table>
			</div>

			<div id="lps_slidersponsive_options" class="settings-block">
				<h3><?php esc_html_e( 'Responsive Slider Settings', 'lps' ); ?></h3><hr>
				<table width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td><?php esc_html_e( 'Respond to', 'lps' ); ?></td>
						<td>
							<select name="lps_respondto" id="lps_respondto" onchange="lpsRefresh()">
								<option value="window"><?php esc_html_e( 'window', 'lps' ); ?></option>
								<option value="slider"><?php esc_html_e( 'slider', 'lps' ); ?></option>
								<option value=""><?php esc_html_e( 'min', 'lps' ); ?></option>
							</select>
							<p class="comment">
								<?php esc_html_e( 'Width that responsive object responds to, the min (default) option means the smaller of the window or slider.', 'lps' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<td colspan="2"><?php esc_html_e( 'Tablet Settings', 'lps' ); ?></td>
					</tr>
					<tr>
						<td class="left-padd">&bull; <?php esc_html_e( 'Breakpoint', 'lps' ); ?></td>
						<td>
							<input type="number" name="lps_sliderbreakpoint_tablet" id="lps_sliderbreakpoint_tablet" onchange="lpsRefresh()" value="600" min="320" max="1200">
						</td>
					</tr>
					<tr>
						<td class="left-padd">&bull; <?php esc_html_e( 'Slides to Show', 'lps' ); ?></td>
						<td>
							<input type="number" name="lps_slideslides_tablet" id="lps_slideslides_tablet" onchange="lpsRefresh()" value="1" min="1" max="12">
						</td>
					</tr>
					<tr>
						<td class="left-padd">&bull; <?php esc_html_e( 'Slides to Scroll', 'lps' ); ?></td>
						<td>
							<input type="number" name="lps_slidescroll_tablet" id="lps_slidescroll_tablet" onchange="lpsRefresh()" value="1" min="1" max="12">
						</td>
					</tr>
					<tr>
						<td class="left-padd">&bull; <?php esc_html_e( 'Show Dots', 'lps' ); ?></td>
						<td>
							<select name="lps_sliderdots_tablet" id="lps_sliderdots_tablet" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no', 'lps' ); ?></option>
								<option value="true"><?php esc_html_e( 'yes', 'lps' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="left-padd">&bull; <?php esc_html_e( 'Infinite Scroll', 'lps' ); ?></td>
						<td>
							<select name="lps_sliderinfinite_tablet" id="lps_sliderinfinite_tablet" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no', 'lps' ); ?></option>
								<option value="true"><?php esc_html_e( 'yes', 'lps' ); ?></option>
							</select>
						</td>
					</tr>

					<tr>
						<td colspan="2"><?php esc_html_e( 'Mobile Settings', 'lps' ); ?></td>
					</tr>
					<tr>
						<td class="left-padd">&bull; <?php esc_html_e( 'Breakpoint', 'lps' ); ?></td>
						<td>
							<input type="number" name="lps_sliderbreakpoint_mobile" id="lps_sliderbreakpoint_mobile" onchange="lpsRefresh()" value="480" min="320" max="1024">
						</td>
					</tr>
					<tr>
						<td class="left-padd">&bull; <?php esc_html_e( 'Slides to Show', 'lps' ); ?></td>
						<td>
							<input type="number" name="lps_slideslides_mobile" id="lps_slideslides_mobile" onchange="lpsRefresh()" value="1" min="1" max="12">
						</td>
					</tr>
					<tr>
						<td class="left-padd">&bull; <?php esc_html_e( 'Slides to Scroll', 'lps' ); ?></td>
						<td>
							<input type="number" name="lps_slidescroll_mobile" id="lps_slidescroll_mobile" onchange="lpsRefresh()" value="1" min="1" max="12">
						</td>
					</tr>
					<tr>
						<td class="left-padd">&bull; <?php esc_html_e( 'Show Dots', 'lps' ); ?></td>
						<td>
							<select name="lps_sliderdots_mobile" id="lps_sliderdots_mobile" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no', 'lps' ); ?></option>
								<option value="true"><?php esc_html_e( 'yes', 'lps' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="left-padd">&bull; <?php esc_html_e( 'Infinite Scroll', 'lps' ); ?></td>
						<td>
							<select name="lps_sliderinfinite_mobile" id="lps_sliderinfinite_mobile" onchange="lpsRefresh()">
								<option value=""><?php esc_html_e( 'no', 'lps' ); ?></option>
								<option value="true"><?php esc_html_e( 'yes', 'lps' ); ?></option>
							</select>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php
	}

	/**
	 * Get short text of maximum x chars.
	 *
	 * @param  string  $text       Text.
	 * @param  integer $limit      Limit of chars.
	 * @param  boolean $is_excerpt True if this represents an excerpt.
	 * @return string
	 */
	public static function get_short_text( $text, $limit, $is_excerpt = false ) {
		$filter = ( $is_excerpt ) ? 'the_excerpt' : 'the_content';

		$text = strip_tags( $text );
		$text = preg_replace( '~\[[^\]]+\]~', '', $text );
		$text = strip_shortcodes( $text );
		$text = apply_filters( $filter, strip_shortcodes( $text ) );
		$text = preg_replace( '~\[[^\]]+\]~', '', $text );
		$text = strip_tags( $text );
		$text = preg_replace( '~\[[^\]]+\]~', '', $text );
		/** This is a trick to replace the unicode whitespace :) */
		$text = preg_replace( '/\xA0/u', ' ', $text );
		$text = str_replace( '&nbsp;', ' ', $text );
		$text = preg_replace( '/\s\s+/', ' ', $text );
		$text = preg_replace( '/\s+/', ' ', $text );
		$text = trim( $text );
		if ( ! empty( $text ) ) {
			$content = explode( ' ', $text );
			$len     = 0;
			$i       = 0;
			$max     = count( $content );
			$text    = '';
			while ( $len < $limit ) {
				$text .= $content[ $i ] . ' ';
				$i ++;
				$len = strlen( $text );
				if ( $i >= $max ) {
					break;
				}
			}
			$text = trim( $text );
			$text = preg_replace( '/\[.+\]/', '', $text );
			$text = apply_filters( $filter, $text );
			$text = str_replace( ']]>', ']]&gt;', $text );
		}
		return $text;
	}

	/**
	 * Return the content generated after an ajax call for the pagination.
	 *
	 * @return void
	 */
	public static function lps_navigate_callback() {
		$args    = filter_input( INPUT_POST, 'args', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$current = filter_input( INPUT_POST, 'current', FILTER_DEFAULT );
		if ( ! empty( $args ) ) {
			if ( ! empty( $current ) ) {
				if ( empty( $args['excludeid'] ) ) {
					$args['excludeid'] = (int) $current;
				} else {
					$args['excludeid'] .= ',' . (int) $current;
				}
			}
			header( 'Content-type: text/html; charset=utf-8' );
			$_args = $args;
			if ( is_array( $args ) ) {
				foreach ( $args as $key => $value ) {
					$args[ $key ] = sanitize_text_field( $value );
				}
			} else {
				$_args = stripslashes( stripslashes( $args ) );
				$args  = ( ! empty( $_args ) ) ? json_decode( $_args ) : false;
			}

			$ppage = filter_input( INPUT_POST, 'page', FILTER_DEFAULT );
			if ( ! empty( $ppage ) && $args ) {
				$args = (array) $args;
				if ( ! empty( $args['linktext'] ) ) {
					$args['linktext'] = preg_replace( '/u([0-9a-z]{4})+/', '&#x$1;', $args['linktext'] );
				}
				set_query_var( 'page', (int) $ppage );
				echo self::latest_selected_content( $args ); // WPCS: XSS OK.
			}
		}
		die();
	}

	/**
	 * Return the content generated for plugin pagination with the specific arguments.
	 *
	 * @param  integer $total        Total of records.
	 * @param  integer $per_page     How many per page.
	 * @param  integer $range        Range size.
	 * @param  string  $shortcode_id Shortcode id (element selector).
	 * @param  string  $class        Pagination CSS class.
	 * @param  string  $more_text    Load more text.
	 * @return string
	 */
	public static function lps_pagination( $total = 1, $per_page = 10, $range = 4, $shortcode_id = '', $class = '', $more_text = '' ) {
		wp_reset_postdata();
		$body     = '';
		$total    = (int) $total;
		$per_page = ( ! empty( $per_page ) ) ? (int) $per_page : 1;
		$range    = abs( (int) $range );
		$range    = ( empty( $range ) ) ? 1 : $range;
		$total    = ceil( $total / $per_page );
		if ( $total > 1 ) {
			$current_page = get_query_var( 'page' ) ? (int) get_query_var( 'page' ) : 1;

			if ( 0 === ( $current_page % $range ) ) {
				$start = $current_page - $range + 1;
			} else {
				$start = $current_page - $current_page % $range + 1;
			}
			$start = ( $start <= 1 ) ? 1 : $start;
			$end   = $start + $range - 1;
			if ( $end >= $total ) {
				$end = $total;
			}

			if ( substr_count( $class, ' lps-load-more' ) ) {
				$body .= '<ul class="latest-post-selection pages ' . esc_attr( trim( $class ) ) . ' ' . esc_attr( $shortcode_id ) . '">';
				if ( $current_page < $total ) {
					$text  = ( ! empty( $more_text ) ) ? $more_text : __( 'Load more', 'lps' );
					$body .= '<li class="go-to-next lps-load-more"><a class="page-item" href="' . get_pagenum_link( $current_page + 1 ) . '" data-page="' . ( $current_page + 1 ) . '" title="' . esc_attr( $text ) . '">' . esc_html( $text ) . '</a></li>';
				}
				$body .= '</ul>';
			} else {

				$body .= '
				<ul class="latest-post-selection pages ' . esc_attr( trim( $class ) ) . ' ' . esc_attr( $shortcode_id ) . '">
					<li class="pages-info">' . __( 'Page', 'lps' ) . ' ' . $current_page . ' ' . __( 'of', 'lps' ) . ' ' . $total . '</li>';

				if ( $total > $range && $start > $range ) {
					$body .= '<li class="go-to-first"><a class="page-item" href="' . get_permalink() . '" data-page="1" title="' . esc_attr__( 'First', 'lps' ) . '">&lsaquo;&nbsp;</a></li>';
				} else {
					if ( $total > $range ) {
						$body .= '<li class="go-to-first disabled"><a class="page-item" data-page="' . $current_page . '" title="' . esc_attr__( 'First', 'lps' ) . '">&lsaquo;&nbsp;</a></li>';
					}
				}

				if ( $current_page > 1 ) {
					if ( 2 === $current_page ) {
						$body .= '<li class="go-to-prev"><a class="page-item" href="' . get_permalink() . '" data-page="1" title="' . esc_attr__( 'Previous', 'lps' ) . '">&laquo;</a></li>';
					} else {
						$body .= '<li class="go-to-prev"><a class="page-item" href="' . get_pagenum_link( $current_page - 1 ) . '" data-page="' . ( $current_page - 1 ) . '" title="' . esc_attr__( 'Previous', 'lps' ) . '">&laquo;</a></li>';
					}
				} else {
					$body .= '<li class="go-to-prev disabled"><a class="page-item" data-page="' . $current_page . '" title="' . esc_attr__( 'Previous', 'lps' ) . '">&laquo;</a></li>';
				}

				for ( $i = $start; $i <= $end; $i ++ ) {
					if ( 1 === $i ) {
						if ( $current_page === $i ) {
							$body .= '<li class="current"><a class="page-item" href="' . get_permalink() . '" data-page="1" title="' . esc_attr__( 'First', 'lps' ) . '">' . $i . '</a></li>';
						} else {
							$body .= '<li><a class="page-item" href="' . get_permalink() . '" data-page="1" title="' . esc_attr__( 'First', 'lps' ) . '">' . $i . '</a></li>';
						}
					} else {
						if ( $current_page === $i ) {
							$body .= '<li class="current"><a class="page-item" data-page="' . $i . '" title="' . esc_attr__( 'Page', 'lps' ) . ' ' . $i . '">' . $i . '</a></li>';
						} else {
							$body .= '<li><a class="page-item" href="' . get_pagenum_link( $i ) . '" data-page="' . $i . '" title="' . esc_attr__( 'Page', 'lps' ) . ' ' . $i . '">' . $i . '</a></li>';
						}
					}
				}

				if ( $current_page < $total ) {
					$body .= '<li class="go-to-next"><a class="page-item" href="' . get_pagenum_link( $current_page + 1 ) . '" data-page="' . ( $current_page + 1 ) . '" title="' . esc_attr__( 'Next', 'lps' ) . '">&raquo;</a></li>';
				} else {
					$body .= '<li class="go-to-next disabled"><a class="page-item" data-page="' . $current_page . '" title="' . esc_attr__( 'Next', 'lps' ) . '">&raquo;</a></li>';
				}
				if ( $end < $total ) {
					$body .= '<li class="go-to-last"><a class="page-item" href="' . get_pagenum_link( $total ) . '" data-page="' . $total . '" title="' . esc_attr__( 'Last', 'lps' ) . '">&nbsp;&rsaquo;</a></li>';
				} else {
					if ( $total > $range ) {
						$body .= '<li class="go-to-last disabled"><a class="page-item" data-page="' . $current_page . '" title="' . esc_attr__( 'Last', 'lps' ) . '">&nbsp;&rsaquo;</a></li>';
					}
				}
				$body .= '</ul>';
			}

			if ( get_site_url() . '/' === get_permalink() ) {
				/** We must use /page/x */
				if ( ! empty( $current_page ) && substr_count( $body, '/page/' . $current_page . '/' ) !== 0 ) {
					$body = str_replace( '/page/' . $current_page . '/', '/', $body );
				}
			} else {
				/** We must use /x */
				$body = str_replace( '/' . $current_page . '/page/', '/', $body );
				$body = str_replace( '/page/', '/', $body );
			}
		}

		return $body;
	}

	/**
	 * Dynamic relative time.
	 *
	 * @param integer $id The post ID.
	 * @return string
	 */
	public static function relative_time( $id = null ) {
		return sprintf(
			// Translators: %s the date difference.
			_x( '%s ago', '%s = human-readable time difference', 'lps' ),
			strtolower( human_time_diff( get_the_time( 'U', $id ), current_time( 'timestamp' ) ) )
		);
	}

	/**
	 * Return the content generated by a shortcode with the specific arguments.
	 *
	 * @param  array $args Array of shortcode arguments.
	 * @return string
	 */
	public static function latest_selected_content( $args ) {
		global $post, $lps_current_post_embedded_item_ids;
		if ( empty( $lps_current_post_embedded_item_ids ) ) {
			$lps_current_post_embedded_item_ids = array();
		}

		$lps_current_post_embedded_item_ids = apply_filters( 'lps_filter_exclude_previous_content_ids', $lps_current_post_embedded_item_ids );

		// Maybe filter some more shortcode arguments.
		$args = apply_filters( 'lps_filter_use_custom_shortcode_arguments', $args );

		// Get the post arguments from shortcode arguments.
		$ids             = ( ! empty( $args['id'] ) ) ? explode( ',', $args['id'] ) : array();
		$exclude_ids     = ( ! empty( $args['excludeid'] ) ) ? explode( ',', $args['excludeid'] ) : array();
		$parent          = ( ! empty( $args['parent'] ) ) ? explode( ',', $args['parent'] ) : array();
		$author          = ( ! empty( $args['author'] ) ) ? explode( ',', $args['author'] ) : array();
		$exclude_authors = ( ! empty( $args['excludeauthor'] ) ) ? explode( ',', $args['excludeauthor'] ) : array();

		$type          = ( ! empty( $args['type'] ) ) ? $args['type'] : 'post';
		if ( substr_count( $type, ',' ) ) {
			$type = explode( ',', $type );
		}
		$titletag      = ( ! empty( $args['titletag'] ) && in_array( $args['titletag'], self::$title_tags ) ) ? $args['titletag'] : 'h3';
		$chrlimit      = ( ! empty( $args['chrlimit'] ) ) ? intval( $args['chrlimit'] ) : 120;
		$extra_display = ( ! empty( $args['display'] ) ) ? explode( ',', $args['display'] ) : array( 'title' );
		$linkurl       = ( ! empty( $args['url'] ) && ( 'yes' === $args['url'] || 'yes_blank' === $args['url'] ) ) ? true : false;
		$linkmedia     = ( ! empty( $args['url'] ) && ( 'yes_media' === $args['url'] || 'yes_media_blank' === $args['url'] || 'yes_media_lightbox' === $args['url'] ) ) ? true : false;
		$lightbox_size = ( $linkmedia && ! empty( $args['lightbox_size'] ) ) ? $args['lightbox_size'] : '';
		$lightbox_attr = ( $linkmedia && ! empty( $args['lightbox_attr'] ) ) ? $args['lightbox_attr'] : '';
		$lightbox_val  = ( $linkmedia && ! empty( $args['lightbox_val'] ) ) ? $args['lightbox_val'] : '';
		$linkblank     = ( ! empty( $args['url'] ) && ( 'yes_blank' === $args['url'] || 'yes_media_blank' === $args['url'] || 'yes_media_lightbox' === $args['url'] ) ) ? true : false;

		$tile_type     = 0;
		if ( $linkurl || $linkmedia ) {
			$linktext = ( ! empty( $args['linktext'] ) ) ? $args['linktext'] : '';
		}

		$tile_type       = ( ! empty( $args['elements'] ) && ! empty( self::$tile_pattern[ $args['elements'] ] ) ) ? $args['elements'] : 0;
		$tile_pattern    = ( ! empty( self::$tile_pattern[ $tile_type ] ) ) ? self::$tile_pattern[ $tile_type ] : 'title';

		$link_class      = ' class="main-link"';
		$read_more_class = ' class="read-more"';
		$lightbox_extra  = '';
		if ( in_array( (int) $tile_type, array( 3, 11, 14, 19 ), true ) ) {
			$link_class      = ' class="main-link read-more-wrap"';
			$read_more_class = '';
		}
		if ( ! empty( $lightbox_attr ) ) {
			if ( 'class' === $lightbox_attr ) {
				if ( $link_class ) {
					$link_class = str_replace( 'class="', 'class="' . esc_attr( $lightbox_val ) . ' ', $link_class );
				}
				if ( $read_more_class ) {
					$read_more_class = str_replace( 'class="', 'class="' . esc_attr( $lightbox_val ) . ' ', $read_more_class );
				}
			} else {
				$lightbox_extra = ' ' . esc_attr( $lightbox_attr ) . '="' . esc_attr( $lightbox_val ) . '"';
			}
		}

		$show_extra      = ( ! empty( $args['show_extra'] ) ) ? explode( ',', $args['show_extra'] ) : array();
		$raw_content     = ( in_array( 'raw', $show_extra ) ) ? true : false;

		$qargs = array(
			'numberposts' => 1,
			'post_status' => 'publish',
		);

		if ( ! empty( $args['status'] ) ) {
			$qargs['post_status'] = explode( ',', trim( $args['status'] ) );
			if ( in_array( 'private', $qargs['post_status'], true ) ) {
				if ( ! is_user_logged_in() ) {
					$pkey = array_search( 'private', $qargs['post_status'], true );
					if ( false !== $pkey ) {
						unset( $qargs['post_status'][ $pkey ] );
					}
				}
			}
		}
		if ( empty( $qargs['post_status'] ) ) {
			return;
		}
		self::$current_query_statuses_list = $qargs['post_status'];
		$orderby          = ( ! empty( $args['orderby'] ) ) ? $args['orderby'] : 'dateD';
		$qargs['order']   = 'DESC';
		$qargs['orderby'] = 'date';
		if ( ! empty( $orderby ) && ! empty( self::$orderby_options[ $orderby ] ) ) {
			$qargs['order']   = self::$orderby_options[ $orderby ]['order'];
			$qargs['orderby'] = self::$orderby_options[ $orderby ]['orderby'];
		}

		// Make sure we do not loop in the current page.
		if ( ! empty( $post->ID ) ) {
			$qargs['post__not_in'] = array( $post->ID );
		}

		// Exclude specified post IDs.
		if ( ! empty( $exclude_ids ) ) {
			if ( ! empty( $qargs['post__not_in'] ) ) {
				$qargs['post__not_in'] = array_merge( $qargs['post__not_in'], $exclude_ids );
			} else {
				$qargs['post__not_in'] = $exclude_ids;
			}
		}

		if ( ! empty( $show_extra ) && in_array( 'exclude_previous_content', $show_extra, true ) ) {
			// Exclude the previous ID embedded through the plugin shortcodes on this page.
			if ( is_scalar( $qargs['post__not_in'] ) ) {
				$qargs['post__not_in'] = array( $qargs['post__not_in'] );
			}
			if ( empty( $lps_current_post_embedded_item_ids ) ) {
				$lps_current_post_embedded_item_ids = array();
			}
			$qargs['post__not_in'] = array_merge( $qargs['post__not_in'], $lps_current_post_embedded_item_ids );
		}

		if ( ! empty( $author ) ) {
			$qargs['author__in'] = $author;
		}

		if ( ! empty( $exclude_authors ) ) {
			$qargs['author__not_in'] = $exclude_authors;
		}

		if ( ! empty( $args['limit'] ) ) {
			$qargs['numberposts']    = ( ! empty( $args['limit'] ) ) ? intval( $args['limit'] ) : 1;
			$qargs['posts_per_page'] = ( ! empty( $args['limit'] ) ) ? intval( $args['limit'] ) : 1;
		}
		if ( empty( $args['output'] ) ) {
			if ( ! empty( $args['perpage'] ) ) {
				$qargs['posts_per_page'] = ( ! empty( $args['perpage'] ) ) ? intval( $args['perpage'] ) : 0;
				$paged                   = get_query_var( 'page' ) ? abs( intval( get_query_var( 'page' ) ) ) : 1;
				$current_page            = $paged;
				$qargs['paged']          = $paged;
				$qargs['page']           = $current_page;
			}
			if ( ! empty( $args['offset'] ) ) {
				$qargs['offset'] = ( ! empty( $args['offset'] ) ) ? intval( $args['offset'] ) : 0;
				if ( ! empty( $qargs['paged'] ) ) {
					$qargs['offset'] = abs( $current_page - 1 ) * $args['offset'];
				}
			}
		}

		$force_type = true;
		if ( ! empty( $ids ) && is_array( $ids ) ) {
			foreach ( $ids as $k => $v ) {
				$ids[ $k ] = intval( $v );
			}
			$qargs['post__in'] = $ids;
			$force_type        = false;
		}
		if ( $force_type ) {
			$qargs['post_type'] = $type;
		} else {
			if ( ! empty( $args['type'] ) ) {
				$qargs['post_type'] = $args['type'];
			}
		}
		if ( ! empty( $parent ) ) {
			$qargs['post_parent__in'] = $parent;
		}
		$qargs['tax_query'] = array();
		if ( ! empty( $args['tag'] ) ) {
			array_push(
				$qargs['tax_query'],
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'terms'    => ( ! empty( $args['tag'] ) ) ? explode( ',', $args['tag'] ) : 'homenews',
				)
			);
		}
		if ( ! empty( $args['dtag'] ) && ! empty( $post->ID ) ) {
			$tag_ids = wp_get_post_tags( $post->ID, array(
				'fields' => 'ids',
			) );
			if ( ! empty( $tag_ids ) && is_array( $tag_ids ) ) {
				if ( ! empty( $qargs['tax_query'] ) ) {
					array_push(
						$qargs['tax_query'],
						array(
							'relation' => 'AND',
						)
					);
				}
				array_push(
					$qargs['tax_query'],
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'term_id',
						'terms'    => $tag_ids,
						'operator' => 'IN',
					)
				);
			}
		}
		if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			if ( ! empty( $qargs['tax_query'] ) ) {
				array_push(
					$qargs['tax_query'],
					array(
						'relation' => 'AND',
					)
				);
			}
			array_push(
				$qargs['tax_query'],
				array(
					'taxonomy' => $args['taxonomy'],
					'field'    => 'slug',
					'terms'    => explode( ',', $args['term'] ),
				)
			);
		}
		if ( ! empty( $args['taxonomy2'] ) && ! empty( $args['term2'] ) ) {
			if ( ! empty( $qargs['tax_query'] ) ) {
				array_push(
					$qargs['tax_query'],
					array(
						'relation' => 'AND',
					)
				);
			}
			array_push(
				$qargs['tax_query'],
				array(
					'taxonomy' => $args['taxonomy2'],
					'field'    => 'slug',
					'terms'    => explode( ',', $args['term2'] ),
				)
			);
		}


		if ( ! empty( $args['exclude_tags'] ) ) {
			if ( ! empty( $qargs['tax_query'] ) ) {
				array_push(
					$qargs['tax_query'],
					array(
						'relation' => 'AND',
					)
				);
			}
			array_push(
				$qargs['tax_query'],
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'terms'    => explode( ',', $args['exclude_tags'] ),
					'operator' => 'NOT IN',
				)
			);
		}
		if ( ! empty( $args['exclude_categories'] ) ) {
			if ( ! empty( $qargs['tax_query'] ) ) {
				array_push(
					$qargs['tax_query'],
					array(
						'relation' => 'AND',
					)
				);
			}
			array_push(
				$qargs['tax_query'],
				array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => explode( ',', $args['exclude_categories'] ),
					'operator' => 'NOT IN',
				)
			);
		}

		if ( ! empty( $args['date_limit'] ) && ( ! empty( $args['date_start'] ) || ! empty( $args['date_start_type'] ) ) ) {
			$drange  = ( ! empty( $args['date_start_type'] ) && in_array( $args['date_start_type'], self::$date_limit_units ) ) ? $args['date_start_type'] : 'months';
			$s_date = strtotime( date( 'Y-m-d' ) . ' -' . abs( (int) $args['date_start'] ) . $drange );
			if ( ! empty( $s_date ) ) {
				$args['date_after'] = date( 'Y-m-d', $s_date );
			}
		}
		if ( ! empty( $args['date_after'] ) || ! empty( $args['date_before'] ) ) {
			$drange = array();
			if ( ! empty( $args['date_after'] ) ) {
				$drange['after'] = esc_attr( $args['date_after'] );
			}
			if ( ! empty( $args['date_before'] ) ) {
				$drange['before'] = esc_attr( $args['date_before'] );
			}
			$qargs['date_query'] = array(
				array(
					array_merge(
						$drange,
						array(
							'inclusive' => true,
						)
					),
				),
			);
		}

		if ( ! empty( $show_extra ) ) {
			if ( in_array( 'nosticky', $show_extra ) ) {
				$qargs['ignore_sticky_posts'] = true;
				$sticky_ids = get_option( 'sticky_posts' );
				if ( ! empty( $sticky_ids ) ) {
					if ( ! empty( $qargs['post__not_in'] ) ) {
						$qargs['post__not_in'] = array_merge( $qargs['post__not_in'], $sticky_ids );
					} else {
						$qargs['post__not_in'] = $sticky_ids;
					}
				}
			} elseif ( in_array( 'sticky', $show_extra ) ) {
				$qargs['ignore_sticky_posts'] = false;
				$sticky_ids = get_option( 'sticky_posts' );
				if ( ! empty( $sticky_ids ) ) {
					if ( ! empty( $qargs['post__in'] ) ) {
						$qargs['post__in'] = array_merge( $qargs['post__in'], $sticky_ids );
					} else {
						$qargs['post__in'] = $sticky_ids;
					}
				}
			}
		}

		$qargs['suppress_filters'] = false;

		// Maybe use some custom query arguments.
		$qargs = apply_filters( 'lps_filter_use_custom_query_arguments', $qargs );
		if ( 'attachment' === $qargs['post_type'] ) {
			add_filter( 'posts_where', array( get_called_class(), 'attachment_custom_where' ), 50, 2 );
			add_filter( 'posts_join', array( get_called_class(), 'attachment_custom_join' ), 50, 2 );
		}

		$posts = get_posts( $qargs );

		// If the slider extension is enabled and the shortcode is configured to output the slider, let's do that and return.
		if ( ! empty( $posts ) && ! empty( $args['output'] ) && 'slider' === $args['output'] ) {
			ob_start();
			self::latest_selected_content_slider( $posts, $args );
			wp_reset_postdata();
			return ob_get_clean();
		}

		$is_lps_ajax  = (int) filter_input( INPUT_POST, 'lps_ajax', FILTER_DEFAULT );
		$shortcode_id = 'lps-' . md5( serialize( $args ) . microtime() );

		ob_start();
		$forced_end = '';
		if ( ! empty( $qargs['posts_per_page'] ) && ! empty( $args['showpages'] ) ) {
			$pagination_class  = in_array( 'pagination_all', $show_extra, true ) ? 'all-elements' : '';
			$pagination_class .= ( 'more' === $args['showpages'] ) ? ' lps-load-more' : '';
			if ( ! empty( $lightbox_attr ) ) {
				$pagination_class .= ' lps-lightbox';
			}

			$counter           = new WP_Query( $qargs );
			$found_posts       = ( ! empty( $counter->found_posts ) ) ? $counter->found_posts : 0;
			$pagination_html   = self::lps_pagination( intval( $found_posts ), ( ! empty( $qargs['posts_per_page'] ) ) ? $qargs['posts_per_page'] : 1, intval( $args['showpages'] ), $shortcode_id, $pagination_class, ( ! empty( $args['loadtext'] ) ? $args['loadtext'] : '' ) );

			if ( ! empty( $is_lps_ajax ) ) {
				// No need to put again the top level.
			} else {
				if ( in_array( 'ajax_pagination', $show_extra, true ) && ! empty( $args ) && is_array( $args ) ) {
					$maybe_spinner = '';
					if ( in_array( 'light_spinner', $show_extra, true ) ) {
						$maybe_spinner = ' light_spinner';
					} elseif ( in_array( 'dark_spinner', $show_extra, true ) ) {
						$maybe_spinner = ' dark_spinner';
					}
					if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
						echo '<div id="' . esc_attr( $shortcode_id ) . '-wrap" data-args="' . esc_js( wp_json_encode( $args ) ) . '" data-current="' . get_the_ID() . '" class="lps-top-section-wrap' . $maybe_spinner . '">'; // WPCS: XSS OK.
					} else {
						echo '<div id="' . esc_attr( $shortcode_id ) . '-wrap" data-args="' . esc_js( wp_json_encode( $args, JSON_UNESCAPED_UNICODE ) ) . '" data-current="' . get_the_ID() . '" class="lps-top-section-wrap' . $maybe_spinner . '">'; // WPCS: XSS OK.
					}
				} else {
					echo '<div id="' . esc_attr( $shortcode_id ) . '-wrap" class="lps-top-section-wrap">';
					$forced_end = '</div>';
				}
			}

			if ( empty( $args['pagespos'] ) || ( ! empty( $args['pagespos'] ) && 2 === (int) $args['pagespos'] ) ) {
				echo $pagination_html; // WPCS: XSS OK.
			}
		}
		if ( ! empty( $posts ) ) {
			if ( in_array( 'date', $extra_display, true ) ) {
				$date_format = get_option( 'date_format' ) . ' \<\i\>' . get_option( 'time_format' ) . '\<\/\i\>';
			}

			$class = ( ! empty( $args['css'] ) ) ? ' ' . $args['css'] : '';
			if ( in_array( 'ajax_pagination', $show_extra, true ) ) {
				$class .= ' ajax_pagination';
			}

			$use_custom_markup = false;
			if ( '_custom_' === substr( $tile_pattern, 1, 8 ) ) {
				$use_custom_markup = true;
			}

			if ( $use_custom_markup ) {
				$start = apply_filters( 'lps_filter_use_custom_section_markup_start', $tile_pattern, $shortcode_id, $class );
				if ( ! substr_count( $start, esc_attr( $shortcode_id ) ) ) {
					$start       = '<div id="' . esc_attr( $shortcode_id ) . '" class="' . trim( esc_attr( $class ) ) . '">' . $start;
					$forced_end .= '</div>';
				}
				echo $start; // WPCS: XSS OK.
			} else {
				echo '<section class="latest-post-selection' . esc_attr( $class ) . '" id="' . esc_attr( $shortcode_id ) . '">';
				if ( ! empty( $args['image'] ) ) {
					$iw = get_option( esc_attr( $args['image'] ) . '_size_w', true );
					$iw = ( 1 === $iw ) ? '100%' : $iw . 'px';
					$ih = get_option( esc_attr( $args['image'] ) . '_size_h', true );
					$ih = ( 1 === $ih ) ? '100%' : $ih . 'px';
					echo '<style>#' . esc_attr( $shortcode_id ) . ' .custom-' . esc_attr( $args['image'] ) . ' { width:' . $iw . '; min-height:' . $ih . '; height:auto;}</style>'; // WPCS: XSS OK.
				}
			}

			$tile_pattern = self::positions_from_extra( $show_extra, $tile_pattern, $args, $extra_display );

			$markup_sep     = '#7$*#';
			$tile_keep_tags = array();
			foreach ( self::$title_tags as $k ) {
				$tile_keep_tags[ $k ] = array();
			}
			$tile_keep_tags[ $titletag ] = array( 'class' );
			$tile_keep_tags['br'] = array();

			global $last_tiles_img;
			foreach ( $posts as $post ) {
				// Collect the IDs for the current page from the shortcode results.
				array_push( $lps_current_post_embedded_item_ids, $post->ID );
				$tile = $tile_pattern;

				if ( $use_custom_markup ) {
					echo apply_filters( 'lps_filter_use_custom_tile_markup', $tile_pattern, $post, $args ); // WPCS: XSS OK.
				} else {
					$a_start   = '';
					$ar_start  = '';
					$a_end     = '';
					$title_str = self::cleanup_title( $post->post_title );

					if ( $linkurl || $linkmedia || substr_count( $class, 'as-overlay' ) ) {
						$link_target  = ( ! empty( $linkblank ) ) ? ' target="_blank"' : '';
						if ( $linkmedia ) {
							$mediaurl = wp_get_attachment_image_src( $post->ID, $lightbox_size );
							$mediaurl = ( ! empty( $mediaurl[0] ) ) ? $mediaurl[0] : '';
							$hr       = ( ! empty( $mediaurl ) ) ? ' href="' . esc_url( $mediaurl ) . '"' : '';
						} else {
							$hr = ( ! empty( $linkurl ) ) ? ' href="' . esc_url( get_permalink( $post->ID ) ) . '"' : '';
						}

						if ( ! empty( $lightbox_attr ) ) {
							$hr .= ' rel="' . $shortcode_id . '"';
						}

						$a_start  = '<a' . $hr . $link_class . $lightbox_extra . $link_target . ' title="' . esc_attr( $title_str ) . '">';
						$ar_start = '<a' . $hr . $read_more_class . $lightbox_extra . $link_target . ' title="' . esc_attr( $title_str ) . '">';
						$a_end    = '</a>';
					}

					$tile = str_replace( '[a]', $a_start, $tile );
					$tile = str_replace( '[a-r]', $ar_start, $tile );
					$tile = str_replace( '[/a]', $a_end, $tile );

					// Tile replace image markup.
					$tile = self::set_tile_image( $post, $args, $tile );

					// Tile date markup.
					if ( in_array( 'date', $extra_display, true ) ) {
						if ( in_array( 'date_diff', $show_extra, true ) ) {
							$date_value = self::relative_time( $post->ID );
						} else {
							$date_value = date_i18n( $date_format, strtotime( $post->post_date ), true );
						}
						$tile = str_replace( '[date]', $markup_sep . '<em class="item-date">' . $date_value . '</em>', $tile );
					}
					$tile = str_replace( '[date]', '', $tile );

					// Tile tags markup.
					if ( in_array( 'tags', $show_extra, true ) ) {
						$tags = apply_filters( 'the_tags', get_the_term_list( $post->ID, 'post_tag', '<div class="lps-terms tags">', ', ', '</div>' ), '<div class="lps-terms tags">', ', ', '</div>', $post->ID );
						if ( ! empty( $tags ) ) {
							$tags = $markup_sep . '<span class="lps-tags-wrap">' . $tags . '</span>';
							$tile = str_replace( '[tags]', $tags, $tile );
						}
					}
					$tile = str_replace( '[tags]', '', $tile );

					// Tile author markup.
					if ( in_array( 'author', $show_extra, true ) ) {
						$author = $markup_sep . '<div class="lps-author-wrap"><span class="lps-author">' . esc_html__( 'By', 'lps' ) . '</span> <a href="' . esc_url( get_author_posts_url( $post->post_author ) ) . '" class="lps-author-link">' . esc_html( get_the_author_meta( 'display_name', $post->post_author ) ) . '</a></div>';
						$tile   = str_replace( '[author]', $author, $tile );
					}
					$tile = str_replace( '[author]', '', $tile );

					$mime_css = '';
					if ( 'attachment' === $post->post_type ) {
						// Attachment tile mime type markup.
						if ( in_array( 'show_mime', $show_extra, true ) ) {
							$mime     = trim( strstr( $post->post_mime_type, '/' ), '/' );
							$mime_css = 'item-mime-type mime-' . esc_attr( $mime ) . ' mime-' . str_replace( '/', '-', esc_attr( $post->post_mime_type ) );

							$tile = str_replace( '[show_mime]', '<span class="' . $mime_css . '"><span>' . esc_html__( 'Mime Type', 'lps' ) . ':</span> ' . $mime . '</span>', $tile );
						}

						// Maybe prepare the mime type class.
						if ( in_array( 'show_mime_class', $show_extra, true ) ) {
							if ( empty( $mime_css ) ) {
								$mime     = trim( strstr( $post->post_mime_type, '/' ), '/' );
								$mime_css = 'item-mime-type mime-' . esc_attr( $mime ) . ' mime-' . str_replace( '/', '-', esc_attr( $post->post_mime_type ) );
							}
						} else {
							$mime_css = '';
						}

						// Attachment tile caption type markup.
						if ( in_array( 'caption', $show_extra, true ) ) {
							$caption = wp_get_attachment_caption( $post->ID );
							if ( ! empty( $caption ) ) {
								$caption = $markup_sep . '<div class="lps-caption-wrap"><span>' . esc_html__( 'Caption', 'lps' ) . ':</span> ' . esc_html( $caption ) . '</div>';
							}
							$tile    = str_replace( '[caption]', $caption, $tile );
						}
					}
					$tile = str_replace( '[show_mime]', '', $tile );
					$tile = str_replace( '[caption]', '', $tile );

					// Tile taxonomies markup.
					$taxonomies = array_diff( $show_extra, array( 'tags', 'author', 'show_mime', 'caption', 'ajax_pagination', 'hide_uncategorized_category' ) );
					if ( ! empty( $taxonomies ) ) {
						foreach ( $taxonomies as $tax ) {
							$terms   = '';
							$tax_obj = get_taxonomy( $tax );
							if ( ! empty( $tax_obj ) ) {
								$terms_list = get_the_term_list( $post->ID, $tax, '<span class="lps-terms ' . esc_attr( $tax ) . '">', ', ', '</span>' );
								if ( 'category' === $tax && in_array( 'hide_uncategorized_category', $show_extra, true ) ) {
									if ( substr_count( strtolower( $terms_list ), 'uncategorized' ) ) {
										$terms_list = '';
									}
								}
								if ( ! empty( $terms_list ) ) {
									$terms = '<div class="lps-taxonomy-wrap ' . esc_attr( $tax ) . '"><span class="lps-taxonomy ' . esc_attr( $tax ) . '">' . esc_html( $tax_obj->label ) . ':</span> ' . $terms_list . '</div>';
								}
							}
							$tile = str_replace( '[' . $tax . ']', $markup_sep . $terms, $tile );
						}
					}

					// Tile title markup.
					if ( in_array( 'title', $extra_display, true ) ) {
						$tile = str_replace( '[title]', '<' . $titletag . ' class="item-title-tag">' . $title_str . '</' . $titletag . '>', $tile );
					}
					$tile = str_replace( '[title]', '', $tile );

					// Tile text markup.
					$text = '';
					if ( ! empty( $args['display'] )
						&& ( substr_count( $args['display'], 'content' ) || substr_count( $args['display'], 'excerpt' ) ) ) {
						$text = self::compute_tile_text( $post, $extra_display, $chrlimit, $raw_content );
					}
					$tile = str_replace( '[text]', $markup_sep . $text, $tile );

					if ( ! empty( $linktext ) ) {
						$tile = str_replace( '[read_more_text]', $markup_sep . '<span class="read-more">' . $linktext . '</span>', $tile );
					} else {
						$tile = str_replace( '[read_more_text]', '', $tile );
					}

					// Cleanup the remanining tags.
					$tile = preg_replace( '/\[(.*)\]/', '', $tile );

					$mime_class = ( ! empty( $mime_css ) ) ? ' class="' . $mime_css . '"' : '';
					if ( substr_count( $class, 'as-overlay' ) ) {
						if ( ! empty( $last_tiles_img ) ) {
							$last_tiles_img = esc_url( $last_tiles_img );
						}
						$maybe_tile = $tile;
						$maybe_tile = str_replace( $markup_sep, ' ', $maybe_tile );
						$maybe_tile = wp_kses( $maybe_tile, $tile_keep_tags );

						echo '<article ' . $mime_class . ' style="background-image:url(\'' . $last_tiles_img . '\')"><div class="lps-ontopof-overlay">' . $a_start . $maybe_tile . $a_end . '</div><div class="clear"></div></article>'; // WPCS: XSS OK.
					} else {
						$tile = str_replace( $markup_sep, '', $tile );
						echo '<article' . $mime_class . '>' . $tile . '<div class="clear"></div></article>'; // WPCS: XSS OK.
					}
				}
			}

			if ( $use_custom_markup ) {
				echo apply_filters( 'lps_filter_use_custom_section_markup_end', $tile_pattern, $shortcode_id, $class ); // WPCS: XSS OK.
				if ( ! empty( $forced_end ) ) {
					echo $forced_end; // WPCS: XSS OK.
				}
			} else {
				echo '</section>';
			}
		}
		if ( ! empty( $qargs['posts_per_page'] ) && ! empty( $args['showpages'] ) ) {
			if ( ! empty( $args['pagespos'] ) && ( 1 === (int) $args['pagespos'] || 2 === (int) $args['pagespos'] ) ) {
				if ( in_array( 'linebreak', $show_extra, true ) ) {
					echo '<div class="clear"></div>';
				}
				echo $pagination_html; // WPCS: XSS OK.
			}
			if ( in_array( 'ajax_pagination', $show_extra, true ) && ! $is_lps_ajax && ! empty( $args ) && is_array( $args ) ) {
				echo '</div>';
			}
		}

		if ( ! $is_lps_ajax && ! empty( $args ) && is_array( $args ) && in_array( 'linebreak', $show_extra, true ) ) {
			echo '<div class="clear"></div>';
		}

		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * Alter the query where for attachment use.
	 *
	 * @param  string $where The where statement.
	 * @param  object $obj   The query object.
	 * @return string
	 */
	public static function attachment_custom_where( $where, $obj ) {
		global $wpdb;
		if ( is_scalar( self::$current_query_statuses_list ) ) {
			$list = explode( ',', self::$current_query_statuses_list );
		} else {
			$list = self::$current_query_statuses_list;
		}
		$que = '';
		foreach ( $list as $k => $value ) {
			$que  .= ( ! empty( $que ) ) ? ' OR ' : '';
			$que  .= 'p2.post_status = \'' . $value . '\'';
			$where = str_replace( 'AND (p2.post_status = \'' . $value . '\')', '', $where );
			$where = str_replace( ' OR p2.post_status = \'' . $value . '\'', '', $where );
			$where = str_replace( 'p2.post_status = \'' . $value . '\'', '', $where );
		}
		$where = str_replace( ' AND ()', '', $where );
		$where = str_replace( 'AND (' . $que . ')', '', $where );

		return $where;
	}

	/**
	 * Alter the query join for attachment use.
	 *
	 * @param  string $join The join statement.
	 * @param  object $obj  The query object.
	 * @return string
	 */
	public static function attachment_custom_join( $join, $obj ) {
		global $wpdb;
		$join = str_replace( 'LEFT JOIN ' . $wpdb->posts . ' AS p2 ON (' . $wpdb->posts . '.post_parent = p2.ID) ', '', $join );
		return $join;
	}

	/**
	 * Return empty for the attachment paragraph that embeds the image in the content.
	 *
	 * @param  string $p The paragraph.
	 * @return string
	 */
	public static function remove_attachment_content_p( $p ) {
		return '';
	}

	/**
	 * Compute a post usable excerpt.
	 *
	 * @param  object  $post The post object.
	 * @param  boolean $raw  Use or not raw content.
	 * @return string
	 */
	public static function maybe_post_excerpt( $post, $raw = false ) {
		if ( $raw ) {
			$excerpt = wp_kses_post( strip_shortcodes( $post->post_excerpt ) );
		} else {
			$excerpt = apply_filters( 'the_excerpt', strip_shortcodes( $post->post_excerpt ) );
		}
		return $excerpt;
	}

	/**
	 * Compute a post usable content.
	 *
	 * @param  object  $post The post object.
	 * @param  boolean $raw  Use or not raw content.
	 * @return string
	 */
	public static function maybe_post_content( $post, $raw = false ) {
		if ( $raw ) {
			$content = wp_kses_post( $post->post_content );
		} else {
			$content = apply_filters( 'the_content', $post->post_content );
		}

		return $content;
	}

	/**
	 * Compute a item text.
	 *
	 * @param  object  $post  The post object.
	 * @param  array   $extra The elements display list.
	 * @param  integer $limit Chars limit.
	 * @param  boolean $raw   Use or not raw content.
	 * @return string
	 */
	public static function compute_tile_text( $post, $extra = array(), $limit, $raw ) {
		if ( 'attachment' === $post->post_type ) {
			add_filter( 'prepend_attachment', array( get_called_class(), 'remove_attachment_content_p' ) );
		}

		if ( in_array( 'excerpt-small', $extra, true ) ) {
			return self::get_short_text( $post->post_excerpt, $limit, true );
		} elseif ( in_array( 'excerpt', $extra, true ) ) {
			return self::maybe_post_excerpt( $post );
		} elseif ( in_array( 'content', $extra, true ) ) {
			return self::maybe_post_content( $post, $raw );
		} elseif ( in_array( 'content-small', $extra, true ) ) {
			if ( $raw ) {
				return wp_kses_post( self::trim_html_to_length( $post->post_content, $limit ) );
			} else {
				return self::get_short_text( $post->post_content, $limit, false );
			}
		} elseif ( in_array( 'excerptcontent', $extra, true ) ) {
			return '<div class="lps-excerpt">' . self::maybe_post_excerpt( $post, $raw ) . '</div><div class="lps-content">' . self::maybe_post_content( $post, $raw ) . '</div>';
		} elseif ( in_array( 'contentexcerpt', $extra, true ) ) {
			return '<div class="lps-content">' . self::maybe_post_content( $post, $raw ) . '</div><div class="lps-excerpt">' . self::maybe_post_excerpt( $post, $raw ) . '</div>';
		}
		return '';
	}

	/**
	 * Trim a HTML string to length, keeping the tags.
	 *
	 * @param  string  $title   String to be trimmed.
	 * @param  integer $max_len Max chars.
	 * @param  string  $end     The ending string.
	 * @return string
	 */
	public static function trim_html_to_length( $title, $max_len = 30, $end = '...' ) {
		$current_len = 0;

		$title = strip_shortcodes( $title );
		$title = str_replace( '&nbsp;', ' ', $title );
		$title = preg_replace( '/\s\s+/', ' ', trim( $title ) );
		$title = preg_replace( '/<!--(.*?)-->/', '', $title );
		$title = html_entity_decode( $title );
		if ( strlen( $title ) <= $max_len ) {
			return $title;
		}

		$words_tags = preg_split( '/(<[^>]*[^\/]>)|(<[^>]*\/>)/i', $title, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
		$new_title  = '';
		$text       = '';
		$starts     = array();
		$ends       = array();
		if ( ! empty( $words_tags ) ) {
			foreach ( $words_tags as $elem ) {
				$current_len = strlen( $new_title );
				$remaining   = $max_len - $current_len;
				if ( $remaining <= 0 ) {
					// Stop here, do not iterate further.
					break;
				}

				if ( '</' === substr( $elem, 0, 2 ) ) {
					// Tag ending.
					array_unshift( $ends, $elem );
					// $text   = trim( $text );
					$text  .= $elem;
					$maybes = rtrim( ltrim( $elem, '</' ), '>' );
					if ( ! empty( $starts ) ) {
						if ( substr( reset( $starts ), 0, strlen( $maybes ) + 1 ) === '<' . $maybes ) {
							// This is closed now, remove the most recent starting match.
							array_shift( $starts );
						}
					}
				} elseif ( '<' === substr( $elem, 0, 1 ) ) {
					// Tag start, append it at the beginning of stack.
					array_unshift( $starts, $elem );
					$text .= $elem;
				} else {
					// Text.
					if ( strlen( $elem ) > $remaining ) {
						$pos = stripos( $elem, ' ', $remaining );
						if ( ! empty( $pos ) ) {
							$elem = trim( substr( $elem, 0, $pos ) );
						} else {
							$w = explode( ' ', $elem );
							$el = '';
							foreach ( $w as $wk ) {
								if ( strlen( $el ) >= $remaining ) {
									break;
								}
								$el .= ' ' . $wk;
							}
							$elem = trim( $el );
						}
					}
					$new_title .= $elem . ' ';
					$text      .= $elem . ' ';
				}
			}
		}

		// Remove the trailing punctuation if possible.
		$text = trim( $text );
		$text = preg_replace( '/\PL\z/', '', $text );
		$text = trim( $text );

		if ( ! empty( $end ) ) {
			$text .= $end;
		}
		if ( ! empty( $starts ) ) {
			$starts = implode( '', $starts );
			$text  .= str_replace( '<', '</', $starts );
		}

		return $text;
	}

	/**
	 * Compute the position for the extra elements.
	 *
	 * @param string $show_extra    The extra element list.
	 * @param string $tile_pattern  The tile pattern.
	 * @param array  $args          The shortcode arguments.
	 * @param array  $extra_display The extra elements to be shown.
	 * @return string
	 */
	public static function positions_from_extra( $show_extra = '', $tile_pattern = '', $args = array(), $extra_display = array() ) {
		if ( in_array( 'date', $extra_display, true ) ) {
			if ( in_array( 'title', $extra_display, true ) ) {
				if ( ! empty( $args['display'] ) && substr_count( $args['display'], 'date,title' ) ) {
					$tile_pattern = str_replace( '[title]', '[date][title]', $tile_pattern );
				} else {
					$tile_pattern = str_replace( '[title]', '[title][date]', $tile_pattern );
				}
			} else {
				$tile_pattern = str_replace( '[title]', '[date]', $tile_pattern );
			}
		}

		if ( ! is_array( $show_extra ) ) {
			$show_extra = explode( ',', $show_extra );
		}
		if ( ! empty( $show_extra ) ) {
			foreach ( $show_extra as $extra_tag ) {
				if ( substr_count( $extra_tag, 'taxpos_' ) ) {
					preg_match_all( '/taxpos\_(.*)\_(before|after)\-(.*)/', $extra_tag, $matches );
					if ( ! empty( $matches[1][0] ) && in_array( $matches[1][0], $show_extra, true )
						&& ! empty( $matches[2][0] ) ) {
						if ( 'before' === $matches[2][0] ) {
							$tile_pattern = str_replace( '[' . $matches[3][0] . ']', '[' . $matches[1][0] . '][' . $matches[3][0] . ']', $tile_pattern );
						} else {
							$tile_pattern = str_replace( '[' . $matches[3][0] . ']', '[' . $matches[3][0] . '][' . $matches[1][0] . ']', $tile_pattern );
						}
					}
				}
			}
		}

		// Set the default positions for.
		foreach ( self::$replaceable_tags as $tag ) {
			if ( ! substr_count( $tile_pattern, '[' . $tag . ']' ) ) {
				$tile_pattern = str_replace( '[text]', '[text][' . $tag . ']', $tile_pattern );
			}
		}

		return $tile_pattern;
	}

	/**
	 * Clean the tile title.
	 *
	 * @param string $str The string.
	 * @return string
	 */
	public static function cleanup_title( $str ) {
		return ( ! empty( $str ) ) ? str_replace( ']', '', str_replace( '[', '', $str ) ) : '';
	}

	/**
	 * Compute the tile image for a post, based on the arguments.
	 *
	 * @param object $post The WP_Post object.
	 * @param array  $args The shortcode arguments.
	 * @param string $tile The tile pattern.
	 * @return string
	 */
	public static function set_tile_image( $post, $args, $tile ) {
		global $last_tiles_img;
		if ( empty( $post ) ) {
			return;
		}
		$last_tiles_img = '';

		// Tile image markup.
		if ( ! empty( $args['image'] ) ) {
			$img_html = '';
			$attr     = array(
				'class' => 'lps-custom-' . $args['image'],
			);

			if ( 'attachment' === $post->post_type ) {
				$th_id       = $post->ID;
				$attr['alt'] = get_post_meta( $th_id, '_wp_attachment_image_alt', true );
				if ( empty( $attr['alt'] ) ) {
					$attr['alt'] = self::cleanup_title( $post->post_title );
				}
			} else {
				$th_id       = get_post_thumbnail_id( (int) $post->ID );
				$attr['alt'] = self::cleanup_title( $post->post_title );
			}

			$image     = wp_get_attachment_image_src( $th_id, $args['image'] );
			$img_url   = '';
			$is_native = false;
			if ( ! empty( $image[0] ) ) {
				$img_url        = $image[0];
				$is_native      = true;
				$attr['width']  = $image[1];
				$attr['height'] = $image[2];
			} elseif ( ! empty( $args['image_placeholder'] ) ) {
				$img_url = $args['image_placeholder'];
			}

			if ( ! empty( $img_url ) ) {
				if ( true === $is_native ) {
					$srcset = wp_get_attachment_image_srcset( $th_id, $args['image'] );
					if ( ! empty( $srcset ) ) {
						$attr['srcset'] = $srcset;
					}
				}

				$attributes = '';
				foreach ( $attr as $k => $v ) {
					$attributes .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
				}
				$image_meta = wp_get_attachment_metadata( $th_id );
				$img_html   = '<img src="' . esc_url( $img_url ) . '" ' . $attributes . '">';

				$last_tiles_img = $img_url;
			}
			$tile = str_replace( '[image]', $img_html, $tile );
		}
		$tile = str_replace( '[image]', '', $tile );
		return $tile;
	}

	/**
	 * Generate the slider output from the selected posts and shortcode settings.
	 *
	 * @param array $posts List of WP_Post objects.
	 * @param array $args  Shortcode settings.
	 * @return void
	 */
	public static function latest_selected_content_slider( $posts, $args ) {
		if ( empty( $posts ) ) {
			return;
		}
		// Enqueue one time the slider assets.
		self::load_slider_assets();
		$shortcode_id = md5( serialize( $args ) . microtime() );

		$css     = ( ! empty( $args['css'] ) ) ? $args['css'] : '';
		$imgsize = ( empty( $args['image'] ) ) ? 'none' : $args['image'];
		$url     = ( ! empty( $args['url'] ) && substr_count( $args['url'], 'yes' ) ) ? true : false;
		$height  = ( ! empty( $args['slidermaxheight'] ) ) ? (int) $args['slidermaxheight'] : 0;
		if ( empty( $height ) && 'none' === $imgsize ) {
			$height = 100;
		}
		$mode     = ( ! empty( $args['slidermode'] ) ) ? $args['slidermode'] : 'horizontal';
		$auto     = ( ! empty( $args['sliderauto'] ) && 'true' === $args['sliderauto'] ) ? 'true' : 'false';
		$speed    = ( ! empty( $args['sliderspeed'] ) ) ? (int) $args['sliderspeed'] : 1000;
		$ctrl     = ( ! empty( $args['slidercontrols'] ) && 'true' === $args['slidercontrols'] ) ? 'true' : 'false';
		$slides   = ( ! empty( $args['slideslides'] ) ) ? (int) $args['slideslides'] : 1;
		$scroll   = ( ! empty( $args['slidescroll'] ) ) ? (int) $args['slidescroll'] : 1;
		$dots     = ( ! empty( $args['sliderdots'] ) && 'true' === $args['sliderdots'] ) ? 'true' : 'false';
		$inf      = ( ! empty( $args['sliderinfinite'] ) ) ? 'true' : 'false';
		$t_bp     = ( ! empty( $args['sliderbreakpoint_tablet'] ) ) ? (int) $args['sliderbreakpoint_tablet'] : 600;
		$t_slides = ( ! empty( $args['slideslides_tablet'] ) ) ? (int) $args['slideslides_tablet'] : 1;
		$t_scroll = ( ! empty( $args['slidescroll_tablet'] ) ) ? (int) $args['slidescroll_tablet'] : 1;
		$t_dots   = ( ! empty( $args['sliderdots_tablet'] ) && 'true' === $args['sliderdots_tablet'] ) ? 'true' : 'false';
		$t_inf    = ( ! empty( $args['sliderinfinite_tablet'] ) ) ? 'true' : 'false';
		$m_bp     = ( ! empty( $args['sliderbreakpoint_mobile'] ) ) ? (int) $args['sliderbreakpoint_mobile'] : 460;
		$m_slides = ( ! empty( $args['slideslides_mobile'] ) ) ? (int) $args['slideslides_mobile'] : 1;
		$m_scroll = ( ! empty( $args['slidescroll_mobile'] ) ) ? (int) $args['slidescroll_mobile'] : 1;
		$m_dots   = ( ! empty( $args['sliderdots_mobile'] ) && 'true' === $args['sliderdots_mobile'] ) ? 'true' : 'false';
		$m_inf    = ( ! empty( $args['sliderinfinite_mobile'] ) ) ? 'true' : 'false';
		$extra    = ( ! empty( $args['display'] ) ) ? explode( ',', $args['display'] ) : array( 'title' );
		$chrlimit = ( ! empty( $args['chrlimit'] ) ) ? intval( $args['chrlimit'] ) : 120;
		$overlay  = ( ! empty( $args['slideoverlay'] ) && 'no' === $args['slideoverlay'] ) ? false : true;
		$otype    = '';
		if ( true === $overlay ) {
			$otype = ( ! empty( $args['slideoverlay'] ) ) ? $args['slideoverlay'] : 'all';
		}
		$gaps   = ( ! empty( $args['slidegap'] ) ) ? (int) $args['slidegap'] : 0;
		$center = ( ! empty( $args['centermode'] ) && 'true' === $args['centermode'] ) ? true : false;
		$padd   = ( ! empty( $args['centerpadd'] ) ) ? (int) $args['centerpadd'] : 0;
		$resp   = ( ! empty( $args['slidersponsive'] ) && 'yes' === $args['slidersponsive'] ) ? true : false;
		$respto = ( ! empty( $args['respondto'] ) && in_array( $args['respondto'], array( 'window', 'slider' ), true ) ) ? $args['respondto'] : 'min';

		ob_start();
		?>
		<style>
			#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> {
				display: none;
			}
			<?php if ( ! empty( $height ) ) : ?>
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> > div,
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .img-wrap,
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .slick-slide {
					max-height: <?php echo esc_attr( $height ); ?>px;
					overflow: hidden;
				}
			<?php endif; ?>
			<?php if ( ! empty( $gaps ) ) : ?>
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .slick-slide {
					margin: 0 <?php echo (int) $gaps; ?>px;
				}
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .slick-list {
					margin: 0 -<?php echo (int) $gaps; ?>px;
				}
			<?php endif; ?>
			<?php if ( ! empty( $center ) ) : ?>
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .slick-slide {
					margin: 0px;
					padding: <?php echo (int) $padd; ?>px;
					position: relative;
				}
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .slick-slide .overlay {
					max-width: calc(100% - <?php echo 2 * (int) $padd; ?>px);
					margin-left: 0px;
					bottom: <?php echo (int) $padd; ?>px;
					display: none;
				}
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .slick-center .overlay {
					max-width: calc(100%);
					margin-left: -<?php echo (int) $padd; ?>px;
					bottom: <?php echo (int) $padd; ?>px;
					display: block;
				}
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .slick-center .img-wrap {
					min-width: calc(100% + <?php echo 2 * (int) $padd; ?>px);
					max-height: auto;
					height: auto;
					margin-left: -<?php echo (int) $padd; ?>px;
					margin-top: -<?php echo (int) $padd; ?>px;
				}
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .slick-prev {
					left: <?php echo 2 * (int) $padd; ?>px;
				}
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .slick-next {
					right: <?php echo 2 * (int) $padd; ?>px;
				}
			<?php endif; ?>
			<?php if ( 'true' === $dots ) : ?>
				#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .slick-dots {
					bottom: -30px;
				}
			<?php endif; ?>
		</style>
		<div class="latest-post-selection-slider-wrap">
			<div class="latest-post-selection-slider <?php echo esc_attr( $css ); ?>" id="latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?>">
				<?php
				foreach ( $posts as $post ) :
					setup_postdata( $post );
					if ( ! empty( $imgsize ) ) :
						if ( 'none' === $imgsize ) {
							$image[0] = plugins_url( '/assets/images/slider-default.png', __FILE__ );
						} else {
							$th_id = ( 'attachment' === $post->post_type ) ? (int) $post->ID : get_post_thumbnail_id( (int) $post->ID );
							$image = wp_get_attachment_image_src( $th_id, $imgsize );
						}
						if ( empty( $image[0] ) && ! empty( $args['image_placeholder'] ) ) {
							$image[0] = esc_attr( $args['image_placeholder'] );
						}
						if ( ! empty( $image[0] ) ) :
							$a_start   = '';
							$a_end     = '';
							$title_str = self::cleanup_title( $post->post_title );
							if ( $url ) {
								$link_target = ( 'yes_blank' === $args['url'] ) ? ' target="_blank"' : '';
								$a_start     = '<a href="' . get_permalink( $post->ID ) . '"' . $link_target . ' title="' . esc_attr( $title_str ) . '">';
								$a_end       = '</a>';
							}
							?>
							<div>
								<?php echo $a_start; // WPCS: XSS OK. ?>
								<div class="img-wrap"><img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_attr( $title_str ); ?>"></div>

								<?php if ( ! empty( $otype ) ) : ?>
									<div class="overlay">
										<?php if ( 'all' === $otype || 'title' === $otype ) : ?>
											<h3><?php echo esc_html( $title_str ); ?></h3>
										<?php endif; ?>
										<?php
										if ( 'all' === $otype || 'text' === $otype ) :
											$text = '';
											if ( in_array( 'excerpt', $extra, true )
												|| in_array( 'content', $extra, true )
												|| in_array( 'content-small', $extra, true )
												|| in_array( 'excerpt-small', $extra, true )
												|| 'all' === $otype ) :
												if ( in_array( 'excerpt', $extra, true ) ) {
													$text = apply_filters( 'the_excerpt', strip_shortcodes( get_the_excerpt( $post ) ) );
												} elseif ( in_array( 'excerpt-small', $extra, true )
													|| 'all' === $otype ) {
													$text = self::get_short_text( get_the_excerpt( $post ), $chrlimit, true );
												} elseif ( in_array( 'content', $extra, true ) ) {
													$text = apply_filters( 'the_content', $post->post_content );
												} elseif ( in_array( 'content-small', $extra, true ) ) {
													$text = self::get_short_text( $post->post_content, $chrlimit, false );
												}
												echo esc_html( strip_tags( $text ) );
											endif;
										endif;
										?>
									</div>
								<?php endif; ?>
								<?php echo $a_end; // WPCS: XSS OK. ?>
							</div>
							<?php
						endif;
					endif;
				endforeach;
				?>
			</div>
		</div>
		<script>
		jQuery(document).ready(function(){
			jQuery('#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?>').slick({
				<?php if ( 'vertical' === $mode ) : ?>
					vertical: true,
				<?php elseif ( 'horizontal' === $mode ) : ?>
					vertical: false,
				<?php elseif ( 'fade' === $mode ) : ?>
					fade: true,
				<?php endif; ?>
				lazyLoad: 'progress',
				<?php if ( empty( $height ) ) : ?>
					adaptiveHeight: true,
				<?php else : ?>
					adaptiveHeight: false,
				<?php endif; ?>
				rows: 1,
				draggable: true,
				accessibility: true,
				autoplay: <?php echo esc_attr( $auto ); ?>,
				autoplaySpeed: <?php echo (int) $speed; ?>,
				speed: 300,
				pauseOnFocus: true,
				pauseOnHover: true,
				pauseOnDotsHover: true,
				slidesToShow: <?php echo (int) $slides; ?>,
				slidesToScroll: <?php echo (int) $scroll; ?>,
				infinite: <?php echo esc_attr( $inf ); ?>,
				dots: <?php echo esc_attr( $dots ); ?>,
				arrows: <?php echo esc_attr( $ctrl ); ?>,
				<?php if ( true === $resp ) : ?>
					respondTo: '<?php echo esc_attr( $respto ); ?>',
					responsive: [{
						breakpoint: 1024,
						settings: {
							slidesToShow: <?php echo (int) $slides; ?>,
							slidesToScroll: <?php echo (int) $scroll; ?>,
							infinite: <?php echo esc_attr( $inf ); ?>,
							dots: <?php echo esc_attr( $dots ); ?>,
						}
					}, {
						breakpoint: <?php echo (int) $t_bp; ?>,
						settings: {
							slidesToShow: <?php echo (int) $t_slides; ?>,
							slidesToScroll: <?php echo (int) $t_scroll; ?>,
							infinite: <?php echo esc_attr( $t_inf ); ?>,
							dots: <?php echo esc_attr( $t_dots ); ?>,
						}
					},{
						breakpoint: <?php echo (int) $m_bp; ?>,
						settings: {
							slidesToShow: <?php echo (int) $m_slides; ?>,
							slidesToScroll: <?php echo (int) $m_scroll; ?>,
							infinite: <?php echo esc_attr( $m_inf ); ?>,
							dots: <?php echo esc_attr( $m_dots ); ?>,
						}
					}],
				<?php endif; ?>
				<?php if ( ! empty( $center ) ) : ?>
					centerMode: true,
					centerPadding: '<?php echo (int) $padd; ?>px',
				<?php endif; ?>
				zIndex: 1000
			});
			<?php if ( 'none' === $imgsize ) : ?>
				jQuery('#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?> .overlay').css({'height': <?php echo (int) $height; ?>});
			<?php endif; ?>
			<?php if ( 'true' === $auto ) : ?>
				jQuery('#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?>').on('mouseleave', function() {
					jQuery(this).slick('play');
				});
			<?php endif; ?>
			jQuery('#latest-post-selection-slider-<?php echo esc_attr( $shortcode_id ); ?>').show();
		});
		</script>
		<?php
		$slider = ob_get_clean();

		// Normalize newlines.
		$slider = preg_replace( '/(\r\n|\r|\n)+/', ' ', $slider );

		// Replace whitespace characters with a single space.
		$slider = preg_replace( '/\s+/', ' ', $slider );

		echo $slider; // WPCS: XSS OK.

		wp_reset_postdata();
	}

	/**
	 * Plugin action link.
	 *
	 * @param  array $links Plugin links.
	 * @return array
	 */
	public static function plugin_action_links( $links ) {
		$all   = array();
		$all[] = '<a href="https://iuliacazan.ro/latest-post-shortcode">' . esc_html__( 'Plugin URL', 'lps' ) . '</a>';
		$all   = array_merge( $all, $links );
		return $all;
	}

	/**
	 * The actions to be executed when the plugin is updated.
	 *
	 * @return void
	 */
	public static function plugin_ver_check() {
		$opt = str_replace( '-', '_', self::PLUGIN_TRANSIENT ) . '_db_ver';
		$dbv = get_option( $opt, 0 );
		if ( LPS_PLUGIN_VERSION !== (float) $dbv ) {
			update_option( $opt, LPS_PLUGIN_VERSION );
			self::activate_plugin();
		}
	}

	/**
	 * The actions to be executed when the plugin is activated.
	 *
	 * @return void
	 */
	public static function activate_plugin() {
		set_transient( self::PLUGIN_TRANSIENT, true );
	}

	/**
	 * The actions to be executed when the plugin is deactivated.
	 *
	 * @return void
	 */
	public static function deactivate_plugin() {
		self::plugin_admin_notices_cleanup( false );
	}

	/**
	 * Execute notices cleanup.
	 *
	 * @param  boolean $ajax Is AJAX call.
	 * @return void
	 */
	public static function plugin_admin_notices_cleanup( $ajax = true ) {
		// Delete transient, only display this notice once.
		delete_transient( self::PLUGIN_TRANSIENT );

		if ( true === $ajax ) {
			// No need to continue.
			wp_die();
		}
	}

	/**
	 * Admin notices.
	 *
	 * @return void
	 */
	public static function plugin_admin_notices() {
		$maybe_trans = get_transient( self::PLUGIN_TRANSIENT );
		if ( ! empty( $maybe_trans ) ) {
			?>
			<style>.notice.<?php echo esc_attr( self::PLUGIN_TRANSIENT ); ?>{background:rgba(62,76,183,0.2);border-left-color:rgb(62,76,183)}.notice.<?php echo esc_attr( self::PLUGIN_TRANSIENT ); ?> img{max-width: 100%}</style>
			<script>(function($) { $(document).ready(function() { var $notice = $('.notice.<?php echo esc_attr( self::PLUGIN_TRANSIENT ); ?>'); var $button = $notice.find('.notice-dismiss'); $notice.unbind('click'); $button.unbind('click'); $notice.on('click', '.notice-dismiss', function(e) { $.get( $notice.data('dismissurl') ); }); }); })(jQuery);</script>

			<div class="updated notice is-dismissible <?php echo esc_attr( self::PLUGIN_TRANSIENT ); ?>"
				data-dismissurl="<?php echo esc_url( admin_url( 'admin-ajax.php?action=lps-plugin-deactivate-notice' ) ); ?>">
				<p>
					<?php
					echo wp_kses_post( sprintf(
						// Translators: %1$s - image URL, %2$s - icon URL, %3$s - donate URL, %4$s - link style, %5$s - icon style, %6$s - rating.
						__( '<a href="%3$s" target="_blank"%4$s><img src="%1$s"></a><img src="%2$s"%5$s> <h3>Thank you for activating the plugin Latest Post Shortcode!</h3>If you find the plugin useful and would like to support my work, please consider making a <a href="%3$s" target="_blank">donation</a>. It would make me very happy if you would leave a %6$s rating.', 'lps' ) . ' ' . __( 'A huge thanks in advance!', 'lps' ),
						esc_url( plugin_dir_url( __FILE__ ) . '/assets/images/buy-me-a-coffee.png' ),
						esc_url( plugin_dir_url( __FILE__ ) . '/assets/images/icon-128x128.png' ),
						'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ&item_name=Support for development and maintenance (' . urlencode( self::PLUGIN_NAME ) . ')',
						' style="float:right; margin:20px"',
						' style="float:left; margin-right:20px; margin-top:10px; width:86px"',
						'<a href="' . self::PLUGIN_SUPPORT_URL . 'reviews/?rate=5#new-post" class="rating" target="_blank" title="' . esc_attr( 'A huge thanks in advance!', 'lps' ) . '">★★★★★</a>'
					) );
					?>
					<div class="clear"></div>
				</p>
			</div>
			<?php
		}
	}
}

// Instantiate the class.
$lps_instance = Latest_Post_Shortcode::get_instance();

// Register activation and deactivation actions.
register_activation_hook( __FILE__, array( $lps_instance, 'activate_plugin' ) );
register_deactivation_hook( __FILE__, array( $lps_instance, 'deactivate_plugin' ) );

// Allow the text widget to render the Latest Post Shortcode.
add_filter( 'widget_text', 'do_shortcode', 11 );

if ( class_exists( 'Elementor\\Plugin' ) ) {
	// Add Elementor support.
	require_once 'elementor/lps.php';
}
