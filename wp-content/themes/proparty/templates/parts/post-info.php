			<div class="post_info">
				<?php
				$info_parts = array_merge(array(
					'snippets' => false,	// For singular post/page/team etc.
					'date' => true,
					'author' => true,
					'terms' => true,
					'counters' => true
					), isset($info_parts) && is_array($info_parts) ? $info_parts : array());
									
				if ($info_parts['date']) {
					?>
					<?php
				}
				if ($info_parts['terms'] && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_links)) {
					?>
					<span class="post_info_item post_info_tags"><?php _e('in', 'axiom'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_links); ?></span>
					<?php
				}
				if ($info_parts['counters']) {
					?>
					<span class="post_info_item post_info_counters"><?php require(axiom_get_file_dir('templates/parts/counters.php')); ?></span>
					<?php
				}
				?>
			</div>
