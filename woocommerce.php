<?php get_header(); ?>
<main>
	<div class="wrap">
		<?php mob_template_sidebar('sidebar1');?>
		<div class="<?php echo 'sb'.(is_active_sidebar('sidebar1')+is_active_sidebar('sidebar2'));?>">
		<?php woocommerce_content(); ?>
		</div>
		<?php mob_template_sidebar('sidebar2');?>
	</div>
</main>
<?php get_footer(); ?>