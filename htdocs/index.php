<?php 
	include "php/page.php";
	begin_head("John McFarlane's Homepage", "wallpaper1.png");
?>
	<style type="text/css">
		div.buttons { text-align:center; margin-top:10px; margin-bottom:10px }
		img.button { vertical-align:middle; border:none; height:25px }

		div.time_zone { text-align:center;font-family:sans-serif }
	</style>
<?php
	end_head();
	begin_body();
?>

<script type="text/javascript" src="http://www.worldtimeserver.com/clocks/embed.js"></script>
<script type="text/javascript" src="clock.js"></script>


<h1>John McFarlane's Homepage</h1>


<div class="buttons">
	<?php function button($name, $image, $link)
	{
		echo "<a href=\"" . $link . "\" title=\"" . $name . "\">";
			echo "<img class=\"button\" src=\"" . $image . "\" alt=\"" . $name . "\" />";
		echo "</a>";
	}?>

	<?php button("Twitter", "https://g.twimg.com/Twitter_logo_blue.png", "https://twitter.com/JSAMcFarlane"); ?> &nbsp;
	<?php button("GitHub", "https://assets.github.com/images/icons/emoji/octocat.png", "https://github.com/johnmcfarlane"); ?> &nbsp;
	<?php button("LinkedIn profile", "http://www.linkedin.com/img/webpromo/btn_linkedin_120x30.gif", "http://www.linkedin.com/in/johnmcfarlane"); ?> &nbsp;
	<?php button("Google+", "//ssl.gstatic.com/images/icons/gplus-32.png", "//plus.google.com/110202519902799314719?prsrc=3"); ?> &nbsp;
	<?php button("Facebook profile", "http://badge.facebook.com/badge/726024281.162.2106066251.png", "https://www.facebook.com/John.S.A.McFarlane"); ?> &nbsp;
	<?php button("Videos", "http://www.youtube.com/yt/brand/media/image/YouTube-icon-full_color.png", "http://youtube.com/mcfirkin"); ?> &nbsp;
	<?php button("Blog", "http://buttons.blogger.com/blogger-simple-white.gif", "http://johnspermanentvacation.blogspot.com"); ?>

	<!-- In case you care, I tried to use the generated script, but when placed inside a div on Firefox, it comes out wrong -->
	<!-- It also fails the xhtml validator. -->
</div>


<div style="text-align:center">
	<a href="https://picasaweb.google.com/lh/photo/9oE7DHa4vt3MljOTIAHR9g?feat=directlink">
		<img style="border-style:solid;border-width:1px;border-color:#000000;" src="boys_n_bubble.png" alt="Photograph of John McFarlane, his son, Owain and his bubble" />
	</a>
	<!--a href="gallery/20070221_grand_canyon/index.html">
		<img style="border-style:solid;border-width:1px;border-color:#000000;" src="john_and_heather.png" alt="Photograph of John and Heather at the Grand Canyon" />
	</a-->
</div>

<div style="text-align:center">
	<table cellspacing="10" style="margin-left:auto;margin-right:auto;text-align:center;font-family:sans-serif;"><tr>
		<td>
			<div class="time_zone">NSW</div>
			<div><script type="text/javascript">au_clock()</script></div>
		</td>
		<td>
			<div class="time_zone">UK</div>
			<div><script type="text/javascript">uk_clock()</script></div>
		</td>
		<td>
			<div class="time_zone">France</div>
			<div><script type="text/javascript">fr_clock()</script></div>
		</td>
		<td>
			<div class="time_zone">Arizona</div>
			<div><script type="text/javascript">az_clock()</script></div>
		</td>
		<td>
			<div class="time_zone">Washington</div>
			<div><script type="text/javascript">wa_clock()</script></div>
		</td>
	</tr></table>
</div>


<?php end_body(); ?>
