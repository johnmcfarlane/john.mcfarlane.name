<?php 
	include "common.php";
	start_page("Homepage", "wallpaper1.png");
?>

<h1 style="text-align:center">
	John McFarlane's Homepage
</h1>

<p style="text-align:center;">
	<img src="john_and_heather.png" alt="Photograph of John and Heather at the Grand Canyon" style="text-align:center"/>
</p>

<script src="http://www.clocklink.com/embed.js"></script>
<script type="text/javascript" language="JavaScript">
	Clock = new Object;
	Clock.clockfile = "5012-black.swf";
	Clock.TimeZone = "WET";
	Clock.width = 151;
	Clock.height = 50;
	Clock.wmode = "transparent";
</script>

<div align="center"><table cellspacing="10" frame="hsides">
	<tr style="text-align:center;">
		<td>UK</td>
		<td>AZ</td>
	</tr>
	<tr>
		<td><script type="text/javascript" language="JavaScript">
			Clock.TimeZone = "WET";
			showClock(Clock);
		</script></td>
		<td><script type="text/javascript" language="JavaScript">
			Clock.TimeZone = "GMT-0700";
			showClock(Clock);
		</script></td>
	</tr>
</table></div>

<?php finish_page(); ?>
