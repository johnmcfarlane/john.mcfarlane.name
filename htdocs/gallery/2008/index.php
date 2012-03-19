<?php 
	include("../../php/page.php");
	begin_head("Picture Gallery", "wallpaper5.png", "../../"); 
?>
	<link rel="stylesheet" type="text/css" href="../../php/galleria.css" />
<?php
	end_head();
	begin_body();
?>

<h1>2008 Picture Gallery</h1>

<p>To get a fresh selection of pictures, click the Reload / Refresh button.</p>

<?php 
include("../../php/galleria.php"); 
catalogAlbums(".");
?>

<p style="text-align:center"><a href="..">More Pictures</a></p>

<?php end_body(); ?>