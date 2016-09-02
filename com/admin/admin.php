<?php if(!defined('ABSPATH')){exit;};



require_once get_template_directory().'/com/admin/scssphp/scss.inc.php';
require_once get_template_directory().'/com/admin/jsmin/jsmin.php';

$__icons = array('icomoon','font-awesome');
$__layouts = array('page-fullwidth'=>'Full Width','page-leftsidebar'=>'Pre Content Sidebar','page-rightsidebar'=>'Post Content Sidebar');

add_action('admin_enqueue_scripts', 'mob_admin_css', 10);
add_action('wp_dashboard_setup', 'mob_admin_disable_default_dashboard_widgets');
add_action('wp_dashboard_setup', 'mob_admin_custom_dashboard_widgets');
add_action('manage_posts_custom_column', 'mob_admin_posts_custom_columns', 5, 2);
add_action('admin_menu', 'mob_admin_plugin_menu');
add_action('admin_init', 'mob_admin_register_settings');
add_action('admin_notices', 'mob_admin_notices');

add_filter('admin_footer_text', 'mob_admin_custom_admin_footer');
add_filter('manage_posts_columns', 'mob_admin_posts_columns', 5);

/**
 * Flush your rewrite rules
 */
function mob_flush_rewrite_rules() 
{
	flush_rewrite_rules();
}
add_action('after_switch_theme', 'mob_flush_rewrite_rules' );
/**
 * Add admin styles
 * 
 * @return void
 */
function mob_admin_css() 
{
	if(@$_REQUEST['page']=='mob_options')
	{
		wp_enqueue_style( 'mob_admin_css', get_template_directory_uri() . '/css/admin.css', false );
		wp_enqueue_style( 'jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css');
		wp_enqueue_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js', array('jquery'), '1.8.16');
	}
}
/**
 * Compile SCSS
 * 
 * @return string
 */
function mob_admin_compile_scss($scss_folder, $css_folder, $format_style = "scss_formatter")
{
	// scssc will be loaded automatically via Composer
    $scss_compiler = new scssc();
	// set the path where your _mixins are
	$scss_compiler->setImportPaths($scss_folder);
	// set css formatting (normal, nested or minimized), @see http://leafo.net/scssphp/docs/#output_formatting
	$scss_compiler->setFormatter($format_style);
	// get all .scss files from scss folder
	$filelist = glob($scss_folder . "*.scss");

	try{
		// step through all .scss files in that folder
		foreach ($filelist as $file_path) {
			// get path elements from that file
			$file_path_elements = pathinfo($file_path);
			// get file's name without extension
			$file_name = $file_path_elements['filename'];
			// get .scss's content, put it into $string_sass
			$string_sass = mob_file_read($scss_folder . $file_name . ".scss");
			// compile this SASS code to CSS
			$string_css = $scss_compiler->compile($string_sass);
			// write CSS into file with the same filename, but .css extension
			mob_file_write($css_folder . $file_name . ".css", $string_css);
		}
	}catch(Exception $e){
		return $e->getMessage();
	} 
}
/**
 * Custom Backend Footer
 * 
 * @return void
 */
function mob_admin_custom_admin_footer() 
{
	echo '<span id="footer-thankyou">Developed by <a href="http://markoliverbrawn.com" target="_blank">Mark Oliver Brawn</a> of <a href="http://thewhitewhale.co.uk" target="_blank">The White Whale</a></span>';;
}
/**
 * Calling all custom dashboard widgets
 * 
 * @return void
 */
function mob_admin_custom_dashboard_widgets() 
{
	//wp_add_dashboard_widget( 'mob_rss_dashboard_widget', __( 'Recently on Themble (Customize on admin.php)', 'bonestheme' ), 'bones_rss_dashboard_widget' );
	/*
	Be sure to drop any other created Dashboard Widgets
	in this function and they will all load.
	*/
}
/**
 * Disable default dashboard widgets
 * 
 * @return void
 */
function mob_admin_disable_default_dashboard_widgets() 
{
	global $wp_meta_boxes;
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);    // Right Now Widget
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget

	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);    // Quick Press Widget
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     // Recent Drafts Widget
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           //
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         //

	// remove plugin dashboard boxes
	//unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);           // Yoast's SEO Plugin Widget
	//unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);        // Gravity Forms Plugin Widget
	//unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);   // bbPress Plugin Widget

	/*
	have more plugin widgets you'd like to remove?
	share them with us so we can get a list of
	the most commonly used. :D
	https://github.com/eddiemachado/bones/issues
	*/
}
/**
 * Draw options
 */
function mob_admin_draw_options()
{
	?><div class="wrap"><h2></h2>
	<form method="post" action="options.php" id="post"><?php

		if( isset($_GET['settings-updated']) )
		{
			if($_GET['settings-updated']==true)
			{
				echo '<div id="message" class="updated"><p><strong>';
				_e('Settings saved. ');
				echo $_GET['settings-updated-message'];
				echo '</strong></p></div>';
			}
			else
			{
				echo '<div id="message" class="error"><p><strong>';
				echo $_GET['settings-updated'];
				echo $_GET['settings-updated-message'];
				echo '</strong></p></div>';
			}
		}
		
		echo '<div id="tabs">';
		echo '<ul>';
		echo '<li id="tabnav_general"><a href="#mob_opts_general">General Settings</a></li>';
		echo '<li id="tabnav_scss"><a href="#mob_opts_scss">CSS</a></li>';
		echo '<li id="tabnav_js"><a href="#mob_opts_js">Javascript</a></li>';
		echo '<li id="tabnav_help"><a href="#mob_opts_help">Help</a></li>';
		echo '</ul>';
		echo '<div id="mob_opts_general">';
		settings_fields('mob_options');
		do_settings_sections('mob_options');
		echo '</div>';
		echo '<div id="mob_opts_scss" class="has-tabs"></div>';
		echo '<div id="mob_opts_js"></div>';
		echo '<div id="mob_opts_help" class="has-tabs">';
		include get_template_directory().'/com/help/index.php';
		echo '</div>';
		echo '</div>';
		
		submit_button();?>
	</form>
	</div>
	<script type="text/javascript">
	function MOB_setCookie(name, value, days){
		var expires;
		if(days){
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toGMTString();
		}else{
			expires = "";
		}
		document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
	}
	function MOB_getCookie(name){var nameEQ = escape(name) + "=";var ca = document.cookie.split(';');for (var i = 0; i < ca.length; i++){var c = ca[i];while (c.charAt(0) === ' ') c = c.substring(1, c.length);if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));}return null;}
	jQuery(function($){
		// Rejig the sections
		$('#mob_opts_js').append($('#mob_opts_general>h2:contains("<?php _e('Javascript', MOB_NS);?>")').next());
		$('#tabs h2:contains("<?php _e('Javascript', MOB_NS);?>")').hide();
		<?php foreach(array('Base','Tablet','Desktop','Large Monitors') as $label):?>
			var $h3 = $('#tabs h2:contains("<?php echo $label;?>")');
			var $next = $('#tabs h2:contains("<?php echo $label;?>")').next();
			$('#mob_opts_scss').append($h3);
			$('#mob_opts_scss').append($next);
		<?php endforeach;?>

		$('.has-tabs').each(function(ii,vv){
			$vv = $(vv);
			$ul = $('<ul></ul>');
			$vv.append($ul);
			$vv.find('>h2').each(function(i,v){
				var $v = $(v);
				var id = 't-'+ii+'-'+i+'-'+Math.round(Math.random()*1000);
				var $div = $('<div id="'+id+'"></div>');
				$vv.append($div);
				$ul.append('<li class="'+id+'"><a href="#'+id+'">'+$v.text()+'</a></li>');
				$v.next().appendTo($div);
				$v.prependTo($div);
				$v.hide();
			});
		});

		$('#tabs, .has-tabs').uniqueId().each(function(i,v){
			var $v = $(v);
			$v.tabs({
				active: MOB_getCookie('active_tab_'+$v.attr('id')),
				activate: function (event, ui) {
					MOB_setCookie('active_tab_'+$v.attr('id'), ui.newTab.index(), 365);
				}
			});
		});

		
		$('.mob_scss_input').css({height:$(window).height()*0.55}).keydown(function(e) {
			var $this, end, start;
			if (e.keyCode === 9) {
				start = this.selectionStart;
    			end = this.selectionEnd;
    			$this = $(this);
    			$this.val($this.val().substring(0, start) + "\t" + $this.val().substring(end));
    			this.selectionStart = this.selectionEnd = start + 1;
    			return false;
  			}
		});
		
		$(window).resize(function(e){
            var $iframe = $('#helpframe');
            try{
                $iframe.css({minHeight:$(window).outerHeight()-$iframe.offset().top - 250});
            }catch(e){}
        }).trigger('resize');
	});</script><?php
}
/**
 * Needed for including iconsets
 * 
 * @param string $from
 * @param string $to
 * 
 * @return string
 */
function mob_admin_get_relative_path($from, $to)
{
	$from = explode('/', $from);
	$to = explode('/', $to);
	foreach($from as $depth => $dir)
	{

		if(isset($to[$depth]))
		{
			if($dir === $to[$depth])
			{
				unset($to[$depth]);
				unset($from[$depth]);
			}
			else
			{
				break;
			}
		}
	}
	//$rawresult = implode('/', $to);
	for($i=0;$i<count($from)-1;$i++)
	{
	array_unshift($to,'..');
	}
			$result = implode('/', $to);
			return $result;
}
/**
 * Draw default layout input
 * 
 * @return void
 */
function mob_admin_input_general_404_page()
{
	$wp_query = new WP_Query();
	
	$option = get_option('mob_general_404_page');
	$pages = get_pages(array(
		'sort_order' => 'ASC',
		'sort_column' => 'post_title',
		'hierarchical' => 1,
		'child_of' => 0,
		'parent' => -1,
		'offset' => 0,
		'post_type' => 'page',
		'post_status' => 'publish'
	)); 
	
	echo '<select id="mob_general_404_page_input" name="mob_general_404_page"><option value="">-- Please select --</option>';
	foreach($pages as $page)
	{
		echo '<option value="'.$page->ID.'"'.($option==$page->ID?' selected="selected"':'').'>'.$page->post_title.'</option>';
	}
	echo '</select>';
}
/**
 * Draw scss input
 * 
 * @return void
 */
function mob_admin_input_general_meta_template()
{
	$option = get_option('mob_general_meta_template', MOB_DEFAULT_META_TEMPLATE);
	echo "<input id=\"mob_general_meta_template\" type=\"text\" name=\"mob_general_meta_template\" size=\"40\" class=\"large-text\" value=\"".esc_attr($option)."\"/>";
	echo '<span class="description">E.g. <code>'.MOB_DEFAULT_META_TEMPLATE.'</code></span>';
}
/**
 * Draw scss input
 * 
 * @return void
 */
function mob_admin_input_scss($name, $help='')
{
	$options = get_option($name);
	if(is_array($options))
	{
		$options = $options['text_string'];// Backwards compat with early version
	}
	//$scss = file_get_contents(mob_get_assets_directory().'css/src/styles.scss');
	echo "<textarea id='{$name}' name='{$name}' size='40' class=\"large-text mob_scss_input\" style=\"height:200px;\">{$options}</textarea>";
	if($help)
	{
		echo '<span class="description">'.$help.'</span>';
	}
}
/**
 * Draw scss input
 * 
 * @return void
 */
function mob_admin_input_scss_base()
{
	mob_admin_input_scss('mob_scss_base', 'The CSS is mobile first, so create all your core styling here');
}
/**
 * Draw scss input
 * 
 * @return void
 */
function mob_admin_input_scss_mobile()
{
	mob_admin_input_scss('mob_scss_mobile');
}
/**
 * Draw scss input
 * 
 * @return void
 */
function mob_admin_input_scss_tablet()
{
	mob_admin_input_scss('mob_scss_tablet');
}
/**
 * Draw scss input
 * 
 * @return void
 */
function mob_admin_input_scss_desktop()
{
	mob_admin_input_scss('mob_scss_desktop');
}
/**
 * Draw scss input
 * 
 * @return void
 */
function mob_admin_input_scss_large_desktop()
{
	mob_admin_input_scss('mob_scss_large_desktop');
}
/**
 * Draw iconset input
 * 
 * @return void
 */
function mob_admin_input_scss_iconset()
{
	global $__icons;
	$option = get_option('mob_scss_iconset');
	echo '<select id="mob_scss_iconset_input" name="mob_scss_iconset"><option value="">None</option>';
	foreach($__icons as $set)
	{
		echo '<option value="'.$set.'"'.($option==$set?' selected="selected"':'').'>'.$set.'</option>';
	}
	echo '</select>';
}
/**
 * Draw js input
 * 
 * @return void
 */
function mob_admin_input_js()
{
	mob_admin_input_scss('mob_js');
}
/**
 * Adds MOBy admin notices
 * 
 * @return void
 */
function mob_admin_notices() 
{
    return;
	?>
    <div class="update-nag">
        <p><?php _e('Don\'t forget to copy the CSS before you update (text_string update)', MOB_NS); ?></p>
    </div>
    <?php
}
/**
 * Add to menu
 * 
 * @return void
 */
function mob_admin_plugin_menu() 
{
	$hook = add_theme_page( 'Theme Options', 'Theme Options', 'manage_options', 'mob_options', 'mob_admin_draw_options');
	add_action('load-'.$hook,'mob_admin_settings_save');
}
/**
 * Add thumbnails to column
 * 
 * @param array $defaults
 * 
 * @return string
 */
function mob_admin_posts_columns($defaults)
{
    $defaults['mob_post_thumbs'] = 'Thumb';
    return $defaults;
}
/**
 * Add post thumbs to columns cont.
 * 
 * @param string  $column_name
 * @param integer $id
 * 
 * @return void
 */
function mob_admin_posts_custom_columns($column_name, $id)
{
    if($column_name === 'mob_post_thumbs')
    {
    	echo the_post_thumbnail(array(50,50));
    }
}
/**
 * Register settings
 * 
 * @return void
 */
function mob_admin_register_settings() 
{
	$page_id = 'mob_options';
	$general_section_id = 'mob_options_general';
	$base_section_id = 'mob_options_scss_base';
	$tablet_section_id = 'mob_options_scss_tablet';
	$desktop_section_id = 'mob_options_scss_desktop';
	$large_desktop_section_id = 'mob_options_scss_large_desktop';
	$javascript_section_id = 'mob_options_javascript';
	
	// General SECTION
	add_settings_section($general_section_id, 'Layout', '', $page_id);
	// General
	register_setting($page_id, 'mob_general_404_page', 'mob_admin_sanitize_number');
	add_settings_field('mob_general_404_page', '404 Page', 'mob_admin_input_general_404_page', $page_id, $general_section_id);
	// Templates
	register_setting($page_id, 'mob_general_meta_template', 'mob_admin_sanitize_string');
	add_settings_field('mob_general_meta_template', 'Meta Template', 'mob_admin_input_general_meta_template', $page_id, $general_section_id);	
	
	// Base styling
	add_settings_section($base_section_id, 'Base', '', $page_id);
	// Iconset
	register_setting($page_id, 'mob_scss_iconset', 'mob_admin_sanitize_scss_iconset');// Styles Settings - Icons
	add_settings_field('mob_bones_scss_iconset', 'SCSS Iconset', 'mob_admin_input_scss_iconset', $page_id, $base_section_id);
	// Base SCSS - mobile first
	register_setting($page_id, 'mob_scss_base');// Base styles - Mobile first
	add_settings_field('mob_bones_scss_base', 'SCSS Base Source', 'mob_admin_input_scss_base', $page_id, $base_section_id);
	
	// Tablet styling
	add_settings_section($tablet_section_id, 'Tablet', '', $page_id);
	register_setting($page_id, 'mob_scss_tablet');// Tablet settings 
	add_settings_field('mob_bones_scss_tablet', 'SCSS Tablet Source', 'mob_admin_input_scss_tablet', $page_id, $tablet_section_id);
	
	// Desktop styling
	add_settings_section($desktop_section_id, 'Desktop', '', $page_id);
	register_setting($page_id, 'mob_scss_desktop');// Standard desktop
	add_settings_field('mob_bones_scss_desktop', 'SCSS Desktop Source', 'mob_admin_input_scss_desktop', $page_id, $desktop_section_id);
	
	// Large monitor styling
	add_settings_section($large_desktop_section_id, 'Large Monitors', '', $page_id);
	register_setting($page_id, 'mob_scss_large_desktop');// Large monitors
	add_settings_field('mob_bones_scss_large', 'SCSS Large Viewport Source', 'mob_admin_input_scss_large_desktop', $page_id, $large_desktop_section_id);
	
	add_settings_section($javascript_section_id, 'Javascript', '', $page_id);
	register_setting($page_id, 'mob_js');// Javascript
	add_settings_field('mob_bones_js', 'Javascript', 'mob_admin_input_js', $page_id, $javascript_section_id);
} 
/**
 * RSS Dashboard Widget
 * 
 * @return void
 */
function mob_admin_rss_dashboard_widget() 
{
	if(function_exists('fetch_feed')) 
	{
		// include_once( ABSPATH . WPINC . '/feed.php' );               // include the required file
		$feed = fetch_feed( 'http://feeds.feedburner.com/wpcandy' );        // specify the source feed
		if (is_wp_error($feed)) 
		{
			$limit = 0;
			$items = 0;
		} 
		else 
		{
			$limit = $feed->get_item_quantity(7);                        // specify number of items
			$items = $feed->get_items(0, $limit);                        // create an array of items
		}
	}
	if($limit==0)
	{
		echo '<div>The RSS Feed is either empty or unavailable.</div>';   // fallback message
	}
	else foreach ($items as $item) { ?>
	<h4 style="margin-bottom: 0;">
		<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo mysql2date('j F Y @ g:i a', $item->get_date( 'Y-m-d H:i:s' ) ); ?>" target="_blank">
			<?php echo $item->get_title(); ?>
		</a>
	</h4>
	<p style="margin-top: 0.5em;">
		<?php echo substr($item->get_description(), 0, 200); ?>
	</p>
	<?php }
}
/**
 * Check that a valid iconset has been selected
 */
function mob_admin_sanitize_number($value)
{	
	if(!is_numeric($value))
	{
		$value = '';
	}
	return $value;
}
/**
 * Check that a valid iconset has been selected
 */
function mob_admin_sanitize_string($value)
{	
	//if(!is_string($value))
	//{
	//	$value = '';
	//}
	return $value;
}
/**
 * Check that a valid iconset has been selected
 */
function mob_admin_sanitize_scss_iconset($value)
{
	global $__icons;
	
	mob_log('submitted iconset:'.$value);
	
	if(!in_array($value, $__icons))
	{
		$value = '';
	}
	return $value;
}
/**
 * Called when settings are saved - compiles the scss
 */
function mob_admin_settings_save()
{	
	$url = wp_nonce_url('themes.php?page=mob_options','mob_options');
	if (false === ($creds = request_filesystem_credentials($url) ) ) {
	    // if we get here, then we don't have credentials yet,
	    // but have just produced a form for the user to fill in,
	    // so stop processing for now
	    return true; // stop the normal page form from displaying
	}
	// now we have some credentials, try to get the wp_filesystem running
	if ( ! WP_Filesystem($creds) ) {
	    // our credentials were no good, ask the user for them again
	    request_filesystem_credentials($url);
	    return true;
	}
	
	
	if(isset($_GET['settings-updated']) && $_GET['settings-updated'])
	{		
		$messages = array();
		$assets_directory = mob_get_assets_directory();
		$template_directory = get_template_directory();
		$scss_path = $assets_directory.'css/src/styles.scss';
		$js_path = $assets_directory.'js/src/scripts.js';
		$iconset = get_option('mob_scss_iconset');
		$scss = '';
		
		// Include salsa
		$path = mob_admin_get_relative_path($assets_directory.'css/src/', $template_directory.'/css/src/salsa');
		$scss.= '@import "'.$path.'/salsa";'.PHP_EOL;
		
		// Include bones mixins
		$path = mob_admin_get_relative_path($assets_directory.'css/src/', $template_directory.'/css/src/partials');
		$scss.= '@import "'.$path.'/functions";'.PHP_EOL;
		$scss.= '@import "'.$path.'/mixins";'.PHP_EOL;
		
		// Include an iconset
		if($iconset)
		{
			switch($iconset)
			{
				case 'font-awesome':
				$path = mob_admin_get_relative_path($assets_directory.'css/src/', $template_directory.'/fonts/'.$iconset.'/scss');
				$scss.= '@import "'.$path.'/'.$iconset.'";'.PHP_EOL;
				break;
				
				default:
				$path = mob_admin_get_relative_path($assets_directory.'css/src/', $template_directory.'/fonts/'.$iconset);
				$scss.= '@import "'.$path.'/'.$iconset.'";'.PHP_EOL;
				break;
			}
		}
		
		// Collate the media queries
		foreach(array(
			'base'=>'',
			'mobile'=>481,
			'tablet'=>768,
			'desktop'=>1030,
			'large_desktop'=>1240) as $name=>$breakpoint)
		{
			$option = get_option('mob_scss_'.$name);
			if($option)
			{
				if($breakpoint)
				{
					$scss.= '@media only screen and (min-width: '.$breakpoint.'px) {';
					$scss.= $option;
					$scss.= '}';
				}
				else 
				{
					$scss.= $option;
				}
			}
		}
		if(mob_file_read($scss_path)!=$scss)
		{
			if(mob_file_write($scss_path, $scss)!==false)
			{
				if($error = mob_admin_compile_scss($assets_directory.'css/src/', $assets_directory.'css/', 'scss_formatter_compressed'))
				{
					$messages[] = '<span class="icon icon-warning"></span> scss not compiled. '.$error;	
				}
				else
				{
					$messages[] = '<span class="icon icon-checkmark"></span> scss compiled';
				}
			}
			else
			{
				$messages[] = '<span class="icon icon-warning"></span> could not save css';
			}
		}
		
		// Cache the css path as a setting so that we can style the login screen
		update_option('mob_scss_url', mob_get_assets_directory('baseurl').'css/styles.css');		
		
		// Now update the Javascript
		$js =get_option('mob_js');
		$previous_version_js = '';
		if(file_exists($js_path))
		{
			$previous_version_js = mob_file_read($js_path);
		}
		if($previous_version_js!=$js)
		{
			try 
			{
				$minified = JSMin::minify($js);
				$messages[] = (false===mob_file_write($js_path, $js) || false===mob_file_write($assets_directory.'js/scripts.js', $minified) ? '<span class="icon icon-cross"></span> js could not be saved' : '<span class="icon icon-checkmark"></span> js saved');
			} 
			catch (Exception $e) 
			{
				$messages[] = '<span class="icon icon-warning"></span> js not compiled. '.$e->getMessage();
			}
			
		}
		
		// Cache the css/js path as a setting so that we can style the login screen
		update_option('mob_scss_url', mob_get_assets_directory('baseurl').'css/styles.css');
		update_option('mob_js_url', mob_get_assets_directory('baseurl').'js/scripts.js');
		
		$_GET['settings-updated-message'] = implode(', ', $messages);
	}
}
?>