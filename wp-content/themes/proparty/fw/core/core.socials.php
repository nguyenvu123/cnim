<?php
/**
 * AxiomThemes Framework: social networks
 *
 * @package	axiom
 * @since	axiom 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('axiom_socials_theme_setup')) {
	add_action( 'axiom_action_before_init_theme', 'axiom_socials_theme_setup' );
	function axiom_socials_theme_setup() {

		if ( is_admin() ) {
			// Add extra fields in the user profile
			add_action( 'show_user_profile',		'axiom_add_fields_in_user_profile' );
			add_action( 'edit_user_profile',		'axiom_add_fields_in_user_profile' );
	
			// Save / update additional fields from profile
			add_action( 'personal_options_update',	'axiom_save_fields_in_user_profile' );
			add_action( 'edit_user_profile_update',	'axiom_save_fields_in_user_profile' );

		} else {

			// Add og:image meta tag for facebook
			add_action( 'wp_head', 					'axiom_facebook_og_tags', 5 );

		}

	}
}


/* Social Share and Profile links
-------------------------------------------------------------------------------- */
	
// List of social networks for site sharing and user profiles
$AXIOM_GLOBALS['share_links'] = array(
	'blogger' =>		'http://www.blogger.com/blog_this.pyra?t&u={link}&n={title}',
	'bobrdobr' =>		'http://bobrdobr.ru/add.html?url={link}&title={title}&desc={descr}',
	'delicious' =>		'http://delicious.com/save?url={link}&title={title}&note={descr}',
	'designbump' =>		'http://designbump.com/node/add/drigg/?url={link}&title={title}',
	'designfloat' =>	'http://www.designfloat.com/submit.php?url={link}',
	'digg' =>			'http://digg.com/submit?url={link}',
	'evernote' =>		'https://www.evernote.com/clip.action?url={link}&title={title}',
	'facebook' =>		'http://www.facebook.com/sharer.php?s=100&p[url]={link}&p[title]={title}&p[summary]={descr}&p[images][0]={image}',
	'friendfeed' =>		'http://www.friendfeed.com/share?title={title} - {link}',
	'google' =>			'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk={link}&title={title}&annotation={descr}',
	'gplus' => 			'https://plus.google.com/share?url={link}', 
	'identi' => 		'http://identi.ca/notice/new?status_textarea={title} - {link}', 
	'juick' => 			'http://www.juick.com/post?body={title} - {link}',
	'linkedin' => 		'http://www.linkedin.com/shareArticle?mini=true&url={link}&title={title}', 
	'liveinternet' =>	'http://www.liveinternet.ru/journal_post.php?action=n_add&cnurl={link}&cntitle={title}',
	'livejournal' =>	'http://www.livejournal.com/update.bml?event={link}&subject={title}',
	'mail' =>			'http://connect.mail.ru/share?url={link}&title={title}&description={descr}&imageurl={image}',
	'memori' =>			'http://memori.ru/link/?sm=1&u_data[url]={link}&u_data[name]={title}', 
	'mister-wong' =>	'http://www.mister-wong.ru/index.php?action=addurl&bm_url={link}&bm_description={title}', 
	'mixx' =>			'http://chime.in/chimebutton/compose/?utm_source=bookmarklet&utm_medium=compose&utm_campaign=chime&chime[url]={link}&chime[title]={title}&chime[body]={descr}', 
	'moykrug' =>		'http://share.yandex.ru/go.xml?service=moikrug&url={link}&title={title}&description={descr}',
	'myspace' =>		'http://www.myspace.com/Modules/PostTo/Pages/?u={link}&t={title}&c={descr}', 
	'newsvine' =>		'http://www.newsvine.com/_tools/seed&save?u={link}&h={title}',
	'odnoklassniki' =>	'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl={link}&title={title}', 
	'pikabu' =>			'http://pikabu.ru/add_story.php?story_url={link}',
	'pinterest' =>		'http://pinterest.com/pin/create/button/?url={link}&media={image}&description={title}',
	'posterous' =>		'http://posterous.com/share?linkto={link}&title={title}',
	'postila' =>		'http://postila.ru/publish/?url={link}&agregator=axiomthemes',
	'reddit' =>			'http://reddit.com/submit?url={link}&title={title}', 
	'rutvit' =>			'http://rutvit.ru/tools/widgets/share/popup?url={link}&title={title}', 
	'stumbleupon' =>	'http://www.stumbleupon.com/submit?url={link}&title={title}', 
	'surfingbird' =>	'http://surfingbird.ru/share?url={link}', 
	'technorati' =>		'http://technorati.com/faves?add={link}&title={title}', 
	'tumblr' =>			'http://www.tumblr.com/share?v=3&u={link}&t={title}&s={descr}', 
	'twitter' =>		'https://twitter.com/intent/tweet?text={title}&url={link}',
	'vk' =>				'http://vk.com/share.php?url={link}&title={title}&description={descr}',
	'vk2' =>			'http://vk.com/share.php?url={link}&title={title}&description={descr}',
	'webdiscover' =>	'http://webdiscover.ru/share.php?url={link}',
	'yahoo' =>			'http://bookmarks.yahoo.com/toolbar/savebm?u={link}&t={title}&d={descr}',
	'yandex' =>			'http://zakladki.yandex.ru/newlink.xml?url={link}&name={title}&descr={descr}',
	'ya' =>				'http://my.ya.ru/posts_add_link.xml?URL={link}&title={title}&body={descr}',
	'yosmi' =>			'http://yosmi.ru/index.php?do=share&url={link}'
);

// Add social network
// Example: 1) add_share_link('pinterest', 'url');
//			2) add_share_link(array('pinterest'=>'url', 'dribble'=>'url'));
if (!function_exists('axiom_add_share_link')) {
	function axiom_add_share_link($soc, $url='') {
		if (!is_array($soc)) $soc = array($soc => $url);
		global $AXIOM_GLOBALS;
		$AXIOM_GLOBALS['share_links'] = array_merge( $AXIOM_GLOBALS['share_links'], $soc );
	}
}

// Return (and show) share social links
if (!function_exists('axiom_show_share_links')) {
	function axiom_show_share_links($args) {
		if ( axiom_get_custom_option('show_share')=='hide' ) return '';

		$args = array_merge(array(
			'post_id' => 0,						// post ID
			'post_link' => '',					// post link
			'post_title' => '',					// post title
			'post_descr' => '',					// post descr
			'post_thumb' => '',					// post featured image
			'size' => 'small',					// icons size: tiny|small|big
			'style' => 'icons',					// style for show icons: icons|images|bg
			'type' => 'block',					// share block style: list|block|drop
			'popup' => true,					// open share url in new window or in popup window
			'counters' => axiom_get_custom_option('show_share_counters')=='yes',	// show share counters
			'direction' => axiom_get_custom_option('show_share'),				// share block direction
			'caption' => axiom_get_custom_option('share_caption'),				// share block caption
			'share' => axiom_get_theme_option('share_buttons'),					// list of allowed socials
			'echo' => true						// if true - show on page, else - only return as string
			), $args);

		if (count($args['share'])==0 || implode('', $args['share'][0])=='') return '';
		
		global $AXIOM_GLOBALS;

		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];

		$output = '<div class="sc_socials sc_socials_size_'.esc_attr($args['size']).' sc_socials_share' . ($args['type']=='drop' ? ' sc_socials_drop' : ' sc_socials_dir_' . esc_attr($args['direction'])) . '">'
			. ($args['caption']!='' ? '<span class="share_caption">'.($args['caption']).'</span>' : '');

		foreach ($args['share'] as $soc) {
			$icon = $args['style']=='icons' || axiom_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : axiom_get_socials_url(basename($soc['icon']));
			$sn = basename($soc['icon']);
			$sn = $args['style']=='icons' ? axiom_substr($sn, axiom_strrpos($sn, '-')+1) : axiom_substr($sn, 0, axiom_strrpos($sn, '.'));
			if (($pos=axiom_strrpos($sn, '_'))!==false)
				$sn = axiom_substr($sn, 0, $pos);
			$url = empty($soc['url']) && !empty($AXIOM_GLOBALS['share_links'][$sn]) ? $AXIOM_GLOBALS['share_links'][$sn] : $soc['url'];

			$link = str_replace(
				array('{id}', '{link}', '{title}', '{descr}', '{image}'),
				array(
					urlencode($args['post_id']),
					urlencode($args['post_link']),
					urlencode(strip_tags($args['post_title'])),
					urlencode(strip_tags($args['post_descr'])),
					urlencode($args['post_thumb'])
					),
				$url);
			$output .= '<div class="sc_socials_item">'
					. '<a href="'.esc_url($soc['url']).'"'
					. ' class="social_icons icons social_'.esc_attr($sn).'"'
					. ($args['popup'] ? ' onclick="window.open(\'' . esc_url($link) .'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=480, height=400, toolbar=0, status=0\'); return false;"' : ' target="_blank"')
					. ($args['style']=='bg' ? ' style="background-image: url('.esc_url($icon).');"' : '')
					. ($args['counters'] ? ' data-count="'.esc_attr($sn).'"' : '') 
				. '>'
				. ($args['style']=='icons' ? '<span class="icon-' . esc_attr($sn) . '"></span>' : ($args['style']=='images' ? '<img src="'.esc_url($icon).'" alt="'.esc_attr($sn).'" />' : '<span class="sc_socials_hover" style="background-image: url('.esc_url($icon).');"></span>'))
				. '</a>'
				//. ($args['counters'] ? '<span class="share_counter">0</span>' : '') 
				. ($args['type']=='drop' ? '<i>' . trim(axiom_strtoproper($sn)) . '</i>' : '')
				. '</div>';
		}
		$output .= '</div>';
		if ($args['echo']) echo ($output);
		return $output;
	}
}


// Return social icons links
if (!function_exists('axiom_prepare_socials')) {
	function axiom_prepare_socials($list, $style='icons', $quantity=0) {
		$output = '';
		$counter = 0;
		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];
		if (count($list) > 0) {
			foreach ($list as $soc) {
				if (($counter >= $quantity) && ($quantity > 0)) return $output;
				if (empty($soc['url'])) continue;
				$icon = $style=='icons' || axiom_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : axiom_get_socials_url(basename($soc['icon']));
				$sn = basename($soc['icon']);
				if ((axiom_strrpos( $sn, '-' ) > 0 ) or (axiom_strrpos( $sn, '.' ) > 0)) {
					$sn = (($style == 'icons') && (!axiom_strrpos( $sn, '.' ))) ? axiom_substr( $sn, axiom_strrpos( $sn, '-' ) + 1 ) : axiom_substr( $sn, 0, -4);
				}
				if (($pos=axiom_strrpos($sn, '_'))!==false)
					$sn = axiom_substr($sn, 0, $pos);
				$output .= '<div class="sc_socials_item">'
						. '<a href="'.esc_url($soc['url']).'" target="_blank" class="social_icons social_'.esc_attr($sn).''. ($style=='icons' ? ' icons':'') .'"'
						. ($style=='bg' ? ' style="background-image: url('.esc_url($icon).');"' : '')
						. '>'
						. ($style=='icons' ? '<span class="icon-' . esc_attr($sn) . '"></span>' : ($style=='images' ? '<img src="'.esc_url($icon).'" alt="" />' : '<span class="sc_socials_hover" style="background-image: url('.esc_url($icon).');"></span>'))
						. '</a>'
						. '</div>';
				$counter++;
			}
		}
		return $output;
	}
}


/* Social links in user profile
-------------------------------------------------------------------------------- */

// Return (and show) user profiles links
if (!function_exists('axiom_show_user_socials')) {
	function axiom_show_user_socials($args) {
		$args = array_merge(array(
			'author_id' => 0,						// author's ID
			'allowed' => array(),					// list of allowed social
			'size' => 'small',						// icons size: tiny|small|big
			'style' => 'icons',						// style for show icons: icons|images|bg
			'echo' => true							// if true - show on page, else - only return as string
			), is_array($args) ? $args 
				: array('author_id' => $args));		// If send one number parameter - use it as author's ID
		$output = '';
		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];
		$social_list = axiom_get_theme_option('social_icons');
		$list = array();
		foreach ($social_list as $soc) {
			$sn = basename($soc['icon']);
			$sn = $args['style']=='icons' ? axiom_substr($sn, axiom_strrpos($sn, '-')+1) : axiom_substr($sn, 0, axiom_strrpos($sn, '.'));
			if (($pos=axiom_strrpos($sn, '_'))!==false)
				$sn = axiom_substr($sn, 0, $pos);
			if (count($args['allowed'])==0 || in_array($sn, $args['allowed'])) {
				$link = get_the_author_meta('user_' . ($sn), $args['author_id']);
				if ($link) {
					$icon = $args['style']=='icons' || axiom_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : axiom_get_socials_url(basename($soc['icon']));
					$list[] = array(
						'icon'	=> $icon,
						'url'	=> $link
					);
				}
			}
		}
		if (count($list) > 0) {
			$output = '<div class="sc_socials sc_socials_size_'.esc_attr($args['size']).'">' . trim(axiom_prepare_socials($list, $args['style'])) . '</div>';
			if ($args['echo']) echo ($output);
		}
		return $output;
	}
}

// Show additional fields in the user profile
if (!function_exists('axiom_add_fields_in_user_profile')) {
	function axiom_add_fields_in_user_profile( $user ) {
	?>
		<h3><?php _e('User Position', 'axiom'); ?></h3>
		<table class="form-table">
			<tr>
				<th><label for="user_position"><?php _e('User position', 'axiom'); ?>:</label></th>
				<td><input type="text" name="user_position" id="user_position" size="55" value="<?php echo esc_attr(get_the_author_meta('user_position', $user->ID)); ?>" />
					<span class="description"><?php _e('Please, enter your position in the company', 'axiom'); ?></span>
				</td>
			</tr>
		</table>
	
		<h3><?php _e('Social links', 'axiom'); ?></h3>
		<table class="form-table">
		<?php
		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];
		$social_list = axiom_get_theme_option('social_icons');
		foreach ($social_list as $soc) {
			$sn = basename($soc['icon']);
			//$sn = axiom_substr($sn, 0, axiom_strrpos($sn, '.'));
			$sn = axiom_substr( $sn, axiom_strrpos( $sn, '-' ) + 1 );
			if (($pos=axiom_strrpos($sn, '_'))!==false)
				$sn = axiom_substr($sn, 0, $pos);
			?>
			<tr>
				<th><label for="user_<?php echo esc_attr($sn); ?>"><?php echo trim(axiom_strtoproper($sn)); ?>:</label></th>
				<td><input type="text" name="user_<?php echo esc_attr($sn); ?>" id="user_<?php echo esc_attr($sn); ?>" size="55" value="<?php echo esc_attr(get_the_author_meta('user_'.($sn), $user->ID)); ?>" />
					<span class="description"><?php echo sprintf(__('Please, enter your %s link', 'axiom'), axiom_strtoproper($sn)); ?></span>
				</td>
			</tr>
			<?php
		}
		?>
		</table>
	<?php
	}
}

// Save / update additional fields
if (!function_exists('axiom_save_fields_in_user_profile')) {
	function axiom_save_fields_in_user_profile( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;
		update_user_meta( $user_id, 'user_position', $_POST['user_position'] );
		$social_list = axiom_get_theme_option('social_icons');
		foreach ($social_list as $soc) {
			$sn = basename($soc['icon']);
			//$sn = axiom_substr($sn, 0, axiom_strrpos($sn, '.'));
			$sn = axiom_substr( $sn, axiom_strrpos( $sn, '-' ) + 1 );
			if (($pos=axiom_strrpos($sn, '_'))!==false)
				$sn = axiom_substr($sn, 0, $pos);
			update_user_meta( $user_id, 'user_'.($sn), $_POST['user_'.($sn)] );
		}
	}
}
	
	
/* Twitter
-------------------------------------------------------------------------------- */

if (!function_exists('axiom_get_twitter_data')) {
	function axiom_get_twitter_data($cfg) {
		$data = get_transient("twitter_data_".($cfg['mode']));
		if (!$data) {
			require_once( axiom_get_file_dir('lib/tmhOAuth/tmhOAuth.php') );
			$tmhOAuth = new tmhOAuth(array(
				'consumer_key'    => $cfg['consumer_key'],
				'consumer_secret' => $cfg['consumer_secret'],
				'token'           => $cfg['token'],
				'secret'          => $cfg['secret']
			));
			$code = $tmhOAuth->user_request(array(
				'url' => $tmhOAuth->url(axiom_get_twitter_mode_url($cfg['mode']))
			));
			if ($code == 200) {
				$data = json_decode($tmhOAuth->response['response'], true);
				if (isset($data['status'])) {
					$code = $tmhOAuth->user_request(array(
						'url' => $tmhOAuth->url(axiom_get_twitter_mode_url($cfg['oembed'])),
						'params' => array(
							'id' => $data['status']['id_str']
						)
					));
					if ($code == 200)
						$data = json_decode($tmhOAuth->response['response'], true);
				}
				set_transient("twitter_data_".($cfg['mode']), $data, 60*60);
			}
		} else if (!is_array($data) && axiom_substr($data, 0, 2)=='a:') {
			$data = unserialize($data);
		}
		return $data;
	}
}

if (!function_exists('axiom_get_twitter_mode_url')) {
	function axiom_get_twitter_mode_url($mode) {
		$url = '/1.1/statuses/';
		if ($mode == 'user_timeline')
			$url .= $mode;
		else if ($mode == 'home_timeline')
			$url .= $mode;
		return $url;
	}
}

if (!function_exists('axiom_prepare_twitter_text')) {
	function axiom_prepare_twitter_text($tweet) {
		$text = $tweet['text'];
		if (!empty($tweet['entities']['urls']) && count($tweet['entities']['urls']) > 0) {
			foreach ($tweet['entities']['urls'] as $url) {
				$text = str_replace($url['url'], '<a href="'.esc_url($url['expanded_url']).'" target="_blank">' . ($url['display_url']) . '</a>', $text);
			}
		}
		if (!empty($tweet['entities']['media']) && count($tweet['entities']['media']) > 0) {
			foreach ($tweet['entities']['media'] as $url) {
				$text = str_replace($url['url'], '<a href="'.esc_url($url['expanded_url']).'" target="_blank">' . ($url['display_url']) . '</a>', $text);
			}
		}
		return $text;
	}
}

// Return Twitter followers count
if (!function_exists('axiom_get_twitter_followers')) {
	function axiom_get_twitter_followers($cfg) {
		$data = axiom_get_twitter_data($cfg);
		return $data && isset($data[0]['user']['followers_count']) ? $data[0]['user']['followers_count'] : 0;
	}
	// Old version
	/*
	function axiom_get_twitter_followers($account) {
		$tw = get_transient("twitterfollowers");
		if ($tw !== false) return $tw;
		$tw = '?';
		$url = esc_url('https://twitter.com/users/show/'.($account));
		$headers = get_headers($url);
		if (axiom_strpos($headers[0], '200')) {
			$xml = axiom_fgc($url);
			preg_match('/followers_count>(.*)</', $xml, $match);
			if ($match[1] !=0 ) {
				$tw = $match[1];
				set_transient("twitterfollowers", $tw, 60*60);
			}
		}
		return $tw;
	}
	*/
}



/* Facebook
-------------------------------------------------------------------------------- */

if (!function_exists('axiom_get_facebook_likes')) {
	function axiom_get_facebook_likes($account) {
		$fb = get_transient("facebooklikes");
		if ($fb !== false) return $fb;
		$fb = '?';
		$url = esc_url('http://graph.facebook.com/'.($account));
		$headers = get_headers($url);
		if (axiom_strpos($headers[0], '200')) {
			$json = axiom_fgc($url);
			$rez = json_decode($json, true);
			if (isset($rez['likes']) ) {
				$fb = $rez['likes'];
				set_transient("facebooklikes", $fb, 60*60);
			}
		}
		return $fb;
	}
}


// Add facebook meta tags for post/page sharing
function axiom_facebook_og_tags() {
	global $post;
	if ( !is_singular() || axiom_get_global('blog_streampage')) return;
	if (has_post_thumbnail( $post->ID )) {
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>' . "\n";
	}
	//echo '<meta property="og:title" content="' . esc_attr( strip_tags( get_the_title() ) ) . '" />' . "\n"
	//	.'<meta property="og:description" content="' . esc_attr( strip_tags( strip_shortcodes( get_the_excerpt()) ) ) . '" />' . "\n"
	//	.'<meta property="og:url" content="' . esc_attr( get_permalink() ) . '" />';
}


/* Feedburner
-------------------------------------------------------------------------------- */

if (!function_exists('axiom_get_feedburner_counter')) {
	function axiom_get_feedburner_counter($account) {
		$rss = get_transient("feedburnercounter");
		if ($rss !== false) return $rss;
		$rss = '?';
		$url = esc_url('http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri='.($account));
		$headers = get_headers($url);
		if (axiom_strpos($headers[0], '200')) {
			$xml = axiom_fgc($url);
			preg_match('/circulation="(\d+)"/', $xml, $match);
			if ($match[1] != 0) {
				$rss = $match[1];
				set_transient("feedburnercounter", $rss, 60*60);
			}
		}
		return $rss;
	}
}
?>