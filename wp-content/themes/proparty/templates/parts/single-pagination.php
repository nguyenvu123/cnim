<?php
wp_link_pages( array(
	'before' => '<nav class="pagination_single" role="navigation"><span class="pager_pages">' . __( 'Pages:', 'axiom' ) . '</span>',
	'after' => '</nav>',
	'link_before' => '<span class="pager_numbers">',
	'link_after' => '</span>'
	)
);
?>