<?php
function mob_shortcode_countdown($atts)
{
	$defaults = array(
		'id'=>'countdown_'.uniqid(),
        'day' => 0,
        'month' => 0,
		'year' => 0,
		'hour'=>0,
		'minutes'=>0,
		'seconds'=>0,
		'nextevent'=>false,
		'labels' => "['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Minutes', 'Seconds']", // The expanded texts for the counters 
   		'labels1'=>"['Year', 'Month', 'Week', 'Day', 'Hour', 'Minute', 'Second']", // The display texts for the counters if only one 
        'compactLabels'=>"['y', 'm', 'w', 'd']", // The compact texts for the counters 
    	'whichLabels'=>null, // Function to determine which labels to use 
    	'digits'=>"['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']", // The digits to display 
    	'timeSeparator'=>':', // Separator for time periods 
    	'isRTL'=>false, // True for right-to-left languages, false for left-to-right 
    	'since'=>null, // new Date(year, mth - 1, day, hr, min, sec) - date/time to count up from or numeric for seconds offset, or string for unit offset(s): 'Y' years, 'O' months, 'W' weeks, 'D' days, 'H' hours, 'M' minutes, 'S' seconds 
    	'timezone'=>null, // The timezone (hours or minutes from GMT) for the target times, or null for client local 
    	'serverSync'=>null, // A function to retrieve the current server time for synchronisation 
    	'format'=>'dHMS', // Format for display - upper case for always, lower case only if non-zero, 'Y' years, 'O' months, 'W' weeks, 'D' days, 'H' hours, 'M' minutes, 'S' seconds 
    	'layout'=>'', // Build your own layout for the countdown 
    	'compact'=>false, // True to display in a compact format, false for an expanded one 
    	'padZeroes'=>false, // True to add leading zeroes 
    	'significant'=>0, // The number of periods with values to show, zero for all 
    	'description'=>'', // The description displayed for the countdown 
    	'expiryUrl'=>null, // A URL to load upon expiry, replacing the current page 
    	'alwaysExpire'=>false, // True to trigger onExpiry even if never counted down 
    	'onExpiry'=>null, // Callback when the countdown expires - receives no parameters and 'this' is the containing division 
    	'onTick'=>null, // Callback when the countdown is updated - receives int[7] being the breakdown by period (based on format) and 'this' is the containing division 
    	'tickInterval'=>1
    );
    $a = shortcode_atts($defaults, $atts );
	
    if($a['nextevent'] && class_exists('EM_Events'))
    {
    	if($events = EM_Events::get(array('limit'=>1)))
    	{
    		$datetime = strtotime($events[0]->event_start_date.' '.$events[0]->event_start_time);
    		$a['day'] = date('j',$datetime);
    		$a['month'] = date('n',$datetime);
    		$a['year'] = date('Y',$datetime);
    		$a['hour'] = date('G',$datetime);
    		$a['minutes'] = intval(date('i',$datetime));
    		$a['seconds'] = intval(date('s',$datetime));
    	}
    }
    $return = '<div id="'.$a['id'].'"></div><script type="text/javascript">jQuery(document).ready(function(){
		jQuery(\'#'.$a['id'].'\').countdown({';
	    foreach(array_diff_key($a, array('id'=>'','day'=>'','month'=>'','year'=>'','hour'=>'','minutes'=>'','seconds'=>'')) as $k=>$v)
	    {
	    	if($v!=$defaults[$k] && $v!=null && $v!=false && !empty($v))
	    	{
	    		$return.= $k.':'.(substr($defaults[$k],0,1)=='[' || $defaults[$k]==null || is_numeric($defaults[$k]) || is_bool($defaults[$k])?$v:'"'.$v.'"').',';
	    	}
	    }
		$return.= '
			until: new Date('.intval($a['year']).','.intval($a['month']-1).','.intval($a['day']).','.intval($a['hour']).','.intval($a['minutes']).','.intval($a['seconds']).'),
		});
	});</script>';

	if(isset($events[0]->event_slug))
	{
		$return = '<a href="'.$events[0]->event_slug.'">'.$return.'</a>';
	}
	
	
	return $return;
}
add_shortcode('mob_countdown', 'mob_shortcode_countdown' );
/**
 * Add the countdown script
 */
function mob_countdown_enqueue_scripts_and_styles()
{
	// PREPENDED TO MAIN SCRIPT file
	//wp_register_script(MOB_NS.'-countdown', get_stylesheet_directory_uri().'/com/countdown/js/countdown.js',array('jquery'));
	//wp_enqueue_script(MOB_NS.'-countdown');
}
//add_action('wp_enqueue_scripts', 'mob_countdown_enqueue_scripts_and_styles', 0);
?>