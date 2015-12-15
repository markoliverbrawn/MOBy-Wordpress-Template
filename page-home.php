<?php 
/*
 Template Name: Home
*/
?>
<?php get_header(); ?>
<?php echo do_shortcode('[mob_slider animation="slide" category="home" controlnav="false" directionnav="true" slideshow-speed="7000"]');?>
<?php 
$i = 0;
// Remove images with no image
$events = wams_get_random_events_with_images(array());
if(count($events)>=9)
{
	echo '<div class="col col-1">
		<div class="cell cell-1" style="background-image:url('.$events[$i]->get_image_url().')">'.wams_draw_event_for_homepage_slider($events[$i++]).'</div>
		<div class="cell cell-2" style="background-image:url('.$events[$i]->get_image_url().')">'.wams_draw_event_for_homepage_slider($events[$i++]).'</div>
		<div class="cell cell-3" style="background-image:url('.$events[$i]->get_image_url().')">'.wams_draw_event_for_homepage_slider($events[$i++]).'</div>
	</div>
	<div class="col col-2">
		<div class="cell cell-1" style="background-image:url('.$events[$i]->get_image_url().')">'.wams_draw_event_for_homepage_slider($events[$i++]).'</div>
		<div class="cell cell-2" style="background-image:url('.$events[$i]->get_image_url().')">'.wams_draw_event_for_homepage_slider($events[$i++]).'</div>
		<div class="cell cell-3" style="background-image:url('.$events[$i]->get_image_url().')">'.wams_draw_event_for_homepage_slider($events[$i++]).'</div>
		<div class="cell cell-4" style="background-image:url('.$events[$i]->get_image_url().')">'.wams_draw_event_for_homepage_slider($events[$i++]).'</div>
		<div class="cell cell-5" style="background-image:url('.$events[$i]->get_image_url().')">'.wams_draw_event_for_homepage_slider($events[$i++]).'</div>
	</div>
	<div class="col col-3">
		<div class="cell" style="background-image:url('.$events[$i]->get_image_url().')">'.wams_draw_event_for_homepage_slider($events[$i++]).'</div>
	</div>';
}
else
{
	echo '<div style="color:red;font-size:2em;margin:1em;text-align:center;">The homepage grid requires at least 9 active events. Only '.count($events).' have been found :(</div>';
}
?>
<main>
	<div class="wrap">
		<?php 
			global $wrapperclass;
			$wrapperclass = 'sb0';
			get_template_part('content','wrapper');
		?>
	</div>
</main>
<?php get_footer(); ?>