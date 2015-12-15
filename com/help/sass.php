<?php if(!defined('ABSPATH')){exit;};?>
<h3>SASS Reference</h3>
<div class="has-tabs sass-reference">
	<h3>Mixins</h3>
	<ul>
		<li>
			<h5>box-sizing($model: border-box)</h5>
			<p>Apply this where you want your elements to contain their padding and borders, this allows salsa to minimize browsers rounding errors and to use pixels or even em gutters and not be bound to %.</p>
		</li>
		<li>
			<h5>cell($container: false)</h5>
			<p>Apply this mixin to only set the layout element box-model (padding) but not positioning it, the element will simply flow in its natural position in the document. see the next mixin for positioned layout-elements. You can also use the deprecated call: grid-cell.</p>
		</li>
		<li>
			<h5>clear</h5>
			<p>Micro clearfix hack.</p>
		</li>
		<li>
			<h5>container</h5>
			<p>Apply container to your outer-most container element.</p>
		</li>
		<li>
			<h5>css-gradient($from: #dfdfdf, $to: #f8f8f8)</h5>
			<p>CSS3 GRADIENTS: Be careful with these since they can really slow down your CSS. Don't overdo it. <code>@include css-gradient(#dfdfdf,#f8f8f8);</code></p>
		</li>
		<li>
			<h5>horizontal-dropdown-menu();</h5>
			<p>Horizontal Dropdown Nav Menu</p>
		<li>
			<h5>magazine-layout()</h5>
			<p>Applies to the blog listings (as well as categories and archives). Feature thumbs are floated and on desktops and larger viewports, the first two posts are full width, whilst subsequent ones are arranged in a two column layout.</p>
		</li>
		<li>
			<h5>nested-container</h5>
			<p>Apply nested-container to any layout element that contains other layout elements as a nested layout container.</p>
		</li>
		<li>
			<h5>row</h5>
			<p>Apply row to any layout element that should force a new layout row.</p>
		</li>
		<li>
			<h5>span($width, $position: false)</h5>
			<p>span(<$width> [<$gutters>], [<$position>])</p>
			<p>span() is the heart of the layout system, it allows you to define the layout-element's width and position.</p>
			<p><strong>$width:</strong>
				<blockquote>
				    - Can be unitless and represent the number of columns to span.
				    - May have any kind of width unit (e.g. px, em, % etc.) and provide
				      complete control over the element's with, even if not complying with
				      the grid layout or if using a grid is not your cup of tea.
				    - May be followed by a space and none/left/right to remove gutters, 'none'
				      for no gutters (e.g. for nested containers), 'left/right' to leave a
				      gutter on one side only.
				</blockquote>
			</p>
			<p><strong>$position:</strong>
				<blockquote>
				    - Can be unitless and represent the column number the element starts on.
				    - May have any kind of width unit (e.g. px, em, % etc.) and provide
				      complete control over the element's position, similar to absolute
				      positioning only that elements are NOT removed from the normal flow.
				    - May be 'omega' for the last item in a row, will float the other way.
				    - May be 'row' to force a new layout row.
				</blockquote>
			</p>
			<p><strong>Examples:</strong>
				<blockquote>
				    <p>- Simple layout element spanning 4 columns starting from the 12th column <code>@include span(4, 12);</code></p>
					<p>- a 40% wide element pushed 60% off the layout's first column <code>@include span(40%, 60%);</code></p>
				</blockquote>
			</p>
			<p><strong>Nested grids -</strong>
				<blockquote>
				    <p>- You may provide $width as a simple fraction, so if you want an element to span 3 columns inside a 6 columns (nested) grid container, you'll probably use 3/6 as $width.</p>
				    <p>- $position too.</p>
				    <p>- note: 6/6 = 1 column, not 6 columns out of 6, use 100% instead.</p>
					<p>Example: 2 column element nested inside a 6 columns nested-container, starting from the 4th column. <code>@include span(2/6, 4/6);</code></p>
				</blockquote>
			</p>
			<p>Deprecated alias <em>grid</em> is also supported. I.e. <code>@include grid(2/6);</code></p>
		</li>
		<li>
			<h5>transition($transition...)</h5>
			<p>USAGE: <code>@include transition(all 0.2s ease-in-out);</code></p>
		</li>
		<li>
			<h5>unrow</h5>
			<p>Apply unrow to cancel the <em>row</em> mixin's effect, e.g. when changing layouts using media queries.</p>
		</li>
	</ul>
	<h3>Functions</h3>
	<ul>
		<li>
			<h5>lighten($color, $percent)</h5>
			<p>Lighten a color by a percentage.</p>
		</li>
		<li>
			<h5>darken($color, $percent)</h5>
			<p>Darken a color by a percentage.</p>
		</li>
	</ul>
</div>