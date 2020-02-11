<?php
/**
 * AxiomThemes Framework: Theme options manager
 *
 * @package	axiom
 * @since	axiom 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_options_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_options_theme_setup' );
	function axiom_options_theme_setup() {

		if ( is_admin() ) {
			// Add Theme Options in WP menu
			add_action('admin_menu', 								'axiom_options_admin_menu_item');

			if ( axiom_options_is_used() ) {
				// Make custom stylesheet when save theme options
				//add_filter("axiom_filter_save_options",		'axiom_options_save_stylesheet', 10, 2);

				// Ajax Save and Export Action handler
				add_action('wp_ajax_axiom_options_save', 		'axiom_options_save');
				add_action('wp_ajax_nopriv_axiom_options_save',	'axiom_options_save');

				// Ajax Import Action handler
				add_action('wp_ajax_axiom_options_import',		'axiom_options_import');
				add_action('wp_ajax_nopriv_axiom_options_import','axiom_options_import');

				// Prepare global variables
				global $AXIOM_GLOBALS;
				$AXIOM_GLOBALS['to_data'] = null;
				$AXIOM_GLOBALS['to_delimiter'] = ',';
			}
		}
		
	}
}


// Add 'Theme options' in Admin Interface
if ( !function_exists( 'axiom_options_admin_menu_item' ) ) {
	//add_action('admin_menu', 'axiom_options_admin_menu_item');
	function axiom_options_admin_menu_item() {
		// In this case menu item "Theme Options" add in admin menu 'Appearance'
		add_theme_page(__('Theme Options', 'axiom'), __('Theme Options', 'axiom'), 'edit_theme_options', 'axiom_options', 'axiom_options_page');
	
		// In this case menu item "Theme Options" add in root admin menu level
		//add_menu_page(__('AxiomThemes Options', 'axiom'), __('AxiomThemes Options', 'axiom'), 'manage_options', 'axiom_options', 'axiom_options_page');
	
		// In this case menu item "Theme Options" add in admin menu 'Settings'
		//add_options_page(__('AxiomThemes Options', 'axiom'), __('AxiomThemes Options', 'axiom'), 'manage_options', 'axiom_options', 'axiom_options_page');
	}
}



/* Theme options utils
-------------------------------------------------------------------- */

// Check if theme options are now used
if ( !function_exists( 'axiom_options_is_used' ) ) {
	function axiom_options_is_used() {
		$used = false;
		if (is_admin()) {
			if (isset($_REQUEST['action']) && ($_REQUEST['action']=='axiom_options_save' || $_REQUEST['action']=='axiom_options_import'))		// AJAX: Save or Import Theme Options
				$used = true;
			else if (axiom_strpos($_SERVER['REQUEST_URI'], 'axiom_options')!==false)															// Edit Theme Options
				$used = true;
			else if (axiom_strpos($_SERVER['REQUEST_URI'], 'post-new.php')!==false || axiom_strpos($_SERVER['REQUEST_URI'], 'post.php')!==false) {	// Create or Edit Post (page, product, ...)
				$post_type = axiom_admin_get_current_post_type();
				if (empty($post_type)) $post_type = 'post';
				$used = axiom_get_override_key($post_type, 'post_type')!='';
			} else if (axiom_strpos($_SERVER['REQUEST_URI'], 'edit-tags.php')!==false) {															// Edit Taxonomy
				$inheritance = axiom_get_theme_inheritance();
				if (!empty($inheritance)) {
					$post_type = axiom_admin_get_current_post_type();
					if (empty($post_type)) $post_type = 'post';
					foreach ($inheritance as $k=>$v) {
						if (!empty($v['taxonomy'])) {
							foreach ($v['taxonomy'] as $tax) {
								if ( axiom_strpos($_SERVER['REQUEST_URI'], 'taxonomy='.($tax))!==false && in_array($post_type, $v['post_type']) ) {
									$used = true;
									break;
								}
							}
						}
					}
				}
			} else if ( isset($_POST['meta_box_taxonomy_nonce']) ) {																				// AJAX: Save taxonomy
				$used = true;
			}
		} else {
			$used = (axiom_get_theme_option("allow_editor")=='yes' &&
						(
						(is_single() && current_user_can('edit_posts', get_the_ID())) 
						|| 
						(is_page() && current_user_can('edit_pages', get_the_ID()))
						)
					);
		}
		return apply_filters('axiom_filter_theme_options_is_used', $used);
	}
}


// Load all theme options
if ( !function_exists( 'axiom_load_main_options' ) ) {
	function axiom_load_main_options() {
		global $AXIOM_GLOBALS;
		$options = get_option('axiom_options', array());
		foreach ($AXIOM_GLOBALS['options'] as $id => $item) {
			if (isset($item['std'])) {
				if (isset($options[$id]))
					$AXIOM_GLOBALS['options'][$id]['val'] = $options[$id];
				else
					$AXIOM_GLOBALS['options'][$id]['val'] = $item['std'];
			}
		}
		// Call actions after load options
		do_action('axiom_action_load_main_options');
	}
}


// Get custom options arrays (from current category, post, page, shop, event, etc.)
if ( !function_exists( 'axiom_load_custom_options' ) ) {
	function axiom_load_custom_options() {
		global $wp_query, $post, $AXIOM_GLOBALS;

		$AXIOM_GLOBALS['custom_options'] = $AXIOM_GLOBALS['post_options'] = $AXIOM_GLOBALS['taxonomy_options'] = $AXIOM_GLOBALS['template_options'] = array();
		// Load template options
		$page_id = axiom_detect_template_page_id();
		if ( $page_id > 0 ) {
			$AXIOM_GLOBALS['template_options'] = get_post_meta($page_id, 'post_custom_options', true);
		}

		// Load taxonomy and post options
		$inheritance_key = axiom_detect_inheritance_key();
		if (!empty($inheritance_key)) {
			$inheritance = axiom_get_theme_inheritance($inheritance_key);
			// Load taxonomy options
			if (!empty($inheritance['taxonomy'])) {
				foreach ($inheritance['taxonomy'] as $tax) {
					$tax_obj = get_taxonomy($tax);
					$tax_query = !empty($tax_obj->query_var) ? $tax_obj->query_var : $tax;
					if ($tax == 'category' && is_category()) {		// Current page is category's archive (Categories need specific check)
						$tax_id = (int) get_query_var( 'cat' );
						if (empty($tax_id)) $tax_id = get_query_var( 'category_name' );
						$AXIOM_GLOBALS['taxonomy_options'] = axiom_taxonomy_get_inherited_properties('category', $tax_id);
						break;
					} else if ($tax == 'post_tag' && is_tag()) {	// Current page is tag's archive (Tags need specific check)
						$tax_id = get_query_var( $tax_query );
						$AXIOM_GLOBALS['taxonomy_options'] = axiom_taxonomy_get_inherited_properties('post_tag', $tax_id);
						break;
					} else if (is_tax($tax)) {						// Current page is custom taxonomy archive (All rest taxonomies check)
						$tax_id = get_query_var( $tax_query );
						$AXIOM_GLOBALS['taxonomy_options'] = axiom_taxonomy_get_inherited_properties($tax, $tax_id);
						break;
					}
				}
			}
			// Load post options
			if ( !empty($inheritance['post_type']) 
					&& is_singular() 
					&& ( in_array( get_query_var('post_type'), $inheritance['post_type']) 
						|| ( !empty($post->post_type) && in_array( $post->post_type, $inheritance['post_type']) )
						) 
					) {
				$post_id = get_the_ID();
				$AXIOM_GLOBALS['post_options'] = get_post_meta($post_id, 'post_custom_options', true);
				if (!empty($inheritance['taxonomy'])) {
					$tax_list = array();
					foreach ($inheritance['taxonomy'] as $tax) {
						$tax_terms = axiom_get_terms_by_post_id( array(
							'post_id'=>$post_id, 
							'taxonomy'=>$tax
							)
						);
						if (!empty($tax_terms[$tax]->terms)) {
							$tax_list[] = axiom_taxonomies_get_inherited_properties($tax, $tax_terms[$tax]);
						}
					}
					if (!empty($tax_list)) {
						foreach($tax_list as $tax_options) {
							if (!empty($tax_options)) {
								foreach($tax_options as $tk=>$tv) {
									if ( !isset($AXIOM_GLOBALS['taxonomy_options'][$tk]) || axiom_is_inherit_option($AXIOM_GLOBALS['taxonomy_options'][$tk]) ) {
										$AXIOM_GLOBALS['taxonomy_options'][$tk] = $tv;
									}
								}
							}
						}
					}
				}
			}
		}
		
		// Merge Template options with required for current page template
		$layout_name = axiom_get_custom_option(is_singular() && !axiom_get_global('blog_streampage') ? 'single_style' : 'blog_style');
		if (!empty($AXIOM_GLOBALS['registered_templates'][$layout_name]['theme_options'])) {
			$AXIOM_GLOBALS['template_options'] = array_merge($AXIOM_GLOBALS['template_options'], $AXIOM_GLOBALS['registered_templates'][$layout_name]['theme_options']);
		}
		
		do_action('axiom_action_load_custom_options');

		$AXIOM_GLOBALS['theme_options_loaded'] = true;

	}
}


// Get theme option. If not exists - try get site option. If not exist - return default
if ( !function_exists( 'axiom_get_theme_option' ) ) {
	function axiom_get_theme_option($option_name, $default = false, $options = null) {
		global $AXIOM_GLOBALS;
		static $axiom_options = false;
		$val = '';	//false;
		if (is_array($options)) {
			if (isset($option[$option_name])) {
				$val = $option[$option_name]['val'];
			}
		} else if (isset($AXIOM_GLOBALS['options'][$option_name]['val'])) { // if (isset($AXIOM_GLOBALS['options'])) {
			$val = $AXIOM_GLOBALS['options'][$option_name]['val'];
		} else {
			if ($axiom_options===false) $axiom_options = get_option('axiom_options', array());
			if (isset($axiom_options[$option_name])) {
				$val = $axiom_options[$option_name];
			} else if (isset($AXIOM_GLOBALS['options'][$option_name]['std'])) {
				$val = $AXIOM_GLOBALS['options'][$option_name]['std'];
			}
		}
		if ($val === '') {	//false) {
			if (($val = get_option($option_name, false)) !== false) {
				return $val;
			} else {
				return $default;
			}
		} else {
			return $val;
		}
	}
}


// Return property value from request parameters < post options < category options < theme options
if ( !function_exists( 'axiom_get_custom_option' ) ) {
	function axiom_get_custom_option($name, $defa=null, $post_id=0, $post_type='post', $tax_id=0, $tax_type='category') {
		if (isset($_GET[$name]))
			$rez = $_GET[$name];
		else {
			global $AXIOM_GLOBALS;
			$hash_name = ($name).'_'.($tax_id).'_'.($post_id);
			if (!empty($AXIOM_GLOBALS['theme_options_loaded']) && isset($AXIOM_GLOBALS['custom_options'][$hash_name])) {
				$rez = $AXIOM_GLOBALS['custom_options'][$hash_name];
			} else {
				if ($tax_id > 0) {
					$rez = axiom_taxonomy_get_inherited_property($tax_type, $tax_id, $name);
					if ($rez=='') $rez = axiom_get_theme_option($name, $defa);
				} else if ($post_id > 0) {
					$rez = axiom_get_theme_option($name, $defa);
					$custom_options = get_post_meta($post_id, 'post_custom_options', true);
					if (isset($custom_options[$name]) && !axiom_is_inherit_option($custom_options[$name])) {
						$rez = $custom_options[$name];
					} else {
						$terms = array();
						$tax = axiom_get_taxonomy_categories_by_post_type($post_type);
						$tax_obj = get_taxonomy($tax);
						$tax_query = !empty($tax_obj->query_var) ? $tax_obj->query_var : $tax;
						if ( ($tax=='category' && is_category()) || ($tax=='post_tag' && is_tag()) || is_tax($tax) ) {		// Current page is taxonomy's archive (Categories and Tags need specific check)
							$terms = array( get_queried_object() );
						} else {
							$taxes = axiom_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$tax));
							if (!empty($taxes[$tax]->terms)) {
								$terms = $taxes[$tax]->terms;
							}
						}
						$tmp = '';
						if (!empty($terms)) {
							for ($cc = 0; $cc < count($terms) && (empty($tmp) || axiom_is_inherit_option($tmp)); $cc++) {
								$tmp = axiom_taxonomy_get_inherited_property($terms[$cc]->taxonomy, $terms[$cc]->term_id, $name);
							}
						}
						if ($tmp!='') $rez = $tmp;
					}
				} else {
					$rez = axiom_get_theme_option($name, $defa);
					if (axiom_get_theme_option('show_theme_customizer') == 'yes' && axiom_get_theme_option('remember_visitors_settings') == 'yes' && function_exists('axiom_get_value_gpc')) {
						$tmp = axiom_get_value_gpc($name, $rez);
						if (!axiom_is_inherit_option($tmp)) {
							$rez = $tmp;
						}
					}
					if (isset($AXIOM_GLOBALS['template_options'][$name]) && !axiom_is_inherit_option($AXIOM_GLOBALS['template_options'][$name])) {
						$rez = is_array($AXIOM_GLOBALS['template_options'][$name]) ? $AXIOM_GLOBALS['template_options'][$name][0] : $AXIOM_GLOBALS['template_options'][$name];
					}
					if (isset($AXIOM_GLOBALS['taxonomy_options'][$name]) && !axiom_is_inherit_option($AXIOM_GLOBALS['taxonomy_options'][$name])) {
						$rez = $AXIOM_GLOBALS['taxonomy_options'][$name];
					}
					if (isset($AXIOM_GLOBALS['post_options'][$name]) && !axiom_is_inherit_option($AXIOM_GLOBALS['post_options'][$name])) {
						$rez = is_array($AXIOM_GLOBALS['post_options'][$name]) ? $AXIOM_GLOBALS['post_options'][$name][0] : $AXIOM_GLOBALS['post_options'][$name];
					}
				}
				$rez = apply_filters('axiom_filter_get_custom_option', $rez, $name);
				if (!empty($AXIOM_GLOBALS['theme_options_loaded'])) $AXIOM_GLOBALS['custom_options'][$hash_name] = $rez;
			}
		}
		return $rez;
	}
}


// Check option for inherit value
if ( !function_exists( 'axiom_is_inherit_option' ) ) {
	function axiom_is_inherit_option($value) {
		while (is_array($value)) {
			foreach ($value as $val) {
				$value = $val;
				break;
			}
		}
		return axiom_strtolower($value)=='inherit';	//in_array(axiom_strtolower($value), array('default', 'inherit'));
	}
}



/* Theme options manager
-------------------------------------------------------------------- */

// Load required styles and scripts for Options Page
if ( !function_exists( 'axiom_options_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'axiom_options_load_scripts');
	function axiom_options_load_scripts() {
		// WP Color Picker
		axiom_enqueue_style('wp-color-picker', false, array(), null);
		// AxiomThemes fontello styles
		axiom_enqueue_style( 'axiom-fontello-admin-style',	axiom_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null);
		axiom_enqueue_style( 'axiom-fontello-style', 			axiom_get_file_url('css/fontello/css/fontello.css'), array(), null);
		// AxiomThemes options styles
		axiom_enqueue_style('axiom-options-style',			axiom_get_file_url('core/core.options/css/core.options.css'), array(), null);
		axiom_enqueue_style('axiom-options-datepicker-style',	axiom_get_file_url('core/core.options/css/core.options-datepicker.css'), array(), null);

		// WP core scripts
		wp_enqueue_media();
		axiom_enqueue_script('wp-color-picker', false, array('jquery'), null, true);
		// Input masks for text fields
		axiom_enqueue_script( 'jquery-input-mask',				axiom_get_file_url('core/core.options/js/jquery.maskedinput.1.3.1.min.js'), array('jquery'), null, true );
		// AxiomThemes core scripts
		axiom_enqueue_script( 'axiom-core-utils-script',		axiom_get_file_url('js/core.utils.js'), array(), null, true );
		// AxiomThemes options scripts
		axiom_enqueue_script( 'axiom-options-script',			axiom_get_file_url('core/core.options/js/core.options.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-accordion', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-datepicker'), null, true );
		axiom_enqueue_script( 'axiom-options-custom-script',	axiom_get_file_url('core/core.options/js/core.options-custom.js'), array('axiom-options-script'), null, true );

		axiom_enqueue_messages();
		axiom_enqueue_popup();
	}
}


// Prepare javascripts global variables
if ( !function_exists( 'axiom_options_prepare_scripts' ) ) {
	//add_action("admin_head", 'axiom_options_prepare_scripts');
	function axiom_options_prepare_scripts($override='') {
		global $AXIOM_GLOBALS;
		if (empty($override)) $override = 'general';
		?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				AXIOM_GLOBALS['ajax_nonce'] 		= "<?php echo esc_attr(wp_create_nonce('ajax_nonce')); ?>";
				AXIOM_GLOBALS['ajax_url']		= "<?php echo esc_url(admin_url('admin-ajax.php')); ?>";
				AXIOM_GLOBALS['to_delimiter']	= "<?php echo esc_attr($AXIOM_GLOBALS['to_delimiter']); ?>";
				AXIOM_GLOBALS['to_popup']		= "<?php echo esc_attr(axiom_get_theme_option('popup_engine')); ?>";
				AXIOM_GLOBALS['to_override']		= "<?php echo esc_attr($override); ?>";
				AXIOM_GLOBALS['to_export_list']	= [<?php
					if (($export_opts = get_option('axiom_options_'.($override), false)) !== false) {
						$keys = join('","', array_keys($export_opts));
						if ($keys) echo '"'.($keys).'"';
					}
				?>];
				AXIOM_GLOBALS['to_strings'] = {
					del_item_error: 		"<?php _e("You can't delete last item! To disable it - just clear value in field.", 'axiom'); ?>",
					del_item:				"<?php _e("Delete item error!", 'axiom'); ?>",
					wait:					"<?php _e("Please wait!", 'axiom'); ?>",
					save_options:			"<?php _e("Options saved!", 'axiom'); ?>",
					reset_options:			"<?php _e("Options reset!", 'axiom'); ?>",
					reset_options_confirm:	"<?php _e("You really want reset all options to default values?", 'axiom'); ?>",
					export_options_header:	"<?php _e("Export options", 'axiom'); ?>",
					export_options_error:	"<?php _e("Name for options set is not selected! Export cancelled.", 'axiom'); ?>",
					export_options_label:	"<?php _e("Name for the options set:", 'axiom'); ?>",
					export_options_label2:	"<?php _e("or select one of exists set (for replace):", 'axiom'); ?>",
					export_options_select:	"<?php _e("Select set for replace ...", 'axiom'); ?>",
					export_empty:			"<?php _e("No exported sets for import!", 'axiom'); ?>",
					export_options:			"<?php _e("Options exported!", 'axiom'); ?>",
					export_link:			"<?php _e("If need, you can download the configuration file from the following link: %s", 'axiom'); ?>",
					export_download:		"<?php _e("Download theme options settings", 'axiom'); ?>",
					import_options_label:	"<?php _e("or put here previously exported data:", 'axiom'); ?>",
					import_options_label2:	"<?php _e("or select file with saved settings:", 'axiom'); ?>",
					import_options_header:	"<?php _e("Import options", 'axiom'); ?>",
					import_options_error:	"<?php _e("You need select the name for options set or paste import data! Import cancelled.", 'axiom'); ?>",
					import_options_failed:	"<?php _e("Error while import options! Import cancelled.", 'axiom'); ?>",
					import_options_broken:	"<?php _e("Attention! Some options are not imported:", 'axiom'); ?>",
					import_options:			"<?php _e("Options imported!", 'axiom'); ?>",
					import_dummy_confirm:	"<?php _e("Attention! During the import process, all existing data will be replaced with new.", 'axiom'); ?>",
					clear_cache:			"<?php _e("Cache cleared successfull!", 'axiom'); ?>",
					clear_cache_header:		"<?php _e("Clear cache", 'axiom'); ?>"
				};
			});
		</script>
		<?php 
	}
}


// Build the Options Page
if ( !function_exists( 'axiom_options_page' ) ) {
	function axiom_options_page() {
		global $AXIOM_GLOBALS;
	
		axiom_options_page_start();
	
		foreach ($AXIOM_GLOBALS['to_data'] as $id=>$field)
			axiom_options_show_field($id, $field);
	
		axiom_options_page_stop();
	}
}


// Start render the options page (initialize flags)
if ( !function_exists( 'axiom_options_page_start' ) ) {
	function axiom_options_page_start($args = array()) {
		$to_flags = array_merge(array(
			'data'				=> null,
			'nesting'			=> array(),	// Nesting stack for partitions, tabs and groups
			'radio_as_select'	=> false,	// Display options[type="radio"] as options[type="select"]
			'add_inherit'		=> false,	// Add value "Inherit" in all options with lists
			'show_page_layout'	=> true,	// Display page layout or only render fields
			'override'			=> ''		// Override mode - page|post|category|products-category|...
			), is_array($args) ? $args : array( 'add_inherit' => $args ));
		global $AXIOM_GLOBALS;
		$AXIOM_GLOBALS['to_flags'] = $to_flags;
		$AXIOM_GLOBALS['to_data'] = empty($args['data']) ? $AXIOM_GLOBALS['options'] : $args['data'];
		// Load required styles and scripts for Options Page
		axiom_options_load_scripts();
		// Prepare javascripts global variables
		axiom_options_prepare_scripts($to_flags['override']);
		?>
		<div class="axiom_options">
		<?php if ($to_flags['show_page_layout']) { ?>
			<form class="axiom_options_form">
		<?php }	?>
				<div class="axiom_options_header">
					<div id="axiom_options_logo" class="axiom_options_logo">
						<span class="iconadmin-cog"></span>
						<h2><?php _e('Theme Options', 'axiom'); ?></h2>
					</div>
					<div class="axiom_options_button_import"><span class="iconadmin-download"></span><?php _e('Import', 'axiom'); ?></div>
					<div class="axiom_options_button_export"><span class="iconadmin-upload"></span><?php _e('Export', 'axiom'); ?></div>
		<?php if ($to_flags['show_page_layout']) { ?>
					<div class="axiom_options_button_reset"><span class="iconadmin-spin3"></span><?php _e('Reset', 'axiom'); ?></div>
					<div class="axiom_options_button_save"><span class="iconadmin-check"></span><?php _e('Save', 'axiom'); ?></div>
		<?php }	?>
				</div>
				<div class="axiom_options_body">
		<?php
	}
}


// Finish render the options page (close groups, tabs and partitions)
if ( !function_exists( 'axiom_options_page_stop' ) ) {
	function axiom_options_page_stop() {
		global $AXIOM_GLOBALS;
		echo trim(axiom_options_close_nested_groups('', true));
		?>
				</div> <!-- .axiom_options_body -->
		<?php
		if ($AXIOM_GLOBALS['to_flags']['show_page_layout']) {
		?>
			</form>
		<?php
		}
		?>
		</div>	<!-- .axiom_options -->
		<?php
	}
}


// Return true if current type is groups type
if ( !function_exists( 'axiom_options_is_group' ) ) {
	function axiom_options_is_group($type) {
		return in_array($type, array('group', 'toggle', 'accordion', 'tab', 'partition'));
	}
}


// Close nested groups until type
if ( !function_exists( 'axiom_options_close_nested_groups' ) ) {
	function axiom_options_close_nested_groups($type='', $end=false) {
		global $AXIOM_GLOBALS;
		$output = '';
		if ($AXIOM_GLOBALS['to_flags']['nesting']) {
			for ($i=count($AXIOM_GLOBALS['to_flags']['nesting'])-1; $i>=0; $i--) {
				$container = array_pop($AXIOM_GLOBALS['to_flags']['nesting']);
				switch ($container) {
					case 'group':
						$output = '</fieldset>' . ($output);
						break;
					case 'toggle':
						$output = '</div></div>' . ($output);
						break;
					case 'tab':
					case 'partition':
						$output = '</div>' . ($container!=$type || $end ? '</div>' : '') . ($output);
						break;
					case 'accordion':
						$output = '</div></div>' . ($container!=$type || $end ? '</div>' : '') . ($output);
						break;
				}
				if ($type == $container)
					break;
			}
		}
		return $output;
	}
}


// Collect tabs titles for current tabs or partitions
if ( !function_exists( 'axiom_options_collect_tabs' ) ) {
	function axiom_options_collect_tabs($type, $id) {
		global $AXIOM_GLOBALS;
		$start = false;
		$nesting = array();
		$tabs = '';
		foreach ($AXIOM_GLOBALS['to_data'] as $field_id=>$field) {
			if (!empty($AXIOM_GLOBALS['to_flags']['override']) && (empty($field['override']) || !in_array($AXIOM_GLOBALS['to_flags']['override'], explode(',', $field['override'])))) continue;
			if ($field['type']==$type && !empty($field['start']) && $field['start']==$id)
				$start = true;
			if (!$start) continue;
			if (axiom_options_is_group($field['type'])) {
				if (empty($field['start']) && (!in_array($field['type'], array('group', 'toggle')) || !empty($field['end']))) {
					if ($nesting) {
						for ($i = count($nesting)-1; $i>=0; $i--) {
							$container = array_pop($nesting);
							if ($field['type'] == $container) {
								break;
							}
						}
					}
				}
				if (empty($field['end'])) {
					if (!$nesting) {
						if ($field['type']==$type) {
							$tabs .= '<li id="'.esc_attr($field_id).'">'
								. '<a id="'.esc_attr($field_id).'_title"'
									. ' href="#'.esc_attr($field_id).'_content"'
									. (!empty($field['action']) ? ' onclick="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
									. '>'
									. (!empty($field['icon']) ? '<span class="'.esc_attr($field['icon']).'"></span>' : '')
									. ($field['title'])
									. '</a>';
						} else
							break;
					}
					array_push($nesting, $field['type']);
				}
			}
		}
		return $tabs;
	}
}



// Return menu items list (menu, images or icons)
if ( !function_exists( 'axiom_options_menu_list' ) ) {
	function axiom_options_menu_list($field, $clone_val) {
		global $AXIOM_GLOBALS;

		$to_delimiter = $AXIOM_GLOBALS['to_delimiter'];

		if ($field['type'] == 'socials') $clone_val = $clone_val['icon'];
		$list = '<div class="axiom_options_input_menu '.(empty($field['style']) ? '' : ' axiom_options_input_menu_'.esc_attr($field['style'])).'">';
		$caption = '';
		foreach ($field['options'] as $key => $item) {
			if (in_array($field['type'], array('list', 'icons', 'socials'))) $key = $item;
			$selected = '';
			if (axiom_strpos(($to_delimiter).($clone_val).($to_delimiter), ($to_delimiter).($key).($to_delimiter))!==false) {
				$caption = esc_attr($item);
				$selected = ' axiom_options_state_checked';
			}
			$list .= '<span class="axiom_options_menuitem'
				. ($selected) 
				. '" data-value="'.esc_attr($key).'"'
				//. (!empty($field['action']) ? ' onclick="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
				. '>';
			if (in_array($field['type'], array('list', 'select', 'fonts')))
				$list .= $item;
			else if ($field['type'] == 'icons' || ($field['type'] == 'socials' && $field['style'] == 'icons'))
				$list .= '<span class="'.esc_attr($item).'"></span>';
			else if ($field['type'] == 'images' || ($field['type'] == 'socials' && $field['style'] == 'images'))
				//$list .= '<img src="'.esc_attr($item).'" data-icon="'.esc_attr($key).'" alt="" class="axiom_options_input_image" />';
				$list .= '<span style="background-image:url('.esc_url($item).')" data-src="'.esc_url($item).'" data-icon="'.esc_attr($key).'" class="axiom_options_input_image"></span>';
			$list .= '</span>';
		}
		$list .= '</div>';
		return array($list, $caption);
	}
}


// Return action buttom
if ( !function_exists( 'axiom_options_action_button' ) ) {
	function axiom_options_action_button($data, $type) {
		$class = ' axiom_options_button_'.esc_attr($type).(!empty($data['icon']) ? ' axiom_options_button_'.esc_attr($type).'_small' : '');
		$output = '<span class="' 
					. ($type == 'button' ? 'axiom_options_input_button'  : 'axiom_options_field_'.esc_attr($type))
					. (!empty($data['action']) ? ' axiom_options_with_action' : '')
					. (!empty($data['icon']) ? ' '.esc_attr($data['icon']) : '')
					. '"'
					. (!empty($data['icon']) && !empty($data['title']) ? ' title="'.esc_attr($data['title']).'"' : '')
					. (!empty($data['action']) ? ' onclick="axiom_options_action_'.esc_attr($data['action']).'(this);return false;"' : '')
					. (!empty($data['type']) ? ' data-type="'.esc_attr($data['type']).'"' : '')
					. (!empty($data['multiple']) ? ' data-multiple="'.esc_attr($data['multiple']).'"' : '')
					. (!empty($data['linked_field']) ? ' data-linked-field="'.esc_attr($data['linked_field']).'"' : '')
					. (!empty($data['captions']['choose']) ? ' data-caption-choose="'.esc_attr($data['captions']['choose']).'"' : '')
					. (!empty($data['captions']['update']) ? ' data-caption-update="'.esc_attr($data['captions']['update']).'"' : '')
					. '>'
					. ($type == 'button' || (empty($data['icon']) && !empty($data['title'])) ? $data['title'] : '')
					. '</span>';
		return array($output, $class);
	}
}


// Theme options page show option field
if ( !function_exists( 'axiom_options_show_field' ) ) {
	function axiom_options_show_field($id, $field, $value=null) {
		global $AXIOM_GLOBALS;

		// Set start field value
		if ($value !== null) $field['val'] = $value;
		if (!isset($field['val']) || $field['val']=='') $field['val'] = 'inherit';
		if (!empty($field['subset'])) {
			$sbs = axiom_get_theme_option($field['subset'], '', $AXIOM_GLOBALS['to_data']);
			$field['val'] = isset($field['val'][$sbs]) ? $field['val'][$sbs] : '';
		}
		
		if (empty($id))
			$id = 'axiom_options_id_'.str_replace('.', '', mt_rand());
		if (!isset($field['title']))
			$field['title'] = '';

		// Divider before field
		$divider = (!isset($field['divider']) && !in_array($field['type'], array('info', 'partition', 'tab', 'toggle'))) || (isset($field['divider']) && $field['divider']) ? ' axiom_options_divider' : '';
	
		// Setup default parameters
		if ($field['type']=='media') {
			if (!isset($field['before'])) {
				$field['before'] = array(
					'title' => __('Choose image', 'axiom'),
					'action' => 'media_upload',
					'type' => 'image',
					'multiple' => false,
					'linked_field' => '',
					'captions' => array('choose' => __( 'Choose image', 'axiom'),
										'update' => __( 'Select image', 'axiom')
										)
				);
			}
			if (!isset($field['after'])) {
				$field['after'] = array(
					'icon'=>'iconadmin-cancel',
					'action'=>'media_reset'
				);
			}
		}
	
		// Buttons before and after field
		$before = $after = $buttons_classes = '';
		if (!empty($field['before'])) {
			list($before, $class) = axiom_options_action_button($field['before'], 'before');
			$buttons_classes .= $class;
		}
		if (!empty($field['after'])) {
			list($after, $class) = axiom_options_action_button($field['after'], 'after');
			$buttons_classes .= $class;
		}
		if (in_array($field['type'], array('list', 'select', 'fonts')) || ($field['type']=='socials' && (empty($field['style']) || $field['style']=='icons'))) {
			$buttons_classes .= ' axiom_options_button_after_small';
		}
	
		// Is it inherit field?
		$inherit = axiom_is_inherit_option($field['val']) ? 'inherit' : '';
	
		// Is it cloneable field?
		$cloneable = isset($field['cloneable']) && $field['cloneable'];
	
		// Prepare field
		if (!$cloneable)
			$field['val'] = array($field['val']);
		else {
			if (!is_array($field['val']))
				$field['val'] = array($field['val']);
			else if ($field['type'] == 'socials' && (!isset($field['val'][0]) || !is_array($field['val'][0])))
				$field['val'] = array($field['val']);
		}
	
		// Field container
		if (axiom_options_is_group($field['type'])) {					// Close nested containers
			if (empty($field['start']) && (!in_array($field['type'], array('group', 'toggle')) || !empty($field['end']))) {
				echo trim(axiom_options_close_nested_groups($field['type'], !empty($field['end'])));
				if (!empty($field['end'])) {
					return;
				}
			}
		} else {														// Start field layout
			if ($field['type'] != 'hidden') {
				echo '<div class="axiom_options_field'
					. ' axiom_options_field_' . (in_array($field['type'], array('list','fonts')) ? 'select' : $field['type'])
					. (in_array($field['type'], array('media', 'fonts', 'list', 'select', 'socials', 'date', 'time')) ? ' axiom_options_field_text'  : '')
					. ($field['type']=='socials' && !empty($field['style']) && $field['style']=='images' ? ' axiom_options_field_images'  : '')
					. ($field['type']=='socials' && (empty($field['style']) || $field['style']=='icons') ? ' axiom_options_field_icons'  : '')
					. (isset($field['dir']) && $field['dir']=='vertical' ? ' axiom_options_vertical' : '')
					. (!empty($field['multiple']) ? ' axiom_options_multiple' : '')
					. (isset($field['size']) ? ' axiom_options_size_'.esc_attr($field['size']) : '')
					. (isset($field['class']) ? ' ' . esc_attr($field['class']) : '')
					. (!empty($field['columns']) ? ' axiom_options_columns axiom_options_columns_'.esc_attr($field['columns']) : '')
					. ($divider)
					. '">'."\n";
				echo '<label class="axiom_options_field_label'.(!empty($AXIOM_GLOBALS['to_flags']['add_inherit']) && isset($field['std']) ? ' axiom_options_field_label_inherit' : '').'" for="'.esc_attr($id).'">' . ($field['title'])
					. (!empty($AXIOM_GLOBALS['to_flags']['add_inherit']) && isset($field['std']) ? '<span id="'.esc_attr($id).'_inherit" class="axiom_options_button_inherit'.($inherit ? '' : ' axiom_options_inherit_off').'" title="' . __('Unlock this field', 'axiom') . '"></span>' : '')
					. '</label>'
					. "\n";
				echo '<div class="axiom_options_field_content'
					. ($buttons_classes)
					. ($cloneable ? ' axiom_options_cloneable_area' : '')
					. '">' . "\n";
			}
		}
	
		// Parse field type
		foreach ($field['val'] as $clone_num => $clone_val) {
			
			if ($cloneable) {
				echo '<div class="axiom_options_cloneable_item">'
					. '<span class="axiom_options_input_button axiom_options_clone_button axiom_options_clone_button_del">-</span>';
			}
	
			switch ( $field['type'] ) {
		
			case 'group':
				echo '<fieldset id="'.esc_attr($id).'" class="axiom_options_container axiom_options_group axiom_options_content'.esc_attr($divider).'">';
				if (!empty($field['title'])) echo '<legend>'.(!empty($field['icon']) ? '<span class="'.esc_attr($field['icon']).'"></span>' : '').esc_html($field['title']).'</legend>'."\n";
				array_push($AXIOM_GLOBALS['to_flags']['nesting'], 'group');
			break;
		
			case 'toggle':
				array_push($AXIOM_GLOBALS['to_flags']['nesting'], 'toggle');
				echo '<div id="'.esc_attr($id).'" class="axiom_options_container axiom_options_toggle'.esc_attr($divider).'">';
				echo '<h3 id="'.esc_attr($id).'_title"'
					. ' class="axiom_options_toggle_header'.(empty($field['closed']) ? ' ui-state-active' : '') .'"'
					. (!empty($field['action']) ? ' onclick="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>'
					. (!empty($field['icon']) ? '<span class="axiom_options_toggle_header_icon '.esc_attr($field['icon']).'"></span>' : '')
					. ($field['title'])
					. '<span class="axiom_options_toggle_header_marker iconadmin-left-open"></span>'
					. '</h3>'
					. '<div class="axiom_options_content axiom_options_toggle_content"'.(!empty($field['closed']) ? ' style="display:none;"' : '').'>';
			break;
		
			case 'accordion':
				array_push($AXIOM_GLOBALS['to_flags']['nesting'], 'accordion');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="axiom_options_container axiom_options_accordion'.esc_attr($divider).'">';
				echo '<div id="'.esc_attr($id).'" class="axiom_options_accordion_item">'
					. '<h3 id="'.esc_attr($id).'_title"'
					. ' class="axiom_options_accordion_header"'
					. (!empty($field['action']) ? ' onclick="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>' 
					. (!empty($field['icon']) ? '<span class="axiom_options_accordion_header_icon '.esc_attr($field['icon']).'"></span>' : '')
					. ($field['title'])
					. '<span class="axiom_options_accordion_header_marker iconadmin-left-open"></span>'
					. '</h3>'
					. '<div id="'.esc_attr($id).'_content" class="axiom_options_content axiom_options_accordion_content">';
			break;
		
			case 'tab':
				array_push($AXIOM_GLOBALS['to_flags']['nesting'], 'tab');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="axiom_options_container axiom_options_tab'.esc_attr($divider).'">'
						. '<ul>' . trim(axiom_options_collect_tabs($field['type'], $field['start'])) . '</ul>';
				echo '<div id="'.esc_attr($id).'_content"  class="axiom_options_content axiom_options_tab_content">';
			break;
		
			case 'partition':
				array_push($AXIOM_GLOBALS['to_flags']['nesting'], 'partition');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="axiom_options_container axiom_options_partition'.esc_attr($divider).'">'
						. '<ul>' . trim(axiom_options_collect_tabs($field['type'], $field['start'])) . '</ul>';
				echo '<div id="'.esc_attr($id).'_content" class="axiom_options_content axiom_options_partition_content">';
			break;
		
			case 'hidden':
				echo '<input class="axiom_options_input axiom_options_input_hidden" name="'.esc_attr($id).'" id="'.esc_attr($id).'" type="hidden" value="'. esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '" />';
			break;
	
			case 'date':
				if (isset($field['style']) && $field['style']=='inline') {
					echo '<div class="axiom_options_input_date" id="'.esc_attr($id).'_calendar"'
						. ' data-format="' . (!empty($field['format']) ? $field['format'] : 'yy-mm-dd') . '"'
						. ' data-months="' . (!empty($field['months']) ? max(1, min(3, $field['months'])) : 1) . '"'
						. ' data-linked-field="' . (!empty($data['linked_field']) ? $data['linked_field'] : $id) . '"'
						. '></div>'
					. '<input id="'.esc_attr($id).'"'
						. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
						. ' type="hidden"'
						. ' value="' . esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
						. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
				} else {
					echo '<input class="axiom_options_input axiom_options_input_date' . (!empty($field['mask']) ? ' axiom_options_input_masked' : '') . '"'
						. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
						. ' id="'.esc_attr($id). '"'
						. ' type="text"'
						. ' value="' . esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. ' data-format="' . (!empty($field['format']) ? $field['format'] : 'yy-mm-dd') . '"'
						. ' data-months="' . (!empty($field['months']) ? max(1, min(3, $field['months'])) : 1) . '"'
						. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
						. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />'
					. ($before)
					. ($after);
				}
			break;
	
			case 'text':
				echo '<input class="axiom_options_input axiom_options_input_text' . (!empty($field['mask']) ? ' axiom_options_input_masked' : '') . '"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' type="text"'
					. ' value="'. esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
				. ($before)
				. ($after);
			break;
			
			case 'textarea':
				$cols = isset($field['cols']) && $field['cols'] > 10 ? $field['cols'] : '40';
				$rows = isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : '8';
				echo '<textarea class="axiom_options_input axiom_options_input_textarea"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id).'"'
					. ' cols="'.esc_attr($cols).'"'
					. ' rows="'.esc_attr($rows).'"'
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>'
					. esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val)
					. '</textarea>';
			break;
			
			case 'editor':
				$cols = isset($field['cols']) && $field['cols'] > 10 ? $field['cols'] : '40';
				$rows = isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : '10';
				wp_editor( axiom_is_inherit_option($clone_val) ? '' : $clone_val, $id . ($cloneable ? '[]' : ''), array(
					'wpautop' => false,
					'textarea_rows' => $rows
				));
			break;
	
			case 'spinner':
				echo '<input class="axiom_options_input axiom_options_input_spinner' . (!empty($field['mask']) ? ' axiom_options_input_masked' : '')
					. '" name="'.esc_attr($id). ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id).'"'
					. ' type="text"'
					. ' value="'. esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '') 
					. (isset($field['min']) ? ' data-min="'.esc_attr($field['min']).'"' : '') 
					. (isset($field['max']) ? ' data-max="'.esc_attr($field['max']).'"' : '') 
					. (!empty($field['step']) ? ' data-step="'.esc_attr($field['step']).'"' : '') 
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />' 
					. '<span class="axiom_options_arrows"><span class="axiom_options_arrow_up iconadmin-up-dir"></span><span class="axiom_options_arrow_down iconadmin-down-dir"></span></span>';
			break;
	
			case 'tags':
				if (!axiom_is_inherit_option($clone_val)) {
					$tags = explode($AXIOM_GLOBALS['to_delimiter'], $clone_val);
					if (count($tags) > 0) {
						foreach($tags as $tag) {
							if (empty($tag)) continue;
							echo '<span class="axiom_options_tag iconadmin-cancel">'.($tag).'</span>';
						}
					}
				}
				echo '<input class="axiom_options_input_tags"'
					. ' type="text"'
					. ' value=""'
					. ' />'
					. '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
						. ' type="hidden"'
						. ' value="'. esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
			break;
			
			case "checkbox": 
				echo '<input type="checkbox" class="axiom_options_input axiom_options_input_checkbox"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' value="true"'
					. ($clone_val == 'true' ? ' checked="checked"' : '') 
					. (!empty($field['disabled']) ? ' readonly="readonly"' : '') 
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. '<label for="'.esc_attr($id).'" class="' . (!empty($field['disabled']) ? 'axiom_options_state_disabled' : '') . ($clone_val=='true' ? ' axiom_options_state_checked' : '').'"><span class="axiom_options_input_checkbox_image iconadmin-check"></span>' . (!empty($field['label']) ? $field['label'] : $field['title']) . '</label>';
			break;
			
			case "radio":
				foreach ($field['options'] as $key => $title) { 
					echo '<span class="axiom_options_radioitem">'
						.'<input class="axiom_options_input axiom_options_input_radio" type="radio"'
							. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
							. ' value="'.esc_attr($key) .'"'
							. ($clone_val == $key ? ' checked="checked"' : '') 
							. ' id="'.esc_attr(($id).'_'.($key)).'"'
							. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
							. ' />'
							. '<label for="'.esc_attr(($id).'_'.($key)).'"'. ($clone_val == $key ? ' class="axiom_options_state_checked"' : '') .'><span class="axiom_options_input_radio_image iconadmin-circle-empty'.($clone_val == $key ? ' iconadmin-dot-circled' : '') . '"></span>' . ($title) . '</label></span>';
				}
			break;
			
			case "switch":
				$opt = array();
				foreach ($field['options'] as $key => $title) { 
					$opt[] = array('key'=>$key, 'title'=>$title);
					if (count($opt)==2) break;
				}
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' value="'. esc_attr(axiom_is_inherit_option($clone_val) || empty($clone_val) ? $opt[0]['key'] : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. '<span class="axiom_options_switch'.($clone_val==$opt[1]['key'] ? ' axiom_options_state_off' : '').'"><span class="axiom_options_switch_inner iconadmin-circle"><span class="axiom_options_switch_val1" data-value="'.esc_attr($opt[0]['key']).'">'.($opt[0]['title']).'</span><span class="axiom_options_switch_val2" data-value="'.esc_attr($opt[1]['key']).'">'.($opt[1]['title']).'</span></span></span>';
			break;
	
			case 'media':
				echo '<input class="axiom_options_input axiom_options_input_text axiom_options_input_media"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' type="text"'
					. ' value="'. esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!isset($field['readonly']) || $field['readonly'] ? ' readonly="readonly"' : '') 
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
				. ($before)
				. ($after);
				if (!empty($clone_val) && !axiom_is_inherit_option($clone_val)) {
					$info = pathinfo($clone_val);
					$ext = isset($info['extension']) ? $info['extension'] : '';
					echo '<a class="axiom_options_image_preview" data-rel="popup" target="_blank" href="'.esc_url($clone_val).'">'.(!empty($ext) && axiom_strpos('jpg,png,gif', $ext)!==false ? '<img src="'.esc_url($clone_val).'" alt="" />' : '<span>'.($info['basename']).'</span>').'</a>';
				}
			break;
			
			case 'button':
				list($button, $class) = axiom_options_action_button($field, 'button');
				echo ($button);
			break;
	
			case 'range':
				echo '<div class="axiom_options_input_range" data-step="'.(!empty($field['step']) ? $field['step'] : 1).'">';
				echo '<span class="axiom_options_range_scale"><span class="axiom_options_range_scale_filled"></span></span>';
				if (axiom_strpos($clone_val, $AXIOM_GLOBALS['to_delimiter'])===false)
					$clone_val = max($field['min'], intval($clone_val));
				if (axiom_strpos($field['std'], $AXIOM_GLOBALS['to_delimiter'])!==false && axiom_strpos($clone_val, $AXIOM_GLOBALS['to_delimiter'])===false)
					$clone_val = ($field['min']).','.($clone_val);
				$sliders = explode($AXIOM_GLOBALS['to_delimiter'], $clone_val);
				foreach($sliders as $s) {
					echo '<span class="axiom_options_range_slider"><span class="axiom_options_range_slider_value">'.intval($s).'</span><span class="axiom_options_range_slider_button"></span></span>';
				}
				echo '<span class="axiom_options_range_min">'.($field['min']).'</span><span class="axiom_options_range_max">'.($field['max']).'</span>';
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' value="' . esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
				echo '</div>';			
			break;
			
			case "checklist":
				foreach ($field['options'] as $key => $title) { 
					echo '<span class="axiom_options_listitem'
						. (axiom_strpos(($AXIOM_GLOBALS['to_delimiter']).($clone_val).($AXIOM_GLOBALS['to_delimiter']), ($AXIOM_GLOBALS['to_delimiter']).($key).($AXIOM_GLOBALS['to_delimiter']))!==false ? ' axiom_options_state_checked' : '') . '"'
						. ' data-value="'.esc_attr($key).'"'
						. '>'
						. esc_attr($title)
						. '</span>';
				}
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' value="'. esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
			
			case 'fonts':
				foreach ($field['options'] as $key => $title) {
					$field['options'][$key] = $key;
				}
			case 'list':
			case 'select':
				if (!isset($field['options']) && !empty($field['from']) && !empty($field['to'])) {
					$field['options'] = array();
					for ($i = $field['from']; $i <= $field['to']; $i+=(!empty($field['step']) ? $field['step'] : 1)) {
						$field['options'][$i] = $i;
					}
				}
				list($list, $caption) = axiom_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<input class="axiom_options_input axiom_options_input_select" type="text" value="'.esc_attr($caption) . '"'
						. ' readonly="readonly"'
						//. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '') 
						. ' />'
						. ($before)
						. '<span class="axiom_options_field_after axiom_options_with_action iconadmin-down-open" onclick="axiom_options_action_show_menu(this);return false;"></span>';
				}
				echo ($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' value="'. esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
	
			case 'images':
				list($list, $caption) = axiom_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<div class="axiom_options_caption_image iconadmin-down-open">'
						//.'<img src="'.esc_url($caption).'" alt="" />'
						.'<span style="background-image: url('.esc_url($caption).')"></span>'
						.'</div>';
				}
				echo ($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' type="hidden"'
					. ' value="' . esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
			
			case 'icons':
				if (isset($field['css']) && $field['css']!='' && file_exists($field['css'])) {
					$field['options'] = axiom_parse_icons_classes($field['css']);
				}
				list($list, $caption) = axiom_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<div class="axiom_options_caption_icon iconadmin-down-open"><span class="'.esc_attr($caption).'"></span></div>';
				}
				echo ($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' type="hidden"'
					. ' value="' . esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
	
			case 'socials':
				if (!is_array($clone_val)) $clone_val = array('url'=>'', 'icon'=>'');
				list($list, $caption) = axiom_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='icons') {
					list($after, $class) = axiom_options_action_button(array(
						'action' => empty($field['style']) || $field['style']=='icons' ? 'select_icon' : '',
						'icon' => (empty($field['style']) || $field['style']=='icons') && !empty($clone_val['icon']) ? $clone_val['icon'] : 'iconadmin-users-1'
						), 'after');
				} else
					$after = '';
				echo '<input class="axiom_options_input axiom_options_input_text axiom_options_input_socials'
					. (!empty($field['mask']) ? ' axiom_options_input_masked' : '') . '"'
					. ' name="'.esc_attr($id).($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' type="text" value="'. esc_attr(axiom_is_inherit_option($clone_val['url']) ? '' : $clone_val['url']) . '"'
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '') 
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. ($after);
				if (!empty($field['style']) && $field['style']=='images') {
					echo '<div class="axiom_options_caption_image iconadmin-down-open">'
						//.'<img src="'.esc_url($caption).'" alt="" />'
						.'<span style="background-image: url('.esc_url($caption).')"></span>'
						.'</div>';
				}
				echo ($list);
				echo '<input name="'.esc_attr($id) . '_icon' . ($cloneable ? '[]' : '') .'" type="hidden" value="'. esc_attr(axiom_is_inherit_option($clone_val['icon']) ? '' : $clone_val['icon']) . '" />';
			break;
	
			case "color":
				echo '<input class="axiom_options_input axiom_options_input_color'.(isset($field['style']) && $field['style']=='custom' ? ' axiom_options_input_color_custom' : '').'"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' id="'.esc_attr($id) . '"'
					. ' type="text"'
					. ' value="'. esc_attr(axiom_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="axiom_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
				if (isset($field['style']) && $field['style']=='custom') {
					echo '<span class="axiom_options_input_colorpicker iColorPicker"></span>';
				}
			break;   
	
			default:
				if (function_exists('axiom_show_custom_field')) {
					echo trim(axiom_show_custom_field($id, $field, $clone_val));
				}
			} 
	
			if ($cloneable) {
				echo '<input type="hidden" name="'.esc_attr($id) . '_numbers[]" value="'.esc_attr($clone_num).'" />'
					. '</div>';
			}
		}
	
		if (!axiom_options_is_group($field['type']) && $field['type'] != 'hidden') {
			if ($cloneable) {
				echo '<div class="axiom_options_input_button axiom_options_clone_button axiom_options_clone_button_add">'. __('+ Add item', 'axiom') .'</div>';
			}
			if (!empty($AXIOM_GLOBALS['to_flags']['add_inherit']) && isset($field['std']))
				echo  '<div class="axiom_options_content_inherit"'.($inherit ? '' : ' style="display:none;"').'><div>'.__('Inherit', 'axiom').'</div><input type="hidden" name="'.esc_attr($id).'_inherit" value="'.esc_attr($inherit).'" /></div>';
			echo '</div>';
			if (!empty($field['desc']))
				echo '<div class="axiom_options_desc">' . ($field['desc']) .'</div>' . "\n";
			echo '</div>' . "\n";
		}
	}
}


// Ajax Save and Export Action handler
if ( !function_exists( 'axiom_options_save' ) ) {
	//add_action('wp_ajax_axiom_options_save', 'axiom_options_save');
	//add_action('wp_ajax_nopriv_axiom_options_save', 'axiom_options_save');
	function axiom_options_save() {
		global $AXIOM_GLOBALS;
	
		if ( !wp_verify_nonce( $_POST['nonce'], 'ajax_nonce' ) )
			die();
	
		$mode = $_POST['mode'];
		$override = $_POST['override']=='' ? 'general' : $_POST['override'];
		$options = $AXIOM_GLOBALS['options'];
	
		if ($mode == 'save') {
			parse_str($_POST['data'], $post_data);
		} else if ($mode=='export') {
			parse_str($_POST['data'], $post_data);
			if (!empty($AXIOM_GLOBALS['post_meta_box']['fields'])) {
				$options = axiom_array_merge($AXIOM_GLOBALS['options'], $AXIOM_GLOBALS['post_meta_box']['fields']);
			}
		} else
			$post_data = array();
	
		$custom_options = array();
	
		axiom_options_merge_new_values($options, $custom_options, $post_data, $mode, $override);
	
		if ($mode=='export') {
			$name  = trim(chop($_POST['name']));
			$name2 = isset($_POST['name2']) ? trim(chop($_POST['name2'])) : '';
			$key = $name=='' ? $name2 : $name;
			$export = get_option('axiom_options_'.($override), array());
			$export[$key] = $custom_options;
			if ($name!='' && $name2!='') unset($export[$name2]);
			update_option('axiom_options_'.($override), $export);
			$file = axiom_get_file_dir('core/core.options/core.options.txt');
			$url  = axiom_get_file_url('core/core.options/core.options.txt');
			$export = serialize($custom_options);
			axiom_fpc($file, $export);
			$response = array('error'=>'', 'data'=>$export, 'link'=>$url);
			echo json_encode($response);
		} else {
			update_option('axiom_options', apply_filters('axiom_filter_save_options', $custom_options, $override));
		}
		
		die();
	}
}


// Ajax Import Action handler
if ( !function_exists( 'axiom_options_import' ) ) {
	//add_action('wp_ajax_axiom_options_import', 'axiom_options_import');
	//add_action('wp_ajax_nopriv_axiom_options_import', 'axiom_options_import');
	function axiom_options_import() {
		if ( !wp_verify_nonce( $_POST['nonce'], 'ajax_nonce' ) )
			die();
	
		$override = $_POST['override']=='' ? 'general' : $_POST['override'];
		$text = stripslashes(trim(chop($_POST['text'])));
		if (!empty($text)) {
			$opt = @unserialize($text);
			if ( ! $opt ) {
				$opt = @unserialize(str_replace("\n", "\r\n", $text));
			}
			if ( ! $opt ) {
				$opt = @unserialize(str_replace(array("\n", "\r"), array('\\n','\\r'), $text));
			}
		} else {
			$key = trim(chop($_POST['name2']));
			$import = get_option('axiom_options_'.($override), array());
			$opt = isset($import[$key]) ? $import[$key] : false;
		}
		$response = array('error'=>$opt===false ? __('Error while unpack import data!', 'axiom') : '', 'data'=>$opt);
		echo json_encode($response);
	
		die();
	}
}

// Merge data from POST and current post/page/category/theme options
if ( !function_exists( 'axiom_options_merge_new_values' ) ) {
	function axiom_options_merge_new_values(&$post_options, &$custom_options, &$post_data, $mode, $override) {
		$need_save = false;
		foreach ($post_options as $id=>$field) { 
			if ($override!='general' && (!isset($field['override']) || !in_array($override, explode(',', $field['override'])))) continue;
			if (!isset($field['std'])) continue;
			if ($override!='general' && !isset($post_data[$id.'_inherit'])) continue;
			if ($id=='reviews_marks' && $mode=='export') continue;
			$need_save = true;
			if ($mode == 'save' || $mode=='export') {
				if ($override!='general' && axiom_is_inherit_option($post_data[$id.'_inherit']))
					$new = '';
				else if (isset($post_data[$id])) {
					// Prepare specific (combined) fields
					if (!empty($field['subset'])) {
						$sbs = $post_data[$field['subset']];
						$field['val'][$sbs] = $post_data[$id];
						$post_data[$id] = $field['val'];
					}
					if ($field['type']=='socials') {
						if (!empty($field['cloneable'])) {
							foreach($post_data[$id] as $k=>$v)
								$post_data[$id][$k] = array('url'=>stripslashes($v), 'icon'=>stripslashes($post_data[$id.'_icon'][$k]));
						} else {
							$post_data[$id] = array('url'=>stripslashes($post_data[$id]), 'icon'=>stripslashes($post_data[$id.'_icon']));
						}
					} else if (is_array($post_data[$id])) {
						foreach($post_data[$id] as $k=>$v)
							$post_data[$id][$k] = stripslashes($v);
					} else
						$post_data[$id] = stripslashes($post_data[$id]);
					// Add cloneable index
					if (!empty($field['cloneable'])) {
						$rez = array();
						foreach($post_data[$id] as $k=>$v)
							$rez[$post_data[$id.'_numbers'][$k]] = $v;
						$post_data[$id] = $rez;
					}
					$new = $post_data[$id];
					// Post type specific data handling
					if ($id == 'reviews_marks') {
						$new = join(',', $new);
						if (($avg = axiom_reviews_get_average_rating($new)) > 0) {
							$new = axiom_reviews_marks_to_save($new);
						}
					}
				} else
					$new = $field['type'] == 'checkbox' ? 'false' : '';
			} else {
				$new = $field['std'];
			}
			$custom_options[$id] = $new!=='' || $override=='general' ? $new : 'inherit';
		}
		return $need_save;
	}
}



// Load custom fields
if (is_admin()) {
	require_once( axiom_get_file_dir('core/core.options/core.options-custom.php') );
}

// Load default options
require_once( axiom_get_file_dir('core/core.options/core.options-settings.php') );

// Load inheritance system
require_once( axiom_get_file_dir('core/core.options/core.options-inheritance.php') );
?>