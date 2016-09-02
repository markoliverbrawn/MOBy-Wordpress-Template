<?php
define('MOB_NS','mob');
define('MOB_DEFAULT_META_TEMPLATE', 'Posted [date] [time] by [user]. Filed under [cats]. Tagged as [tags]');

if ( ! isset( $content_width ) )
{
	$content_width = 640;
}

// Components
require_once('com/slider/slider.php');
require_once('com/boxes/boxes.php');
require_once('com/countdown/countdown.php');
require_once('com/shortcodes/shortcodes.php');
if(is_admin())
{
	require_once( 'com/admin/admin.php' );
}

// Thumbnail sizes (add them in mob_custom_image_sizes for them to appear in the media dropdown
add_image_size('bones-thumb-600', 600, 150, true);
add_image_size('bones-thumb-300', 300, 100, true);

// Actions
add_action('wp_enqueue_scripts', 'mob_site_styles', 100);
add_action('wp_print_styles', 'mob_fonts');
add_action('after_setup_theme', 'mob_init');
add_filter('image_size_names_choose', 'mob_custom_image_sizes');
add_filter('widget_text', 'do_shortcode');
add_filter('loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );
add_action('login_enqueue_scripts', 'mob_login_css', 10);
add_filter('login_headerurl', 'mob_login_url');
add_filter('login_headertitle', 'mob_login_title');
add_filter('template_include', 'mob_404_page_template', 99 );

/**
 * Load the 404 custom page
 *
 * @return void
 */
function mob_404()
{
	$title = __('Epic 404 - Article Not Found', MOB_NS);
	$content = __('The article you were looking for was not found, but maybe try looking again!', MOB_NS);
	$option = get_option('mob_general_404_page');
	if($page = get_page($option))
	{
		$title = apply_filters('the_title', $page->post_title);
		$content = apply_filters('the_content', $page->post_content);
	}
	
	?><article>
		<header>
			<h1><?php echo $title; ?></h1>
		</header>
		<section>
			<p><?php echo $content; ?></p>
		</section>
	</article><?php
}
/**
 * Force loading of the custom 404 page template
 *
 * @param string $template
 *
 * @return string
 */
function mob_404_page_template($template) 
{
	if(is_404()) 
	{
		$option = get_option('mob_general_404_page');
		if($new_template = get_post_meta($option, '_wp_page_template', true))
		{
			$new_template = locate_template(array($new_template));
			if(''!=$new_template)
			{
				return $new_template;
			}
		}
	}
	return $template;
}
/**
 * Remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
 *
 * @param string $content
 *
 * @return string
 */
function mob_cleanup_filter_ptags_on_images($content)
{
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
/**
 * This removes the annoying […] to a Read More link
 *
 * @return string
 */
function mob_cleanup_excerpt_more($more)
{
	global $post;
	// edit here if you like
	return '...  <a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __( 'Read ', MOB_NS ) . get_the_title($post->ID).'">'. __( 'Read more &raquo;', MOB_NS ) .'</a>';
}
/**
 * Remove injected CSS from gallery
 *
 * @param string $css
 *
 * @return string
 */
function mob_cleanup_gallery_style($css) 
{
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}
/**
 * The default wordpress head is a mess. Let's clean it up by Removing all the junk we don't need.
 * 
 * @return void
 */
function mob_cleanup_head() 
{
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// index link
	remove_action( 'wp_head', 'index_rel_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// remove WP version from css
	add_filter( 'style_loader_src', 'mob_cleanup_remove_wp_ver_css_js', 9999 );
	// remove Wp version from scripts
	add_filter( 'script_loader_src', 'mob_cleanup_remove_wp_ver_css_js', 9999 );

}
/**
 * Remove injected CSS from recent comments widget
 *
 * @return void
 */
function mob_cleanup_recent_comments_style()
{
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments']))
	{
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}
/**
 * Remove injected CSS for recent comments widget
 *
 * @return void
 */
function mob_cleanup_wp_widget_recent_comments_style()
{
	if(has_filter('wp_head', 'wp_widget_recent_comments_style'))
	{
		remove_filter('wp_head', 'wp_widget_recent_comments_style');
	}
}
/**
 * Remove WP version from scripts
 *
 * @param string $src
 *
 * @return string
 */
function mob_cleanup_remove_wp_ver_css_js($src)
{
	if(strpos($src,'ver='))
	{
		$src = remove_query_arg('ver', $src);
	}
	return $src;
}
/**
 * Log debug messages
 * 
 * @param mixed $message
 * 
 * @return void
 */
function mob_log($message) 
{
    if (WP_DEBUG === true) 
    {
        file_put_contents('debug.log', $message, FILE_APPEND);
    }
}
/**
 * Read a file
 * 
 * @param string $filename
 * 
 * @return boolean
 */
function mob_file_read($filename)
{
	global $wp_filesystem;
	
	if(file_exists($filename))
		return @$wp_filesystem->get_contents($filename);
	
	//return file_get_contents($filename);
}
/**
 * Write to a file
 * 
 * @param string $filename
 * @param string $content
 * 
 * @return boolean
 */
function mob_file_write($filename, $content)
{
	// by this point, the $wp_filesystem global should be working, so let's use it to create a file
	global $wp_filesystem;
	
	return @$wp_filesystem->put_contents($filename,$content, FS_CHMOD_FILE);
	
	//return file_put_contents($filename, $content);
}
/**
 * This is a modification of a function found in the twentythirteen theme where we can declare some
 * external fonts. If you're using Google Fonts, you can replace these fonts, change it in your scss 
 * files and be up and running in seconds.
 * 
 * @return void
 */
function mob_fonts()
{
	wp_register_style('googleFonts', '//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
	wp_enqueue_style('googleFonts');
}
/**
 * Get the assets directory
 * 
 * @param string $key
 * 
 * @return string
 */
function mob_get_assets_directory($key='basedir')
{
	$upload_dir = wp_upload_dir();
	
	//print_r($upload_dir['basedir']);die();
	
	$basedir = trailingslashit($upload_dir['basedir']).'moby';
	
	// Fix for mapped domains pointing to parent domain which breaks fonts
	foreach(array('url','baseurl') as $k)
	{
		$parse_url = parse_url($upload_dir[$k]);
		if(!SUBDOMAIN_INSTALL && $k=='baseurl')
		{
			$upload_dir[$k] = $parse_url['scheme'].'://'.$parse_url['host'].$parse_url['path'];
		}
		else 
		{
			$upload_dir[$k] = $parse_url['path'];
		}
	}
	
	if(!is_dir($basedir))
	{
		mkdir($basedir);
		mkdir($basedir.'/css');
		mkdir($basedir.'/css/src');
		mkdir($basedir.'/img');
		mkdir($basedir.'/js');
		mkdir($basedir.'/js/src');
		file_put_contents($basedir.'/css/src/styles.scss','');
	}
	return trailingslashit($upload_dir[$key]).'moby/';
}
/**
 * Get everything up and running.
 *
 * @return void
 */
function mob_init() 
{
	//Allow editor style.
	add_editor_style();
	// Language support, if required
	//load_theme_textdomain( MOB_NS, get_template_directory() . '/library/translation' );
	// launching operation cleanup
	add_action( 'init', 'mob_cleanup_head' );
	// A better title
	add_filter( 'wp_title', 'mob_title', 10, 3 );
	// remove WP version from RSS
	add_filter( 'the_generator', 'mob_rss_version' );
	// remove pesky injected css for recent comments widget
	add_filter( 'wp_head', 'mob_cleanup_wp_widget_recent_comments_style', 1 );
	// clean up comment styles in the head
	add_action( 'wp_head', 'mob_cleanup_recent_comments_style', 1 );
	// clean up gallery output in wp
	add_filter( 'gallery_style', 'mob_cleanup_gallery_style' );
	// enqueue base scripts and styles
	add_action( 'wp_enqueue_scripts', 'mob_scripts_and_styles', 999 );
	// launching this stuff after theme setup
	mob_theme_support();
	// adding sidebars to Wordpress (these are created in functions.php)
	add_action( 'widgets_init', 'mob_register_sidebars' );
	// cleaning up random code around images
	add_filter( 'the_content', 'mob_cleanup_filter_ptags_on_images' );
	// cleaning up excerpt
	add_filter( 'excerpt_more', 'mob_cleanup_excerpt_more' );
}
function mob_is_blog() 
{
	global  $post;
	$posttype = get_post_type($post );
	return ( 
		(
			is_archive() || is_author() || is_category() || is_home() || /*is_single() ||*/ is_tag()
		) && ( 
			$posttype == 'post')  
		) ? true : false ;
}
/**
 * Built in numeric Page Navi (built into the theme by default)
 *
 * @return void
 */
function mob_page_navi()
{
	global $wp_query;
	$bignum = 999999999;
	if ( $wp_query->max_num_pages <= 1 )
		return;
	echo '<nav class="pagination">';
	echo paginate_links( array(
			'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
			'format'       => '',
			'current'      => max( 1, get_query_var('paged') ),
			'total'        => $wp_query->max_num_pages,
			'prev_text'    => '&larr;',
			'next_text'    => '&rarr;',
			'type'         => 'list',
			'end_size'     => 3,
			'mid_size'     => 3
	) );
	echo '</nav>';
}
/**
 * Register Sidebars & Widgetizes Areas
 * 
 * @return void
 */
function mob_register_sidebars() 
{
	register_sidebar(array(
		'id' => 'header',
		'name' => __( 'Header', MOB_NS ),
		'description' => __( 'Header Widgets.', MOB_NS ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Left Content Sidebar', MOB_NS ),
		'description' => __( 'The middle left sidebar.', MOB_NS ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Right Content Sidebar', MOB_NS ),
		'description' => __( 'The middle right sidebar.', MOB_NS ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'id' => 'footer',
		'name' => __( 'Footer', MOB_NS ),
		'description' => __( 'Footer Widget Area.', MOB_NS ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
} // don't remove this bracket!
/**
 * Related Posts Function (call using mob_related_posts();)
 *
 * @return void
 */
function mob_related_posts()
{
	echo '<ul id="bones-related-posts">';
	global $post;
	$tags = wp_get_post_tags( $post->ID );
	if($tags) {
		foreach( $tags as $tag ) {
			$tag_arr .= $tag->slug . ',';
		}
		$args = array(
				'tag' => $tag_arr,
				'numberposts' => 5, /* you can change this to show more */
				'post__not_in' => array($post->ID)
		);
		$related_posts = get_posts( $args );
		if($related_posts) {
			foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
				<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; }
		else { ?>
			<?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', MOB_NS ) . '</li>'; ?>
		<?php }
	}
	wp_reset_postdata();
	echo '</ul>';
}
/**
 * Remove WP version from RSS
 *
 * @return string
 */
function mob_rss_version()
{
	return '';
}
/**
 * Enqueue scripts - loading modernizr and jquery, and reply script
 * 
 * @return void
 */
function mob_scripts_and_styles() {

	// Call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way
	global $wp_styles; 

	if (!is_admin()) 
	{
		// modernizr (without media query polyfill)
		wp_register_script(MOB_NS.'-modernizr', get_stylesheet_directory_uri() . '/js/modernizr.min.js', array(), '2.5.3', false );

		// register main stylesheet
		wp_register_style(MOB_NS.'-stylesheet', get_stylesheet_directory_uri() . '/css/style.css', array(), '', 'all' );

		// ie-only style sheet
		wp_register_style(MOB_NS.'-ie-only', get_stylesheet_directory_uri() . '/css/ie.css', array(), '' );

		// comment reply script for threaded comments
		if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) 
		{
			wp_enqueue_script( 'comment-reply' );
		}

		wp_enqueue_script('jquery');
		//adding scripts file in the footer
		wp_register_script(MOB_NS.'-js', get_stylesheet_directory_uri() . '/js/scripts.min.js', array('jquery'), '', false);

		
		// enqueue styles and scripts
		wp_enqueue_script(MOB_NS.'-js' );
		wp_enqueue_script(MOB_NS.'-modernizr' );
		wp_enqueue_style(MOB_NS.'-stylesheet' );
		wp_enqueue_style(MOB_NS.'-ie-only' );

		// add conditional wrapper around ie stylesheet
		$wp_styles->add_data(MOB_NS.'-ie-only', 'conditional', 'lt IE 9' );

		// Probably better to use a plugin to call jQuery using the google cdn. That way it stays cached and site will load faster.
		//wp_enqueue_script('jquery');
		//wp_enqueue_script(MOB_NS.'-js');
		
		
	}
}
/**
 * Adding WP 3+ Functions & Theme Support
 * 
 * @return void
 */
function mob_theme_support() 
{
	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// default thumb size
	set_post_thumbnail_size(125, 125, true);

	
	// wp custom background (thx to @bransonwerner for update)
	/*add_theme_support('custom-background', array(
		'default-image' => '',    // background image default
		'default-color' => '',    // background color default (dont add the #)
		'wp-head-callback' => '_custom_background_cb',
		'admin-head-callback' => '',
		'admin-preview-callback' => ''
	));*/

	// rss thingy
	add_theme_support('automatic-feed-links');
	
	// WooCommerce
	add_theme_support( 'woocommerce' );

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// adding post format support
	/*add_theme_support('post-formats', array(
		'aside',	// title less blurb
		'gallery',	// gallery of images
		'link',		// quick link to other site
		'image',	// an image
		'quote',	// a quick quote
		'status',	// a Facebook like status update
		'video',	// video
		'audio',	// audio
		'chat'		// chat transcript
	));

	// wp menus
	add_theme_support('menus');*/

	// registering wp3+ menus
	/* Not used: Use widgets instead
	register_nav_menus(array(
		'main-nav' => __( 'The Main Menu', MOB_NS ),   // main nav in header
		'footer-nav' => __( 'Footer Links', MOB_NS ) // secondary nav in footer
		)
	);
	*/
}
/**
 * A better title
 * http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html
 *
 * @param string $title
 * @param string $sep
 * @param string $selocation
 *
 * @return string
 */
function mob_title( $title, $sep, $seplocation )
{
	global $page, $paged;

	// Don't affect in feeds.
	if ( is_feed() ) return $title;

	// Add the blog's name
	if ( 'right' == $seplocation )
	{
		$title .= get_bloginfo( 'name' );
	}
	else
	{
		$title = get_bloginfo( 'name' ) . $title;
	}

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );

	if ( $site_description && ( is_home() || is_front_page() ) )
	{
		$title .= " {$sep} {$site_description}";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
	{
		$title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
	}

	return $title;
}
/**
 * Add the ability to use the dropdown menu to select the new images sizes 
 * from within the media manager when media is added to content blocks. 
 * If you add more image sizes, duplicate one of the lines in the array 
 * and name it according to your new image size.
 * 
 * @param array $sizes
 * 
 * @return array
 */
function mob_custom_image_sizes($sizes) 
{
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
    ) );
}
/**
 * Comment Layout
 * 
 * @param string  $comment
 * @param array   $args
 * @param integer $depth
 * 
 * @return void
 */
function mob_comments( $comment, $args, $depth ) 
{
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/img/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', MOB_NS ), get_comment_author_link(), edit_comment_link(__( '(Edit)', MOB_NS ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', MOB_NS )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', MOB_NS ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!
/**
 * Main content template
 * 
 * @param string $class
 * 
 * @return void
 */
function __mob_template_content($class='')
{
	?><div class="<?php echo $class;?>">
	<?php if ( have_posts() ) : ?>
		<?php if(is_search()):?>
			<header>
				<h1><?php printf( __( 'Search Results for: %s', MOB_NS ), get_search_query() ); ?></h1>
			</header>
		<?php endif;?>	
		<?php while (have_posts()) : the_post(); ?>
			<?php get_template_part('content','article');?>
		<?php endwhile;?> 
		<?php if(1 || is_search()) mob_page_navi();?>
	<?php else : ?>
		<article id="post-not-found">
			<header class="article">
				<h1><?php _e( 'Oops, Post Not Found!', MOB_NS ); ?></h1>
			</header>
			<section>
				<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', MOB_NS ); ?></p>
			</section>
		</article>
	<?php endif;?></div><?php
}
/**
 * Article template
 * 
 * @param string $class
 * 
 * @return void
 */
function __mob_template_content_article($class='')
{
	?><article id="post-<?php the_ID(); ?>">
		<?php if((is_search()||mob_is_blog()) && has_post_thumbnail()):?>
			<a class="article-thumb" href="<?php the_permalink();?>" rel="bookmark"><?php the_post_thumbnail(array(150,150)); ?></a>
		<?php endif;?>
		<header>
			<h1><a href="<?php the_permalink();?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<p><?php printf( __( 'Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>. Filed under %4$s', MOB_NS ), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), get_the_author_link( get_the_author_meta('ID')), get_the_category_list(' ')); ?></p>
		</header> <?php // end article header ?>
		<section class="content">
			<?php
			if((is_search()||mob_is_blog())  && get_option('rss_use_excerpt'))
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
	</article><?php
}
/**
 * Output a sidebar
 * 
 * @param string $name
 * 
 * @return void
 */
function mob_template_sidebar($name)
{
	if(is_active_sidebar( $name ))
	{
		switch($name)
		{
			case 'header':case 'footer':
				echo '<'.$name.'>';
				echo '<div class="wrap">';
				dynamic_sidebar( $name );
				echo '</div>';
				echo '</'.$name.'>';
				break;
				
			default:
				?><div id="<?php echo $name;?>" class="sb">
				<?php dynamic_sidebar( $name ); ?>
				</div><?php
				break;
		}
	}
}
/**
 * Add site styles
 * 
 * @return void
 */
function mob_site_styles() 
{
	
	$basedir = str_replace('http://', '//', mob_get_assets_directory());
	$baseurl = str_replace('http://', '//', mob_get_assets_directory('baseurl'));
	
	if(file_exists($basedir.'css/styles.css'))
	{
		wp_enqueue_style('site-stylesheet', $baseurl.'css/styles.css', array(MOB_NS.'-stylesheet'));
	}
	if(file_exists($basedir.'js/scripts.js'))
	{
		wp_enqueue_script('site-scripts', $baseurl.'js/scripts.js', array('jquery'));
	}
}

// calling own login css to style it
function mob_login_css() 
{
	wp_enqueue_style('mob_login_css', get_template_directory_uri() . '/css/login.css', false );
	if($custom_css = get_option('mob_scss_url'))
	{
		wp_enqueue_style('mobStyles', $custom_css, false );
	}
}
// changing the logo link from wordpress.org to your site
function mob_login_url() 
{  
	return home_url(); 
}
// changing the alt text on the logo to show your site name
function mob_login_title() 
{ 
	return get_option( 'blogname' ); 
}
?>