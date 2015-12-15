<article id="post-<?php the_ID(); ?>">
	<?php if((is_search()||mob_is_blog()) && has_post_thumbnail()):?>
		<a class="article-thumb" href="<?php the_permalink();?>" rel="bookmark"><?php the_post_thumbnail(array(150,150)); ?></a>
	<?php endif;?>
	<header>
		<h1><a href="<?php the_permalink();?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php if(!is_page()):?>
			<p><?php echo str_replace(array(
				'[date]','[time]','[user]','[cats]','[tags]'
			),array(
				get_the_time(get_option('date_format')),
				get_the_time(get_option('time_format')),
				get_the_author_link( get_the_author_meta('ID')), 
				get_the_category_list(' '),
				get_the_tag_list('', ' ')
			),get_option('mob_general_meta_template', MOB_DEFAULT_META_TEMPLATE)); ?></p>
		<?php endif;?>
	</header> <?php // end article header ?>
	<section class="content">
		<?php
		if((is_search() || mob_is_blog())  && get_option('rss_use_excerpt'))
		{
			the_excerpt();
		}
		else
		{
			the_content();
			the_tags();
		}			
		// Link Pages is used in case you have posts that are set to break into multiple pages. You can remove this if you don't plan on doing that. Also, breaking content up into multiple pages is a horrible experience,  so don't do it. While there are SOME edge cases where this is useful, it's mostly used for people to get more ad views. It's up to you but if you want to do it, you're wrong and I hate you. (Ok, I still love you but just not as much) http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
		wp_link_pages(array('before'=>'<div class="page-links"><span class="page-links-title">'.__( 'Pages:', MOB_NS ).'</span>','after'=>'</div>','link_before'=>'<span>','link_after'=>'</span>',));?>			
	</section>
	<?php comments_template(); ?>
</article>