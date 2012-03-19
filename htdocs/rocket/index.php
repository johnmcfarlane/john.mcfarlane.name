<?php 
	include "../php/page.php";
	begin_head("Rocket Demo", "wallpaper2.png", "../");
	end_head();
	begin_body();
?>

<h1>Rocket SVG Demo</h1>

<p>
	<acronym title="Scalable Vector Graphics">SVG</acronym> is an <a href="http://www.w3.org/Graphics/SVG/">open standard</a> for transmitting graphics over the web.
	What <acronym title="Hyper-Text Markup Language">HTML</acronym> does for words, SVG aims to achieve for pictures.
</p>

<p>
	Like HTML, SVG is dynamic. 
	That is to say, scripting languages can be used to alter the contents of SVG in powerful ways.
	This makes SVG an attractive alternative to restrictive technologies such as Flash for generating interactive content on the web.
</p>

<p>
	I have written a simple demo using SVG and JavaScript called Rocket which illustrates the potential of SVG.
	To see it, you'll need an SVG viewer or SVG-compatible browser.
	I recommend the <a href="http://www.apple.com/safari/download/">Safari</a> or <a href="http://www.opera.com/">Opera</a> browsers for viewing this demo. The demo works in Firefox 2 but is slow. 
</p>

<h2>Instructions</h2>
<p>
	Move your mouse over the black area below to interact with the demo.
	The rest is pretty self-explanatory.
</p>

<!--div style="text-align:center;">
	<object type="image/svg+xml" name="rocket" data="rocket.xml" width="640" height="640">Rocket Demo</object>
</div-->

<div style="text-align:center;">
	<object data="rocket.xml" type="image/svg+xml" name="Rocket Demo" width="640" height="640">
		<param name="src" value="rocket.xml"/>
		Rocket Demo
	</object>
</div>

<a href="rocket.xml" 

<!--object style="width:640;height:640;" src="rocket.xml">
</object-->

<?php
	end_body();
?>