<?php
global $wrapperclass;
if(!$wrapperclass)
{
	$wrapperclass = 'sb'.(is_active_sidebar('sidebar1')+is_active_sidebar('sidebar2'));
}
?>
<div class="<?php echo $wrapperclass;?>">
<?php if ( have_posts() ) : ?>
	<?php if(is_search()):?>
		<header>
			<h1><?php printf(__('Search Results for: %s', MOB_NS), get_search_query()); ?></h1>
		</header>
	<?php endif;?>	
	<?php if(is_category()):?>
		<header>
			<h1><?php printf(__('Category: %s', MOB_NS), single_cat_title('', false)); ?></h1>
		</header>
	<?php endif;?>
	<?php if(is_tag()):?>
		<header>
			<h1><?php printf(__('Tag: %s', MOB_NS), single_tag_title('', false)); ?></h1>
		</header>
	<?php endif;?>
	<?php while (have_posts()) : the_post();?>
        <?php get_template_part('content','article');?>
	<?php endwhile;?> 
	<?php if(1 || is_search()) mob_page_navi();?>
<?php elseif(is_404()) : ?>
	<?php mob_404();?>
<?php else : ?>
	<article>
		<header>
			<h1><?php _e('Oops, Post Not Found!', MOB_NS ); ?></h1>
		</header>
		<section>
			<p><?php _e('Uh Oh. Something is missing. Try double checking things.', MOB_NS ); ?></p>
		</section>
	</article>
<?php endif;?></div>