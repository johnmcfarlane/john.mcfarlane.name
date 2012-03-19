<?php 


/////////////////////////////////
// globals

$url_root = "";


function url($filename)
{
	global $url_root;
	echo $url_root . $filename;
}


/////////////////////////////////
// high-level page construction

function begin_head($title, $wallpaper = FALSE, $root_dir = "") 
{
	global $url_root;
	$url_root = $root_dir;
	
	// [0, HTML]
/*	// neccessary for 1.1 to be the correct mime-type
	$charset = "UTF-8";
	$mime = (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) ? "application/xhtml+xml" : "text/html";
	header("content-type:" . $mime . ";" . $charset);*/

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n"; 
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"; 
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\n";

	// [HEAD ...]
	echo "<head>\n";
	echo "\t<title>" . $title . "</title>\n";
	echo "\t<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n";
	echo "\t<meta name=\"author\" content=\"John McFarlane\" />\n";
	echo "\t<meta name=\"description\" content=\"This is the personal website of John McFarlane. The purpose of the site is to keep in touch with friends and family back in the UK and to promote himself to prospective employers in the USA.\" />\n"; 
	echo "\t<link rel=\"stylesheet\" type=\"text/css\" href=\""; url("screen.css"); echo "\" />\n";
	//echo "\t<link rel=\"stylesheet\" type=\"text/css\" href=\""; url("iehack.css"); echo "\" />\n";
	echo "\t<style type=\"text/css\">\n";
	echo "\t\thtml { background:url("; if ($wallpaper) { url("wallpaper/"); echo $wallpaper; } echo ") #e8e8e8 }\n";
	echo "\t</style>\n";
}

function end_head()
{
	echo "</head>\n";
}

function begin_body($header = TRUE)
{
?>
<body>
<div id="outer"><div id="inner">

<?php
	if ($header)
	{
?>
<div id="header">
	<a href="<?php url(".");?>">home</a> -
	<a href="<?php url("gallery");?>">pictures</a> -
	<a href="<?php url("projects");?>">projects</a> -
	<a href="<?php url("cv");?>">cv</a> -
	
	<!--"http://hivelogic.com/enkoder/form"-->
	<script type="text/javascript" src="<?php url("email.js"); ?>"></script>
</div>

<?php
	}
?>
<div id="content">
<?php 
}

function end_body($footer = TRUE)
{
?></div>

<?php
	if ($footer)
	{
?>
<div id="footer">
	<p id="copyright">&copy;2011 John McFarlane</p>
	<p id="validation">
		<a href="http://validator.w3.org/check/referer">XHTML</a> - 
		<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>
	</p>
</div>

<?php
	}
?>
</div></div>
<?php include("tracker.php");?>
</body>
</html>
<?php 
} 

?>
