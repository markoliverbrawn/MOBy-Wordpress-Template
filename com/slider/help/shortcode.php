<?php if(!defined('ABSPATH')){exit;};?>
<h5>[mob_slider]</h5>
<h6>Parameters</h6>
<ul class="parameters">
	<li><strong>category:string</strong> The category to source the slides from</li>
	<li><strong>showon:string (optional)</strong> Page slugs to display the slider on. If omitted, the slider will appear on all pages</li>
	<li><strong>animation:string (optional)</strong> Select your animation type, "fade" or "slide". Default: fade</li>
	<li><strong>animation-speed:intger (optional)</strong> Integer: Set the speed of animations, in milliseconds. Default: 1000</li>
	<li><strong>slideshow-speed:integer (optional)</strong> Integer: Set the speed of the slideshow cycling, in milliseconds. Default: 7000</li>
</ul>
<h6>Example Useage</h6>
<blockquote><code>[mob_slider category="Home" showon="home"]</code></blockquote>