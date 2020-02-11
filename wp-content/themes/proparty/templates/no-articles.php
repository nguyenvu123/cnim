<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_template_no_articles_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_template_no_articles_theme_setup', 1 );
	function axiom_template_no_articles_theme_setup() {
		axiom_add_template(array(
			'layout' => 'no-articles',
			'mode'   => 'internal',
			'title'  => __('No articles found', 'axiom'),
			'w'		 => null,
			'h'		 => null
		));
	}
}

// Template output
if ( !function_exists( 'axiom_template_no_articles_output' ) ) {
	function axiom_template_no_articles_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php _e('No posts found', 'axiom'); ?></h2>
				<p><?php _e( 'Sorry, but nothing matched your search criteria.', 'axiom' ); ?></p>
				<p><?php echo sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'axiom'), home_url(), get_bloginfo()); ?>
				<br><?php _e('Please report any broken links to our team.', 'axiom'); ?></p>
				<?php echo do_shortcode('[trx_search open="fixed"]'); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>