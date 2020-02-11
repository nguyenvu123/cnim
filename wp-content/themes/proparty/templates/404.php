<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_template_404_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_template_404_theme_setup', 1 );
	function axiom_template_404_theme_setup() {
		axiom_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			),
			'w'		 => null,
			'h'		 => null
			));
	}
}

// Template output
if ( !function_exists( 'axiom_template_404_output' ) ) {
	function axiom_template_404_output() {
		?>
		<article class="post_item post_item_404" style="margin-bottom: 100px;">
			<div class="post_content">
				<h1 class="page_title"><?php _e( '404', 'axiom' ); ?></h1>
				<h2 class="page_subtitle"><?php _e('The requested page cannot be found', 'axiom'); ?></h2>
				<p class="page_description"><?php echo sprintf( __('Can\'t find what you need? <br/>Take a moment and do a search below or start from <a href="%s">our homepage</a>.', 'axiom'), home_url() ); ?></p>
				<div class="page_search"><?php echo do_shortcode('[trx_search style="flat" open="fixed" title="'.__('To search type and hit enter', 'axiom').'"]'); ?></div>
			</div>
		</article>
		<?php
	}
}
?>