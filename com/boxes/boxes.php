<?php
/**
 * Meta box setup function.
 *
 *  @return void
 */
function mob_boxes_meta_boxes_setup()
{
  // Add meta boxes on the 'add_meta_boxes' hook.
  add_action('add_meta_boxes', 'mob_boxes_meta_add');
  // Save post meta on the 'save_post' hook.
  add_action('save_post', 'mob_boxes_meta_save_url', 10, 2);
}
add_action('load-post.php', 'mob_boxes_meta_boxes_setup' );
add_action('load-post-new.php', 'mob_boxes_meta_boxes_setup' );
/**
 * Create one or more meta boxes to be displayed on the post editor screen.
 *
 * @return void
 */
function mob_boxes_meta_add() {

  add_meta_box(
    'mob_boxes_url',// Unique ID
    esc_html__( 'Link URL', MOB_NS),// Title
    'mob_boxes_meta_box_url',// Callback function
    'mob_boxes',// Admin page (or post type)
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
function mob_boxes_meta_box_url( $object, $box )
{
	wp_nonce_field(basename(__FILE__), 'mob_boxes_url_nonce' ); ?>
  	<p>
		<label for="mob_boxes_url"><?php _e( "Link URL", MOB_NS); ?></label>
		<br />
		<input class="widefat" type="text" name="mob_boxes_url" id="mob_boxes_url" value="<?php echo esc_attr( get_post_meta( $object->ID, 'mob_boxes_url', true ) ); ?>" size="30" />
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
function mob_boxes_meta_save_url($post_id, $post)
{
	// Verify the nonce before proceeding.
  	if(!isset($_POST['mob_boxes_url_nonce']) || !wp_verify_nonce($_POST['mob_boxes_url_nonce'], basename(__FILE__))) return $post_id;

	// Get the post type object.
	$post_type = get_post_type_object($post->post_type);

	// Check if the current user has permission to edit the post.
	if( !current_user_can( $post_type->cap->edit_post, $post_id ) ) return $post_id;

	// Get the posted data and sanitize it for use as an HTML class.
	$new_meta_value = (isset($_POST['mob_boxes_url'] ) ? sanitize_url($_POST['mob_boxes_url']) : '');

	// Get the meta key.
	$meta_key = 'mob_boxes_url';

	// Get the meta value of the custom field key.
	$meta_value = get_post_meta($post_id, $meta_key, true);

  	// If a new meta value was added and there was no previous value, add it.
	if($new_meta_value && '' == $meta_value) add_post_meta($post_id, $meta_key, $new_meta_value, true);

  	// If the new meta value does not match the old value, update it.
  	elseif($new_meta_value && $new_meta_value != $meta_value) update_post_meta( $post_id, $meta_key, $new_meta_value );

	// If there is no new meta value but an old value exists, delete it.
	elseif ( '' == $new_meta_value && $meta_value ) delete_post_meta( $post_id, $meta_key, $meta_value );
}
add_action('save_post', 'mob_boxes_meta_save_url', 10, 2 );
/**
 * function for the custom type
 *
 * @return void
 */
function mob_boxes() {
	// creating (registering) the custom type
	register_post_type( 'mob_boxes', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 'labels' => array(
			'name' => __( 'Boxes', 'bonestheme' ), /* This is the Title of the Group */
			'singular_name' => __( 'Box', 'bonestheme' ), /* This is the individual type */
			'all_items' => __( 'All Boxes', 'bonestheme' ), /* the all items menu item */
			'add_new' => __( 'Add New', 'bonestheme' ), /* The add new menu item */
			'add_new_item' => __( 'Add New Box', 'bonestheme' ), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __( 'Edit Box', 'bonestheme' ), /* Edit Display Title */
			'new_item' => __( 'New Box', 'bonestheme' ), /* New Display Title */
			'view_item' => __( 'View Box', 'bonestheme' ), /* View Display Title */
			'search_items' => __( 'Search Boxes', 'bonestheme' ), /* Search Custom Type Title */
			'not_found' =>  __( 'Nothing found in the Database.', 'bonestheme' ), /* This displays if there are no entries yet */
			'not_found_in_trash' => __( 'Nothing found in Trash', 'bonestheme' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'This is the example box', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'box', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'box', /* you can rename the slug here */
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
	register_taxonomy_for_object_type( 'category', 'boxes' );

	add_image_size( 'mob_box_image', 400, 300, true);

}

	// adding the function to the Wordpress init
	add_action( 'init', 'mob_boxes');

	/*
	for more information on taxonomies, go here:
	http://codex.wordpress.org/Function_Reference/register_taxonomy
	*/

	// now let's add custom categories (these act like categories)
	register_taxonomy( 'boxes_cat',
		array('mob_boxes'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
		array('hierarchical' => true,     /* if this is true, it acts like categories */
			'labels' => array(
				'name' => __( 'Box Categories', 'bonestheme' ), /* name of the custom taxonomy */
				'singular_name' => __( 'Box Category', 'bonestheme' ), /* single taxonomy name */
				'search_items' =>  __( 'Search Box Categories', 'bonestheme' ), /* search title for taxomony */
				'all_items' => __( 'All Box Categories', 'bonestheme' ), /* all title for taxonomies */
				'parent_item' => __( 'Parent Box Category', 'bonestheme' ), /* parent title for taxonomy */
				'parent_item_colon' => __( 'Parent Box Category:', 'bonestheme' ), /* parent taxonomy title */
				'edit_item' => __( 'Edit Box Category', 'bonestheme' ), /* edit custom taxonomy title */
				'update_item' => __( 'Update Box Category', 'bonestheme' ), /* update title for taxonomy */
				'add_new_item' => __( 'Add New Box Category', 'bonestheme' ), /* add new title for taxonomy */
				'new_item_name' => __( 'New Box Category Name', 'bonestheme' ) /* name title for taxonomy */
			),
			'show_admin_column' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'box' ),
		)
	);

	/*
		looking for custom meta boxes?
		check out this fantastic tool:
		https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
	*/

/*
// Extra by Mark
add_filter('manage_posts_columns', 'posts_columns', 5);
add_action('manage_posts_custom_column', 'posts_custom_columns', 5, 2);
function posts_columns($defaults){
    $defaults['mob_post_thumbs'] = __('Thumb');
    return $defaults;
}
function posts_custom_columns($column_name, $id){
        if($column_name === 'mob_post_thumbs'){
        echo the_post_thumbnail( array(50,50) );
    }
}
*/
function mob_bones_shortcode_boxes($atts)
{
	$a = shortcode_atts( array(
        'category' => '',
		'count' => 1,
		'columns' => 1,
        'order' => 'rand',
        'moretext'=>'More...'
    ), $atts );
	$return = '';

	//print_r($a);

	if($posts = get_posts( 'post_type=mob_boxes'.($a['count']?'&posts_per_page='.$a['count']:'').($a['category']?'&boxes_cat='.$a['category']:'').($a['order']?'&order='.$a['order']:'')))
	{
		$return = '<div class="boxes boxes-columns-'.$a['columns'].'"><ul>';
		foreach($posts as $post)
		{
			$link_start = $link_end = '';
			$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'mob_box_image');//);//'mob_box_image' );
			$url = $thumb['0'];
			$link = get_post_meta($post->ID, 'mob_boxes_url');
			if(isset($link[0]))
			{
				$link_start = '<a href="'.$link[0].'">';
				$link_end = '</a>';
			}
			$return.= '<li id="box-'.$post->ID.'" class="boxes-item">';
			if($url)
			{
				$return.= $link_start.'<div class="image" style="background:url('.$url.') no-repeat;background-size:cover;" title="'.esc_attr($post->post_title).' image"></div>'.$link_end;
			}
            $return.= '<i></i>';
			$return.= '<h2>'.$link_start.$post->post_title.$link_end.'</h2><div class="content">'.$post->post_content.'</div>'.($link_start ? ' '.$link_start.$a['moretext'].$link_end : '');
			$return.= '</li>';
		}
		$return.= '</ul></div>';
	}
	return $return;
}
add_shortcode( 'mob_boxes', 'mob_bones_shortcode_boxes' );