<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. 
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;

if ( have_comments() || comments_open() ) {
	?>
	<section class="comments_wrap">
	<?php
	if ( have_comments() ) {
	?>
		<div id="comments" class="comments_list_wrap">
			<h2 class="section_title comments_list_title"><?php $post_comments = get_comments_number(); echo esc_attr($post_comments); ?> <?php echo esc_attr($post_comments==1 ? __('Comment', 'axiom') : __('Comments', 'axiom')); ?></h2>
			<ul class="comments_list">
				<?php
				wp_list_comments( array('callback'=>'axiom_output_single_comment') );
				?>
			</ul><!-- .comments_list -->
			<?php if ( !comments_open() && get_comments_number()!=0 && post_type_supports( get_post_type(), 'comments' ) ) { ?>
				<p class="comments_closed"><?php _e( 'Comments are closed.', 'axiom' ); ?></p>
			<?php }	?>
			<div class="comments_pagination"><?php paginate_comments_links(); ?></div>
		</div><!-- .comments_list_wrap -->
	<?php 
	}

	if ( comments_open() ) {
	?>
		<div class="comments_form_wrap">
			<h2 class="section_title comments_form_title"><?php _e('Add Comment', 'axiom'); ?></h2>
			<div class="comments_form">
				<?php
				$commenter = wp_get_current_commenter();
				$req = get_option( 'require_name_email' );
				$aria_req = ( $req ? ' aria-required="true"' : '' );
				$comments_args = array(
						// change the id of send button 
						'id_submit'=>'send_comment',
						// change the title of send button 
						'label_submit'=>__('Post Comment', 'axiom'),
						// change the title of the reply section
						'title_reply'=>'',
						// remove "Logged in as"
						'logged_in_as' => '',
						// remove text before textarea
						'comment_notes_before' => '<p class="comments_notes">'.__('Your email address will not be published. Required fields are marked *', 'axiom').'</p>',
						// remove text after textarea
						'comment_notes_after' => '',
						// redefine your own textarea (the comment body)
						'comment_field' => '<div class="comments_field comments_message">'
											. '<label for="comment" class="required">' . __('Your Message', 'axiom') . '</label>'
											. '<textarea id="comment" name="comment" placeholder="' . __( 'Comment', 'axiom' ) . '" aria-required="true"></textarea>'
										. '</div>',
						'fields' => apply_filters( 'comment_form_default_fields', array(
							'author' => '<div class="columns_wrap"><div class="comments_field comments_author column-1_2">'
									. '<label for="author"' . ( $req ? ' class="required"' : '' ). '>' . __( 'Name', 'axiom' ) . '</label>'
									. '<input id="author" name="author" type="text" placeholder="' . __( 'Name', 'axiom' ) . ( $req ? ' *' : '') . '" value="' . esc_attr( isset($commenter['comment_author']) ? $commenter['comment_author'] : '' ) . '" size="30"' . ($aria_req) . ' />'
									. '</div>',
							'email' => '<div class="comments_field comments_email column-1_2">'
									. '<label for="email"' . ( $req ? ' class="required"' : '' ) . '>' . __( 'Email', 'axiom' ) . '</label>'
									. '<input id="email" name="email" type="text" placeholder="' . __( 'Email', 'axiom' ) . ( $req ? ' *' : '') . '" value="' . esc_attr(  isset($commenter['comment_author_email']) ? $commenter['comment_author_email'] : '' ) . '" size="30"' . ($aria_req) . ' />'
									. '</div>',
							'url' => '<div class="comments_field comments_site column-1_1">'
									. '<label for="url" class="optional">' . __( 'Website', 'axiom' ) . '</label>'
									. '<input id="url" name="url" type="text" placeholder="' . __( 'Website', 'axiom' ) . '" value="' . esc_attr(  isset($commenter['comment_author_url']) ? $commenter['comment_author_url'] : '' ) . '" size="30"' . ($aria_req) . ' />'
									. '</div>'
									. '</div>'
						) )
				);
			
				comment_form($comments_args);
				?>
			</div>
		</div><!-- /.comments_form_wrap -->
	<?php 
	}
	?>
	</section><!-- /.comments_wrap -->
<?php 
}
?>