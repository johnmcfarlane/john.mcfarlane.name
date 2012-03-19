<?php
	$image = ImageCreate(6, 6);
	header("Content-Type: image/jpeg"); 
	ImageJpeg($image); 
	ImageDestroy($image); 
?>
