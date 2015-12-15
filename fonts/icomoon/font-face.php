<?php
$host = 'http'.(@$_SERVER['HTTPS']?'s':'').'://'.$_SERVER['HTTP_HOST'];
header('Content-type:text/css');
?>
@font-face {
	font-family: 'icomoon';
	src:url('<?php echo $host;?>/wp-content/themes/moby/fonts/icomoon/icomoon.eot?-wl5h8k');
	src:url('<?php echo $host;?>/wp-content/themes/moby/fonts/icomoon/icomoon.eot?#iefix-wl5h8k') format('embedded-opentype'),
		url('<?php echo $host;?>/wp-content/themes/moby/fonts/icomoon/icomoon.woff?-wl5h8k') format('woff'),
		url('<?php echo $host;?>/wp-content/themes/moby/fonts/icomoon/icomoon.ttf?-wl5h8k') format('truetype'),
		url('<?php echo $host;?>/wp-content/themes/moby/fonts/icomoon/icomoon.svg?-wl5h8k#icomoon') format('svg');
	font-weight: normal;
	font-style: normal;
}