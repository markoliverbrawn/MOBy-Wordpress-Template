<?php get_header(); ?>
<!-- <?php echo basename(__FILE__);?> -->
<main>
	<div class="wrap">
		<?php if(is_404()):?>
			<?php mob_404();?>
		<?php else:?>
			<?php mob_template_sidebar('sidebar1');?>
			<?php get_template_part('content','wrapper')?>
			<?php mob_template_sidebar('sidebar2');?>
		<?php endif;?>
	</div>
</main>
<?php get_footer(); ?>