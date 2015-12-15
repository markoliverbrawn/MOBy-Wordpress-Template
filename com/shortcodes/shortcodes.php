<?php
function mob_shortcode_bloginfo( $atts ) 
{
	$atts = shortcode_atts(array(
		'key' => ''
	), $atts, 'mob_bloginfo' );
	return get_bloginfo($atts['key']);
}
add_shortcode('mob_bloginfo', 'mob_shortcode_bloginfo');