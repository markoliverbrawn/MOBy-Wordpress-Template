<?php
/**
 * Meta box setup function.
 * 
 *  @return void
 */
function mob_slider_meta_boxes_setup() 
{
  // Add meta boxes on the 'add_meta_boxes' hook.
  add_action('add_meta_boxes', 'mob_slider_meta_add');
  // Save post meta on the 'save_post' hook.
  add_action('save_post', 'mob_slider_meta_save_url', 10, 2);
}
add_action('load-post.php', 'mob_slider_meta_boxes_setup' );
add_action('load-post-new.php', 'mob_slider_meta_boxes_setup' );
/**
 * Create one or more meta boxes to be displayed on the post editor screen.
 * 
 * @return void
 */
function mob_slider_meta_add() 
{
  add_meta_box(
    'mob_slider_url',// Unique ID
    esc_html__( 'Link URL', MOB_NS),// Title
    'mob_slider_meta_box_url',// Callback function
    'mob_slider',// Admin page (or post type)
    'normal',// Context
    'default'// Priority
  );
}
/**
 * Display the post meta box. 
 * 
 * @param object $object
 * @param object $box
 * 
 * @return void
 */
function mob_slider_meta_box_url( $object, $box ) 
{ 
	wp_nonce_field(basename(__FILE__), 'mob_slider_url_nonce' ); ?>
  	<p>
		<label for="mob_slider_url"><?php _e( "Link URL", MOB_NS); ?></label>
		<br />
		<input class="widefat" type="text" name="mob_slider_url" id="mob_slider_url" value="<?php echo esc_attr( get_post_meta( $object->ID, 'mob_slider_url', true ) ); ?>" size="30" />
	</p>
<?php 
}
/**
 * Save the meta box's post metadata. 
 * 
 * @param integer $post_id
 * @param array   $post
 * 
 * @return integer
 */
function mob_slider_meta_save_url($post_id, $post) 
{
	// Verify the nonce before proceeding.
  	if(!isset($_POST['mob_slider_url_nonce']) || !wp_verify_nonce($_POST['mob_slider_url_nonce'], basename(__FILE__))) return $post_id;

	// Get the post type object.
	$post_type = get_post_type_object($post->post_type);

	// Check if the current user has permission to edit the post.
	if( !current_user_can( $post_type->cap->edit_post, $post_id ) ) return $post_id;

	// Get the posted data and sanitize it for use as an HTML class.
	$new_meta_value = (isset($_POST['mob_slider_url'] ) ? sanitize_url($_POST['mob_slider_url']) : '');

	// Get the meta key.
	$meta_key = 'mob_slider_url';

	// Get the meta value of the custom field key.
	$meta_value = get_post_meta($post_id, $meta_key, true);

  	// If a new meta value was added and there was no previous value, add it.
	if($new_meta_value && '' == $meta_value) add_post_meta($post_id, $meta_key, $new_meta_value, true);

  	// If the new meta value does not match the old value, update it.
  	elseif($new_meta_value && $new_meta_value != $meta_value) update_post_meta( $post_id, $meta_key, $new_meta_value );

	// If there is no new meta value but an old value exists, delete it.
	elseif ( '' == $new_meta_value && $meta_value ) delete_post_meta( $post_id, $meta_key, $meta_value );
}
add_action('save_post', 'mob_slider_meta_save_url', 10, 2 );

/**
 * Flush your rewrite rules
 */
function _mob_flush_rewrite_rules() 
{
	flush_rewrite_rules();
}
//add_action('after_switch_theme', 'mob_flush_rewrite_rules' );
/**
 * Create the function for the custom type
 * 
 * @return void
 */
function mob_slider() 
{ 
	// creating (registering) the custom type 
	register_post_type( 'mob_slider', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 'labels' => array(
			'name' => __( 'Slider', 'bonestheme' ), /* This is the Title of the Group */
			'singular_name' => __( 'Slide', 'bonestheme' ), /* This is the individual type */
			'all_items' => __( 'All Slides', 'bonestheme' ), /* the all items menu item */
			'add_new' => __( 'Add New', 'bonestheme' ), /* The add new menu item */
			'add_new_item' => __( 'Add New Slide', 'bonestheme' ), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __( 'Edit Slide', 'bonestheme' ), /* Edit Display Title */
			'new_item' => __( 'New Slide', 'bonestheme' ), /* New Display Title */
			'view_item' => __( 'View Slide', 'bonestheme' ), /* View Display Title */
			'search_items' => __( 'Search Slides', 'bonestheme' ), /* Search Custom Type Title */ 
			'not_found' =>  __( 'Nothing found in the Database.', 'bonestheme' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __( 'Nothing found in Trash', 'bonestheme' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'This is the example slide', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'slider', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'slider', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 
				'title', 
				'editor', 
				//'author', 
				'thumbnail', 
				//'excerpt', 
				//'trackbacks', 'custom-fields', 'comments', 
				'revisions', 
				'sticky'
			)
		) /* end of options */
	); /* end of register post type */
	
	/* this adds your post categories to your custom post type */
	register_taxonomy_for_object_type( 'category', 'slider' );
	/* this adds your post tags to your custom post type */
	//register_taxonomy_for_object_type( 'post_tag', 'slider' );
	
	add_image_size( 'mob_slider_image', 1600, 500, true);
	
}
add_action('init', 'mob_slider');
/**
 * Add custom categories (these act like categories)
 */
register_taxonomy( 'slider_cat', 
	array('mob_slider'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true, it acts like categories */
		'labels' => array(
			'name' => __( 'Slider Categories', 'bonestheme' ), /* name of the custom taxonomy */
			'singular_name' => __( 'Slider Category', 'bonestheme' ), /* single taxonomy name */
			'search_items' =>  __( 'Search Slider Categories', 'bonestheme' ), /* search title for taxomony */
			'all_items' => __( 'All Slider Categories', 'bonestheme' ), /* all title for taxonomies */
			'parent_item' => __( 'Parent Slider Category', 'bonestheme' ), /* parent title for taxonomy */
			'parent_item_colon' => __( 'Parent Slider Category:', 'bonestheme' ), /* parent taxonomy title */
			'edit_item' => __( 'Edit Slider Category', 'bonestheme' ), /* edit custom taxonomy title */
			'update_item' => __( 'Update Slider Category', 'bonestheme' ), /* update title for taxonomy */
			'add_new_item' => __( 'Add New Slider Category', 'bonestheme' ), /* add new title for taxonomy */
			'new_item_name' => __( 'New Slider Category Name', 'bonestheme' ) /* name title for taxonomy */
		),
		'show_admin_column' => true, 
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'slider' ),
	)
);
function mob_shortcode_slider($atts)
{
	global $post;
	$a = shortcode_atts( array(
        'category' => '',
        'height'=>400,
        'order' => 'rand',
		'showon' => '',
		'animation'=>'fade',
		'animation-speed'=>1000,
		'slideshow-speed'=>7000,
        'directionnav'=>false,
        'controlnav'=>true,
        'thumbsize'=>'mob_slider_image',
		'bg_h2'=>null,
		'bg_content'=>null,
		'count'=>10
    ), $atts );
	$return = '';
	$a['showon'] = explode(',', $a['showon']);
	
	if(
		!empty($a['showon']) && 
		(
			(
				is_home() && !in_array('home',$a['showon'])
			) 
			|| 
			(
				!is_home() && !in_array($post->ID, $a['showon'])
			)
		)
	)
	{
	
		return '';
	}
	
	if($posts = get_posts( 'posts_per_page='.$a['count'].'&post_type=mob_slider'.($a['category']?'&slider_cat='.$a['category']:'').($a['order']?'&order='.$a['order']:'')))
	{
		$return = '<div class="flexslider" style="height:'.$a['height'].'px;" data-animation="'.$a['animation'].'" data-control-nav="'.($a['controlnav']===true?'true':'false').'" data-direction-nav="'.($a['directionnav']?'true':'false').'" data-slideshow-speed="'.$a['slideshow-speed'].'" data-animation-speed="'.$a['animation-speed'].'"><ul class="slides">';
		foreach($posts as $post)
		{
			$link = get_post_meta($post->ID, 'mob_slider_url', true);
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'mob_slider_image' );
			$url = $thumb['0'];
			$return.= '<li id="slide-'.$post->ID.'" style="background-image:url(\''.$url.'\');'.($link?'cursor:pointer;':'').'height:'.$a['height'].'px;"'.($link?' onclick="window.location.href=\''.$link.'\'"':'').'>';
			$return.= '<h2'.($a['bg_h2']!==null?' style="background:'.moby_get_spot_color($url, $a['bg_h2']).'"':'').'>'.$post->post_title.'</h2>';
			if($post->post_content)
			{
				$return.= '<div class="content"'.($a['bg_content']!==null?' style="background:'.moby_get_spot_color($url, $a['bg_content']).'"':'').'>'.$post->post_content.'</div>';
			}
			$return.= '</li>';
		}
		$return.= '</ul></div>';
	}
	return $return;
}
add_shortcode('mob_slider', 'mob_shortcode_slider' );
?>