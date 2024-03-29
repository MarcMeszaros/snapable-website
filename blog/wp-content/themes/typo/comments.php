<?php
/**
 * @package WordPress
 * @subpackage Theme_Compat
 * @deprecated 3.0
 *
 * This file is here for Backwards compatibility with old themes and will be removed in a future version
 *
 */
_deprecated_file( sprintf( __( 'Theme without %1$s' ), basename(__FILE__) ), '3.0', null, sprintf( __('Please include a %1$s template in your theme.'), basename(__FILE__) ) );

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.'); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<h3 id="comments"><?php	printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number() ), number_format_i18n( get_comments_number() ), '&#8220;' . get_the_title() . '&#8221;' ); ?></h3>

	<ol class="commentlist">
		<?php wp_list_comments("callback=mytheme_comment"); ?>
	</ol>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<div id="respond">
	<h3><?php comment_form_title( __('Leave a Reply'), __('Leave a Reply to %s' ) ); ?></h3>
	
	<div id="cancel-comment-reply">
		<small><?php cancel_comment_reply_link() ?></small>
	</div>
	
	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
	<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url( get_permalink() )); ?></p>
	<?php else : ?>
	
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
	
	<!-- If user is logged in -->
	<?php if ( is_user_logged_in() ) : ?>
	
	<p class="logged-in"><?php printf(__('Logged in as <a href="%1$s">%2$s</a>.'), get_option('siteurl') . '/wp-admin/profile.php', $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account'); ?>"><?php _e('Log out &raquo;'); ?></a></p>
	
	<!-- If user is not logged in -->
	<?php else : ?>
	
	<!-- Comment inputs -->
	<p><input type="text" name="author" id="author" onfocus="if (this.value == 'Name (required)') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Name (required)';}" value="Name (required)" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> /></p>
	
	<p><input type="text" name="email" id="email" onfocus="if (this.value == 'Email (required)') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Email (required)';}" value="Email (required)" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> /></p>
	
	<p><input type="text" name="url" id="url" onfocus="if (this.value == 'Website') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Website';}" value="Website" size="22" tabindex="3" /></p>
	
	<?php endif; ?>
	
	<!--<p><small><?php printf(__('<strong>XHTML:</strong> You can use these tags: <code>%s</code>'), allowed_tags()); ?></small></p>-->
	
	<p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>
	
	<p style="margin-bottom:0px;"><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment &rarr;'); ?>" />
	<?php comment_id_fields(); ?>
	</p>
	<?php do_action('comment_form', $post->ID); ?>
	
	</form>
	
	<?php endif; // If registration required and not logged in ?>

</div>

<?php endif; // if you delete this the sky will fall on your head ?>
