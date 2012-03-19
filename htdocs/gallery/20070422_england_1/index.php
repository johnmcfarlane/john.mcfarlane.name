<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<title>Index of Picture Galleries</title>
</head>	

<body>
	<h1>Index of Picture Galleries</h1>

<?php

$thumbnail_name_format = "img%03d.jpeg.small.jpeg";

// main loop
if ( $dh = opendir( "." ) ) {
	while ( ( $file = readdir( $dh ) ) !== false ) {
		if (! is_dir( $file )) {
			continue;
		}

		processDirectory($file . "/");
	}
}
// done


function processDirectory($dir_name) {
	// check that there are appropriately named thumbnails
	global $thumbnail_name_format;
	$first_thumbnail_name = sprintf($thumbnail_name_format, 0);
	$image_name = $dir_name . $first_thumbnail_name;
	if (! is_file( $image_name ) ) {
		return;
	}

	// and of course there needs to be an index file
	$index_name = $dir_name . "index.html";
	if (! is_file( $index_name ) ) {
		return;
	}

	$image_name = chooseRandomThumbnail($dir_name);

	$title = getPageTitle($index_name);

	echo	
		"<p>", 
		"<a href=\"", $index_name, "\">", 
		"<img src=\"", $image_name, "\" alt=\"Sample Thumbnail of Gallery entitled '", $title, "'\"/>", 
		"<br/>", $title, 
		"</a></p>\n";
}


function chooseRandomThumbnail($dir_name) {
	global $thumbnail_name_format;
	for ($thumbnail_count = 0; true; ++ $thumbnail_count) {
		$thumbnail_name = $dir_name . sprintf($thumbnail_name_format, $thumbnail_count);
		if (is_file($thumbnail_name)) {
			if (mt_rand(0, $thumbnail_count) < 1) {
				$chosen_thumbnail_name = $thumbnail_name;
			}
		}
		else {
			break;
		}
	} while (false);
	return $chosen_thumbnail_name;
}


function getPageTitle($index_name) {
	//Open XML file
	$index=file($index_name);

	//Read data
	foreach ($index as $line) {
		$title_start = strstr($line, "<title>");
		if ($title_start != false) {
			$title_end_pos = strpos($title_start, "</title>");
			if ($title_end_pos) {
				$title_start_pos += 7;
				return substr($title_start, $title_start_pos, $title_end_pos - $title_start_pos);
			}
		}
	}
	
	return "error";
}


?>

</body>
</html>
