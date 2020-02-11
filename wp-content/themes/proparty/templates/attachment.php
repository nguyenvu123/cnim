<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'axiom_template_attachment_theme_setup' ) ) {
	add_action( 'axiom_action_before_init_theme', 'axiom_template_attachment_theme_setup', 1 );
	function axiom_template_attachment_theme_setup() {
		axiom_add_template(array(
			'layout' => 'attachment',
			'mode'   => 'internal',
			'title'  => __('Attachment page layout', 'axiom'),
			'thumb_title'  => __('Fullsize image', 'axiom'),
			'w'		 => 1150,
			'h'		 => null,
			'h_crop' => 455
		));
	}
}

// Template output
if ( !function_exists( 'axiom_template_attachment_output' ) ) {
	function axiom_template_attachment_output($post_options, $post_data) {
		$post_data['post_views']++;
		$title_tag = axiom_get_custom_option('show_page_top')=='yes' && axiom_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';
		?>
		<article <?php post_class('post_item post_item_attachment template_attachment'); ?>>

		<?php $post_icon = axiom_get_custom_option('show_post_icon')=='yes' ? '<span class="post_icon '. esc_attr($post_data['post_icon']) .'"></span>':''; ?>

			<<?php echo esc_html($title_tag); ?> class="post_title"><?php echo balanceTags($post_icon)?><?php echo !empty($post_data['post_excerpt']) ? strip_tags($post_data['post_excerpt']) : $post_data['post_title']; ?></<?php echo esc_html($title_tag); ?>>

			<div class="post_featured">
				<div class="post_thumb post_nav" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<?php
					echo ($post_data['post_thumb']);
					$post = get_post();
					$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
					foreach ( $attachments as $k => $attachment ) {
						if ( $attachment->ID == $post->ID )
							break;
					}
					if ( isset( $attachments[ $k-1 ] ) ) {
						$link = get_permalink( $attachments[ $k-1 ]->ID ).'#top_of_page';
						$desc = axiom_strshort(!empty($attachments[ $k-1 ]->post_excerpt) ? $attachments[ $k-1 ]->post_excerpt : $attachments[ $k-1 ]->post_title, 30);
						?>
						<a class="post_nav_item post_nav_prev" href="<?php echo esc_url($link); ?>">
							<span class="post_nav_info">
								<span class="post_nav_info_title"><?php _e('Previous item', 'axiom'); ?></span>
								<span class="post_nav_info_description"><?php echo ($desc); ?></span>
							</span>
						</a>
						<?php
					}
					if ( isset( $attachments[ $k+1 ] ) ) {
						$link = get_permalink( $attachments[ $k+1 ]->ID ).'#top_of_page';
						$desc = axiom_strshort(!empty($attachments[ $k+1 ]->post_excerpt) ? $attachments[ $k+1 ]->post_excerpt : $attachments[ $k+1 ]->post_title, 30);
						?>
						<a class="post_nav_item post_nav_next" href="<?php echo esc_url($link); ?>">
							<span class="post_nav_info">
								<span class="post_nav_info_title"><?php _e('Next item', 'axiom'); ?></span>
								<span class="post_nav_info_description"><?php echo ($desc); ?></span>
							</span>
						</a>
						<?php
					}
					?>
				</div>
			</div>
		
			<?php
			if (!$post_data['post_protected'] && axiom_get_custom_option('show_post_info')=='yes') {
				require(axiom_get_file_dir('templates/parts/post-info.php'));
			}
			?>
		
			<div class="post_content">
				<?php
				// Post content
				if ($post_data['post_protected']) { 
					echo ($post_data['post_excerpt']);
				} else {
					echo ($post_data['post_content'] ? $post_data['post_content'] : __('No image description ...', 'axiom'));
					wp_link_pages( array( 
						'before' => '<div class="nav_pages_parts"><span class="pages">' . __( 'Pages:', 'axiom' ) . '</span>',
						'after' => '</div>',
						'link_before' => '<span class="page_num">',
						'link_after' => '</span>'
					) ); 
					
					if ( axiom_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
						?>
						<div class="post_info post_info_bottom">
							<span class="post_info_item post_info_tags"><?php _e('Tags:', 'axiom'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
						</div>
						<?php 
					}
				}
				?>
			
			</div>	<!-- /.post_content -->
		
		</article>

		<section class="related_wrap related_wrap_empty"></section>

		<?php	
		if (!$post_data['post_protected']) {
			require(axiom_get_file_dir('templates/parts/comments.php'));
		}

		require(axiom_get_file_dir('templates/parts/views-counter.php'));
	}
}
?>