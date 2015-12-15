<?php 
// Template Name: Right Sidebar Only
?>
<?php get_header(); ?>
<main>
	<div class="wrap">
		<?php 
			global $wrapperclass;
			$wrapperclass = 'sb'.(is_active_sidebar('sidebar2') ? '1' : '0');
			get_template_part('content','wrapper');
		?>
		<?php mob_template_sidebar('sidebar2');?>
	</div>
</main>
<?php get_footer(); ?>