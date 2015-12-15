<?php 
/*
 Template Name: Full Width Landing Page
*/
?>
<?php get_header(); ?>
<main class="landing-page">
	<div class="wrap">
		<?php 
			global $wrapperclass;
			$wrapperclass = 'sb0';
			get_template_part('content','wrapper');
		?>
	</div>
</main>
<?php get_footer(); ?>