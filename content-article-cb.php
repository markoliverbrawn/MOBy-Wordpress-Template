<?php
global $post;

$title = the_title('', '', false);/* Filter: the_title, we don't use OLWPT_theTitle for a very good reason! */
$excerpt = get_the_excerpt();/* Filter: get_the_excerpt */
$permalink = apply_filters('ols_get_permalink', get_permalink());
$has_thumb = apply_filters('ols_has_post_thumbnail', has_post_thumbnail());// This test isn't working, so am testing on $the_thumb below now
$class = implode(' ', get_post_class('result media'));/* Filter: post_class */
$is_cb = false;
if(isset($post->cb_link)) 
{
    $is_cb = true;
}
$the_thumb = apply_filters('ols_get_the_post_thumbnail', get_the_post_thumbnail(null, 'thumbnail'));
if(!$the_thumb)
{
    $the_thumb = '';//<svg viewBox="0 0 300 300"><use xlink:href="'.get_template_directory_uri().'/img/default-thumb.svg#svg2999"></use></svg>';
}
$class.= ' '.($the_thumb ? 'has' : 'no').'-thumb';
$meta = sprintf('<span class="dates">'.get_the_date().'</span> <span class="times">'.get_the_time().'</span>');
// If it's been updated add the updated date-time to meta
if(get_the_date() != get_the_modified_date() || get_the_time() != get_the_modified_time())
{
    $meta.= ' <span class="updated">'.get_the_modified_date().' '.get_the_modified_time().'</span>';
}
if(apply_filters('ols_comment_status', 'open' == $post->comment_status))
{
    $meta .= ' <a class="leavecomment" href="'.$permalink.'#comments" title="Leave a Comment">Leave a Comment</a>';
}
$meta = apply_filters('ols_search_post_meta', $meta);

global $drawn;// YAK! Nasty! Urgh! Splurge! *sick*
if($drawn && (is_single() || cb_is('getrecord')))
{
    return;
}
$drawn = true;

?>
<article id="post-<?php the_ID(); ?>">
	<?php if((is_search()||mob_is_blog()) && $the_thumb):?>
		<a class="article-thumb" href="<?php echo $permalink;?>" rel="bookmark"><?php echo $the_thumb; ?></a>
	<?php endif;?>
	<header>
        <h1><a href="<?php echo $permalink;?>" rel="bookmark"><?php echo cb_is(CB_GETRECORD) ? cb_getrecord_title() : get_the_title(); ?></a></h1>
		<?php if(!is_page()):?>
			<p>
                <?php if($is_cb)
                    {
                        echo $meta;
                    }
                    else
                    {
                        echo str_replace(array(
                            '[date]','[time]','[user]','[cats]','[tags]'
                        ),array(
                            get_the_time(get_option('date_format')),
                            get_the_time(get_option('time_format')),
                            get_the_author_link( get_the_author_meta('ID')), 
                            get_the_category_list(' '),
                            get_the_tag_list('', ' ')
                        ),get_option('mob_general_meta_template', MOB_DEFAULT_META_TEMPLATE)); 
                    }
                ?>
            </p>
		<?php endif;?>
	</header> <?php // end article header ?>
	<section class="content">
		<?php
		if((is_search() || mob_is_blog())  && get_option('rss_use_excerpt'))
		{          
            if(!$excerpt)
            {
                $excerpt = '<i style="opacity:0.5">No description</i>';
            }
            echo '<p>'.$excerpt.'</p>';
		}
		else
		{
            if(cb_is(CB_GETRECORD))
            {
                
                cb_getrecord_the_content();
            }
            else
            {
                the_content();
                the_tags();
            }
		}			
		// Link Pages is used in case you have posts that are set to break into multiple pages. You can remove this if you don't plan on doing that. Also, breaking content up into multiple pages is a horrible experience,  so don't do it. While there are SOME edge cases where this is useful, it's mostly used for people to get more ad views. It's up to you but if you want to do it, you're wrong and I hate you. (Ok, I still love you but just not as much) http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
		wp_link_pages(array('before'=>'<div class="page-links"><span class="page-links-title">'.__( 'Pages:', MOB_NS ).'</span>','after'=>'</div>','link_before'=>'<span>','link_after'=>'</span>',));?>			
	</section>
    <?php
    if(!cb_is(CB_GETRECORD))
    {
        comments_template(); 
    }
    ?>
</article>