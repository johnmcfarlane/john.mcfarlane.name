<?php 
	include("../common.php");
	start_page("Picture Gallery", "wallpaper3.png", "../"); 
?>
	<h1>Picture Gallery</h1>

<?php 
include("galleria.php"); 
catalogAlbums(".");
?>

<?php finish_page(); ?>