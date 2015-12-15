<?php 
// Template Name: Left Sidebar Only
?>
<?php get_header(); ?>
<!-- <?php echo basename(__FILE__);?> -->
<main>
	<div class="wrap">
		<?php mob_template_sidebar('sidebar1');?>
		<?php 
			global $wrapperclass;
			$wrapperclass = 'sb'.(is_active_sidebar('sidebar1') ? '1' : '0');
			get_template_part('content','wrapper');
		?>
	</div>
</main>
<?php get_footer(); ?>