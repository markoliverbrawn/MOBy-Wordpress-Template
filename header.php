<!doctype html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<?php // Force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php wp_title(''); ?></title>
		<?php // mobile meta ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<?php //from http://www.favicon-generator.org/;?>
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/img/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_template_directory_uri(); ?>/img/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/img/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/img/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php mob_template_sidebar('header');?>