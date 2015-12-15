<?php
/**
 * cb_searchform.php
 *
 * PHP Version 5.2
 *
 * @category   Template
 * @package    OLWPT
 * @subpackage Core
 * @author     Leigh Bicknell <leigh@orangeleaf.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       http://orangeleaf.com
 */
if(OLOPT_DEBUG)
{
    trigger_error('Loading '.basename(__FILE__), E_USER_NOTICE);
}

global $__searchform_count;
global $__CB_advancedsearch_form_count;
$__searchform_count++;
$next_advs_count = $__CB_advancedsearch_form_count + 1;

$slug = cb_get_option('slug');
$search_slug = '/'.$slug.'/'.cb_get_option('components', 'search', 'slug');
$advanced = cb_get_option('components', 'advancedsearch', 'slug');
$advanced = '/'.$slug.'/'.$advanced;
//$maps = cb_get_option('components','mapsearch');
$maps = '/'.cb_get_option('components', 'mapsearch', 'slug');
$search_prompt = htmlspecialchars(CB_SEARCH_PROMPT);
if (isset($_REQUEST['qa'])) {
    cb_advancedsearch_prepare_query();
}
$value = $_REQUEST['q']!='' ? $_REQUEST['q'] : get_search_query();
?>
<form class="cb-search" role="search" method="get" id="searchform-<?php echo $__searchform_counter; ?>" action="<?php echo $search_slug; ?>">
    <input type="search" value="<?php echo $value; ?>" name="s" id="s" placeholder="<?php echo esc_attr(__('Search', 'ol-wp-theme'));?>">
    <button type="submit" class="submit"><i class="fa fa-search"></i></button>
    <button class="more" onclick="jQuery(this).siblings('.advancedsearchform').slideToggle();return false;" title="Search options"><i class="fa fa-cog"></i></button>
    <div class="advancedsearchform" style="display:none;">
    	<?php
    	cb_advancedsearch_form();
    	?>
	</div>
</form>
