<?php
/*
The comments page for Bones
*/

// don't load it if you can't comment
if ( post_password_required() ) {
  return;
}

?>
<section class="comments">
	<?php if ( have_comments() ) : ?>
		<h3><?php comments_number( __( '<span>No</span> Comments', 'bonestheme' ), __( '<span>One</span> Comment', 'bonestheme' ), __( '<span>%</span> Comments', MOB_NS ) );?></h3>
	    <section class="commentlist">
	      <?php
	        wp_list_comments( array(
	          'style'             => 'div',
	          'short_ping'        => true,
	          'avatar_size'       => 40,
	          'callback'          => 'mob_comments',
	          'type'              => 'all',
	          'reply_text'        => 'Reply',
	          'page'              => '',
	          'per_page'          => '',
	          'reverse_top_level' => null,
	          'reverse_children'  => ''
	        ) );
	      ?>
	    </section>
	    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	    	<nav class="navigation comment-navigation" role="navigation">
	      		<div class="comment-nav-prev"><?php previous_comments_link( __( '&larr; Previous Comments', MOB_NS ) ); ?></div>
	      		<div class="comment-nav-next"><?php next_comments_link( __( 'More Comments &rarr;', MOB_NS ) ); ?></div>
	    	</nav>
	    <?php endif; ?>
	    <?php if ( ! comments_open() ) : ?>
	    	<p class="no-comments"><?php _e( 'Comments are closed.' , MOB_NS ); ?></p>
	    <?php endif; ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</section>