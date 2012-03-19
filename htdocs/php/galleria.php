<?php

/*	galleria.php - gThumb album gallery cataloging script
	(c) 2007 John McFarlane
	
	Usage:
		Include this script and call catalogAlbums with the path to the
		directory where all your gThumb web album sub-directories are stored 
		on a web server with PHP 4 installed.
	
	Description:
		The function, catalogAlbums, generates a table of links to each
		album found in a sub-directory of the given directory. Each link is
		accompanied by a thumbnail which is randomly selected from the thumbs
		associated with that photo album. 
*/


//////////////////////////////////////////////////
// primary functions


// called from the client web page
function catalogAlbums($root_dir_name, $albums_per_row = 3)
{
	$albums = scanAlbums($root_dir_name);
	rsort($albums);
	outputAlbums($albums, $albums_per_row);
}


// searches the given directory for sub-directories that contain 
// photo albums generated with gThumb and returns an array of 
// Album objects representing each photo album 
function scanAlbums($root_dir_name)
{
	if (($dir_handle = opendir($root_dir_name)) == FALSE)
	{
		echo "Error: Failed to open directory, \"" . $dir_name . "\".<br>";
		return FALSE;
	}

	$albums = Array();
	while ($file = readdir($dir_handle))
	{
		$gallery = new Album($root_dir_name, $file);

		if ($gallery->isValid())
		{
			$albums[] = $gallery;
		}
	}

	return $albums;
}


// produces an html table of the given array of Gallery
// objects with each photo album represented as a table cell
function outputAlbums($albums, $albums_per_row = 3)
{
	$num_rows = (integer)((count($albums) + $albums_per_row) / $albums_per_row);

	echo "\t<table";
	echo " class=\"album\"";
	echo " summary=\"A table of links to photo albums.\">\n";

	for ($row_index = 0; $row_index < $num_rows; ++ $row_index)
	{
		outputRow($albums, $row_index, $albums_per_row, "thumbnail", "outputThumbnailButLinkToIndex");
		outputRow($albums, $row_index, $albums_per_row, "title", "outputTitle");
	}
	
	echo "\t</table>\n";
}


// called by function, outputGallery, to produce a single row of the table;
// the function is called for either a row of thumbnail images or a row of album
// titles alternately.
function outputRow($albums, $row_index, $albums_per_row, $class, $output_fn)
{
	$class_str = $class ?
		" class=\"" . $class . "\""
		: "";

	echo "\t\t<tr" . $class_str . ">\n";

	for ($column_index = 0; $column_index < $albums_per_row; ++ $column_index)
	{
		$gallery_index = $row_index * $albums_per_row + $column_index;
		echo "\t\t\t<td" . $class_str . " colspan=\"" . $albums_per_row . "\">";
		
		$gallery = $albums[$gallery_index];
		if ($gallery && $output_fn)
		{
			$gallery->$output_fn();
		}
		
		echo "</td>\n";
	}
	echo "\t\t</tr>\n";
}


//////////////////////////////////////////////////
// class definitions


// class Album:
//	given a relative path in the constructor, determines whether path is
//	a directory containing a gThumb-generated web photo album and if so,
//	gathers and stores all the information pertaining to the album

class Album
{
	//////////////////////////////////////////////////
	// attributes
	
	var $sort_time;	// the time-stamp value of the last change to the index page
	var $path;
	var $title;
	var $image_filename;
	
	
	//////////////////////////////////////////////////
	// interface methods

	// ctor
	function Album($root_dir_name, $sub_dir_name)
	{
		if (! $this->initialise($root_dir_name, $sub_dir_name))
			$this->invalidate();
	}

	// true if the given $path points to a directory containing a recognised photo album
	function isValid()
	{
		return $this->image_filename;
	}

	// output the gallery details in one go
	function outputAll()
	{
		assert($this->isValid());
		
		$this->outputThumbnailButLinkToIndex();
		echo "<br />";
		$this->outputTitle();
	}


	//////////////////////////////////////////////////
	// output methods
	
	// output the randomly chosen image's thumbnail 
	// as a link to the its page in the album
	function outputThumbnail()
	{
		echo	
			"<a href=\"", $this->getPagePath($this->image_filename), "\">", 
				"<img src=\"", $this->getThumbnailPath($this->image_filename), "\" ", 
				"alt=\"A sample thumbnail from a photo album entitled '", $this->title, "'\" />", 
			"</a>";
	}
	
	// output the randomly chosen image's thumbnail 
	// as a link to the album
	function outputThumbnailButLinkToIndex()
	{
		echo	
			"<a href=\"", $this->getPageName(), "\">", 
				"<img src=\"", $this->getThumbnailPath($this->image_filename), "\" ", 
				"alt=\"A sample thumbnail from a photo album entitled '", $this->title, "'\" />", 
			"</a>";
	}

	// output the album's title as a link to the its index page
	function outputTitle()
	{
		echo
			"<a href=\"", $this->getPageName(), "\">", 
				$this->title, 
			"</a>";
	}

	// print human-readable version of album's date
	function outputDate()
	{
		echo date("r", $this->sort_time);
	}


	//////////////////////////////////////////////////
	// initialisation methods

	// set up the this object's member attributes given the path to an album and return true
	// or return false if there is some reason to doubt that the path indeed points to an album
	function initialise($root_dir_name, $sub_dir_name)
	{
		// put path together
		$this->path = $root_dir_name . "/" . $sub_dir_name . "/";
		
		// check path is a directory
		if (is_dir($this->path) == FALSE)
			return FALSE;
		
		// check directory has an index.html
		$index_filename = $this->getPageName();
		if (is_file($index_filename) == FALSE)
			return FALSE;
		
		// open directory
		$dir_handle = opendir($this->path);
		ASSERT($dir_handle != FALSE);

		// for all files in the album directory
		$latest_exif_date = 0;
		$image_count = 0;
		while ($filename = readdir($dir_handle))
		{
			if ($this->isAlbumImage($filename) == FALSE)
				// if $filename isn't an image, skip to next file
				continue;

			// randomly consider image as chosen thumbnail
			if (mt_rand(0, $image_count) == 0)
				$this->image_filename = $filename;
			++ $image_count;

			// if it has a date tag, consider it as the overall date value of the gallery
			$exif_date = $this->extractImageDate($filename);
			if ($exif_date > $latest_exif_date)
				$latest_exif_date = $exif_date;
		}
		
		if ($image_count == 0)
			return FALSE;

		$this->mod_time = $this->guessAlbumDate($root_dir_name, $sub_dir_name);
		if (! $this->mod_time) 
		{
			if ($latest_exif_date != 0)
				$this->mod_time = $latest_exif_date;
			else
				// get index.html's mod date
				$this->mod_time = filemtime($index_filename);
		}
		assert($this->mod_time != FALSE);

		$this->title = getHtmlPageTitle($this->getPageName());
		return TRUE;
	}

	// ensure the object is in a state where $this->isValid() will return FALSE
	function invalidate()
	{
		$this->image_filenames = NULL;
	}

	// looks to see if the album directory name follows the format:
	// YYYYMMDD where the date is some time during of after 1970
	function guessAlbumDate($root_dir_name, $sub_dir_name)
	{
		$sub_start = substr($sub_dir_name, 0, 8);
		$sub_length = strlen($sub_start);
		if ($sub_length != 8 || ! ctype_digit($sub_start))
			return FALSE;

		$year = (integer)(substr($sub_start, 0, 4));
		$month = (integer)(substr($sub_start, 4, 2));
		$day = (integer)(substr($sub_start, 6, 2));
		return mktime(0, 0, 0, $month, $day, $year);
	}

	// get the given image's date value as stored by a digital camera;
	// doesn't always work but is very useful when trying to date an album
	function extractImageDate($filename)
	{
		return extractExifDate($this->getImagePath($filename));
	}

	//////////////////////////////////////////////////
	// helper methods

	// returns TRUE iff given file is accompanied by 
	// suitably named thumbnail and page files
	function isAlbumImage($image_filename)
	{
		global $image_thumbnail_format, $image_page_format;
		return
			is_file($this->getThumbnailPath($image_filename)) && 
			is_file($this->getPagePath($image_filename));
	}

	// returns path of album's index page
	function getPageName()
	{
		return $this->path . "index.html";
	}

	// returns path of album image given the image's filename alone
	function getImagePath($image_filename)
	{
		return $this->path . $image_filename;
	}

	// returns path of album image's thumbnail given the image's filename alone
	function getThumbnailPath($image_filename)
	{
		return $this->path . $image_filename . ".small.jpeg";
	}

	// returns path of album image's web page given the image's filename alone
	function getPagePath($image_filename)
	{
		return $this->path . $image_filename . ".html";
	}

}	// class Album


//////////////////////////////////////////////////
// utility functions


// returns the title of a given web page
function getHtmlPageTitle($filename)
{
	return (class_exists(SimpleXMLElement))
		? getHtmlPageTitle5($filename)	// PHP5 version uses SimpleXML
		: getHtmlPageTitle4($filename);	// PHP4 scans individual lines looking for the <title> tags by hand
}


// returns the title of a given web page
function getHtmlPageTitle5($filename)
{
	$str = file_get_contents($filename);
	$str = str_replace("&nbsp", " ", $str);
	//$xml = simplexml_load_file($filename, SimpleXMLElement, LIBXML_NOWARNING);
	$xml = new SimpleXMLElement($str);
	return $xml->head->title;
}


// returns the title of a given web page
function getHtmlPageTitle4($filename)
{
	$page = file($filename);

	//Read each line
	foreach ($page as $line) 
	{
		$title_start = strstr($line, "<title>");
		if ($title_start != FALSE) 
		{
			$title_end_pos = strpos($title_start, "</title>");
			if ($title_end_pos) 
			{
				$title_start_pos += 7;
				return substr($title_start, $title_start_pos, $title_end_pos - $title_start_pos);
			}
		}
	}

	return FALSE;
}


// returns the unix time stamp stores in the given image 
// iff a camera wrote that data; returns false otherwise
function extractExifDate($image_path)
{
	if (exif_imagetype($image_path) == IMAGETYPE_JPEG)
	{
		$exif_filedata = exif_read_data($image_path, 'FILE');
		if ($exif_filedata != FALSE)
		{
			$date_time_string = $exif_filedata[FileDateTime];
			if ($date_time_string != FALSE)
			{
				return (integer)$date_time_string;
			}
		}
	}

	return FALSE;
}


// returns true iff given path is a directory that isn't 
// current (".") or parent ("..") directory; path must end
// with a forward slash ("/")
function isSubDir($filename)
{
	$length = strlen($filename);
	assert(endsIn($filename, $length, "/"));

	return
			endsIn($filename, $length, "./") == FALSE
		&&	endsIn($filename, $length, "../") == FALSE
		&&	is_dir($filename);
}


// returns true iff string, $string, of length, $length,
// ends in string, $sub_string
function endsIn($string, $length, $sub_string)
{
	return substr($string, $length - strlen($sub_string)) == $sub_string;
}

?>
