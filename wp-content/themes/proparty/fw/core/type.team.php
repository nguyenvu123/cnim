<?php
/**
 * AxiomThemes Framework: Team post type settings
 *
 * @package	axiom
 * @since	axiom 1.0
 */

// Theme init
if (!function_exists('axiom_team_theme_setup')) {
	add_action( 'axiom_action_before_init_theme', 'axiom_team_theme_setup' );
	function axiom_team_theme_setup() {

		// Add item in the admin menu
		add_action('admin_menu',							'axiom_team_add_meta_box');

		// Save data from meta box
		add_action('save_post',								'axiom_team_save_data');
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('axiom_filter_get_blog_type',			'axiom_team_get_blog_type', 9, 2);
		add_filter('axiom_filter_get_blog_title',		'axiom_team_get_blog_title', 9, 2);
		add_filter('axiom_filter_get_current_taxonomy',	'axiom_team_get_current_taxonomy', 9, 2);
		add_filter('axiom_filter_is_taxonomy',			'axiom_team_is_taxonomy', 9, 2);
		add_filter('axiom_filter_get_stream_page_title',	'axiom_team_get_stream_page_title', 9, 2);
		add_filter('axiom_filter_get_stream_page_link',	'axiom_team_get_stream_page_link', 9, 2);
		add_filter('axiom_filter_get_stream_page_id',	'axiom_team_get_stream_page_id', 9, 2);
		add_filter('axiom_filter_query_add_filters',		'axiom_team_query_add_filters', 9, 2);
		add_filter('axiom_filter_detect_inheritance_key','axiom_team_detect_inheritance_key', 9, 1);

		// Extra column for team members lists
		if (axiom_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-team_columns',			'axiom_post_add_options_column', 9);
			add_filter('manage_team_posts_custom_column',	'axiom_post_fill_options_column', 9, 2);
		}

		// Meta box fields
		global $AXIOM_GLOBALS;
		$AXIOM_GLOBALS['team_meta_box'] = array(
			'id' => 'team-meta-box',
			'title' => __('Team Member Details', 'axiom'),
			'page' => 'team',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"team_member_position" => array(
					"title" => __('Position',  'axiom'),
					"desc" => __("Position of the team member", 'axiom'),
					"class" => "team_member_position",
					"std" => "",
					"type" => "text"),
				"team_member_email" => array(
					"title" => __("E-mail",  'axiom'),
					"desc" => __("E-mail of the team member - need to take Gravatar (if registered)", 'axiom'),
					"class" => "team_member_email",
					"std" => "",
					"type" => "text"),
				"team_member_link" => array(
					"title" => __('Link to profile',  'axiom'),
					"desc" => __("URL of the team member profile page (if not this page)", 'axiom'),
					"class" => "team_member_link",
					"std" => "",
					"type" => "text"),
				"team_member_socials" => array(
					"title" => __("Social links",  'axiom'),
					"desc" => __("Links to the social profiles of the team member", 'axiom'),
					"class" => "team_member_email",
					"std" => "",
					"type" => "social")
			)
		);
		
		// Prepare type "Team"
		axiom_require_data( 'post_type', 'team', array(
			'label'               => __( 'Team member', 'axiom' ),
			'description'         => __( 'Team Description', 'axiom' ),
			'labels'              => array(
				'name'                => _x( 'Team', 'Post Type General Name', 'axiom' ),
				'singular_name'       => _x( 'Team member', 'Post Type Singular Name', 'axiom' ),
				'menu_name'           => __( 'Team', 'axiom' ),
				'parent_item_colon'   => __( 'Parent Item:', 'axiom' ),
				'all_items'           => __( 'All Team', 'axiom' ),
				'view_item'           => __( 'View Item', 'axiom' ),
				'add_new_item'        => __( 'Add New Team member', 'axiom' ),
				'add_new'             => __( 'Add New', 'axiom' ),
				'edit_item'           => __( 'Edit Item', 'axiom' ),
				'update_item'         => __( 'Update Item', 'axiom' ),
				'search_items'        => __( 'Search Item', 'axiom' ),
				'not_found'           => __( 'Not found', 'axiom' ),
				'not_found_in_trash'  => __( 'Not found in Trash', 'axiom' ),
			),
			'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'menu_icon'			  => 'dashicons-admin-users',
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 30,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'query_var'           => true,
			'capability_type'     => 'page',
			'rewrite'             => true
			)
		);
		
		// Prepare taxonomy for team
		axiom_require_data( 'taxonomy', 'team_group', array(
			'post_type'			=> array( 'team' ),
			'hierarchical'      => true,
			'labels'            => array(
				'name'              => _x( 'Team Group', 'taxonomy general name', 'axiom' ),
				'singular_name'     => _x( 'Group', 'taxonomy singular name', 'axiom' ),
				'search_items'      => __( 'Search Groups', 'axiom' ),
				'all_items'         => __( 'All Groups', 'axiom' ),
				'parent_item'       => __( 'Parent Group', 'axiom' ),
				'parent_item_colon' => __( 'Parent Group:', 'axiom' ),
				'edit_item'         => __( 'Edit Group', 'axiom' ),
				'update_item'       => __( 'Update Group', 'axiom' ),
				'add_new_item'      => __( 'Add New Group', 'axiom' ),
				'new_item_name'     => __( 'New Group Name', 'axiom' ),
				'menu_name'         => __( 'Team Group', 'axiom' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'team_group' ),
			)
		);
	}
}

if ( !function_exists( 'axiom_team_settings_theme_setup2' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_team_settings_theme_setup2', 3 );
	function axiom_team_settings_theme_setup2() {
		// Add post type 'team' and taxonomy 'team_group' into theme inheritance list
		axiom_add_theme_inheritance( array('team' => array(
			'stream_template' => 'team',
			'single_template' => 'single-team',
			'taxonomy' => array('team_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('team'),
			'override' => 'post'
			) )
		);
	}
}


// Add meta box
if (!function_exists('axiom_team_add_meta_box')) {
	//add_action('admin_menu', 'axiom_team_add_meta_box');
	function axiom_team_add_meta_box() {
		global $AXIOM_GLOBALS;
		$mb = $AXIOM_GLOBALS['team_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'axiom_team_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('axiom_team_show_meta_box')) {
	function axiom_team_show_meta_box() {
		global $post, $AXIOM_GLOBALS;

		// Use nonce for verification
		$data = get_post_meta($post->ID, 'team_data', true);
		$fields = $AXIOM_GLOBALS['team_meta_box']['fields'];
		?>
		<input type="hidden" name="meta_box_team_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
		<table class="team_area">
		<?php
		foreach ($fields as $id=>$field) { 
			$meta = isset($data[$id]) ? $data[$id] : '';
			?>
			<tr class="team_field <?php echo esc_attr($field['class']); ?>" valign="top">
				<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
				<td>
					<?php
					if ($id == 'team_member_socials') {
						$style = "icons";
						$upload_info = wp_upload_dir();
						$upload_url = $upload_info['baseurl'];
						$social_list = axiom_get_theme_option('social_icons');
						foreach ($social_list as $soc) {
							$sn = basename($soc['icon']);
							if ($style == 'icons') {
								$sn = axiom_substr($sn, axiom_strrpos($sn, '-') + 1, strlen($sn) - axiom_strrpos($sn, '-'));
							} else {
								$sn = axiom_substr( $sn, 0, axiom_strrpos( $sn, '.' ) );
							}
							if ( ( $pos = axiom_strrpos( $sn, '_' ) ) !== false ) {
								$sn = axiom_substr( $sn, 0, $pos );
							}
							$link = isset( $meta[ $sn ] ) ? $meta[ $sn ] : '';
							?>
							<label for="<?php echo esc_attr(($id).'_'.($sn)); ?>"><?php echo esc_attr(axiom_strtoproper($sn)); ?></label><br>
							<input type="text" name="<?php echo esc_attr($id); ?>[<?php echo esc_attr($sn); ?>]" id="<?php echo esc_attr(($id).'_'.($sn)); ?>" value="<?php echo esc_attr($link); ?>" size="30" /><br>
							<?php
						}
					} else {
						?>
						<input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<?php
					}
					?>
					<br><small><?php echo esc_attr($field['desc']); ?></small>
				</td>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
	}
}


// Save data from meta box
if (!function_exists('axiom_team_save_data')) {
	//add_action('save_post', 'axiom_team_save_data');
	function axiom_team_save_data($post_id) {
		// verify nonce
		if (!isset($_POST['meta_box_team_nonce']) || !wp_verify_nonce($_POST['meta_box_team_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='team' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		global $AXIOM_GLOBALS;

		$data = array();

		$fields = $AXIOM_GLOBALS['team_meta_box']['fields'];

		// Post type specific data handling
		foreach ($fields as $id=>$field) {
			if (isset($_POST[$id])) {
				if (is_array($_POST[$id])) {
					foreach ($_POST[$id] as $sn=>$link) {
						$_POST[$id][$sn] = stripslashes($link);
					}
					$data[$id] = $_POST[$id];
				} else {
					$data[$id] = stripslashes($_POST[$id]);
				}
			}
		}

		update_post_meta($post_id, 'team_data', $data);
	}
}



// Return true, if current page is team member page
if ( !function_exists( 'axiom_is_team_page' ) ) {
	function axiom_is_team_page() {
		return get_query_var('post_type')=='team' || is_tax('team_group');
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'axiom_team_detect_inheritance_key' ) ) {
	//add_filter('axiom_filter_detect_inheritance_key',	'axiom_team_detect_inheritance_key', 9, 1);
	function axiom_team_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return axiom_is_team_page() ? 'team' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'axiom_team_get_blog_type' ) ) {
	//add_filter('axiom_filter_get_blog_type',	'axiom_team_get_blog_type', 9, 2);
	function axiom_team_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('team_group') || is_tax('team_group'))
			$page = 'team_category';
		else if ($query && $query->get('post_type')=='team' || get_query_var('post_type')=='team')
			$page = $query && $query->is_single() || is_single() ? 'team_item' : 'team';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'axiom_team_get_blog_title' ) ) {
	//add_filter('axiom_filter_get_blog_title',	'axiom_team_get_blog_title', 9, 2);
	function axiom_team_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( axiom_strpos($page, 'team')!==false ) {
			if ( $page == 'team_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'team_group' ), 'team_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'team_item' ) {
				$title = axiom_get_post_title();
			} else {
				$title = __('All team', 'axiom');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'axiom_team_get_stream_page_title' ) ) {
	//add_filter('axiom_filter_get_stream_page_title',	'axiom_team_get_stream_page_title', 9, 2);
	function axiom_team_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (axiom_strpos($page, 'team')!==false) {
			if (($page_id = axiom_team_get_stream_page_id(0, $page)) > 0)
				$title = axiom_get_post_title($page_id);
			else
				$title = __('All team', 'axiom');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'axiom_team_get_stream_page_id' ) ) {
	//add_filter('axiom_filter_get_stream_page_id',	'axiom_team_get_stream_page_id', 9, 2);
	function axiom_team_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (axiom_strpos($page, 'team')!==false) $id = axiom_get_template_page_id('team');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'axiom_team_get_stream_page_link' ) ) {
	//add_filter('axiom_filter_get_stream_page_link',	'axiom_team_get_stream_page_link', 9, 2);
	function axiom_team_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (axiom_strpos($page, 'team')!==false) {
			$id = axiom_get_template_page_id('team');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'axiom_team_get_current_taxonomy' ) ) {
	//add_filter('axiom_filter_get_current_taxonomy',	'axiom_team_get_current_taxonomy', 9, 2);
	function axiom_team_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( axiom_strpos($page, 'team')!==false ) {
			$tax = 'team_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'axiom_team_is_taxonomy' ) ) {
	//add_filter('axiom_filter_is_taxonomy',	'axiom_team_is_taxonomy', 9, 2);
	function axiom_team_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('team_group')!='' || is_tax('team_group') ? 'team_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'axiom_team_query_add_filters' ) ) {
	//add_filter('axiom_filter_query_add_filters',	'axiom_team_query_add_filters', 9, 2);
	function axiom_team_query_add_filters($args, $filter) {
		if ($filter == 'team') {
			$args['post_type'] = 'team';
		}
		return $args;
	}
}
?>