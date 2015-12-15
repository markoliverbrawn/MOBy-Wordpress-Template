<?php 
/*
 Template Name: Full Width
*/
?>
<?php get_header(); ?>
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