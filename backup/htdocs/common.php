<?php 

// start_page: html at the beginning of the page
function start_page($page_title, $page_wallpaper = FALSE, $root_dir = "./") 
{?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>John McFarlane: <?php echo $page_title;?></title>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>
	<meta name="author" content="John McFarlane"/>
	<meta name="description" content="John McFarlane's Homepage"/>
	<?php if ($page_wallpaper) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $root_dir . "style.css\"/>\n"; ?>
</head>
<body background="<?php echo $root_dir . $page_wallpaper;?>">

<div align="center" class="pg"><div class="header"><p>
	<a href="<?php echo $root_dir?>">home</a> -
	<a href="http://johnspermanentvacation.blogspot.com">blog</a> -
	<a href="<?php echo $root_dir?>gallery">pictures</a> -
	<a href="http://youtube.com/mcfirkin">videos</a> - 
	<a href="<?php echo $root_dir?>projects">projects</a> -
	<a href="<?php echo $root_dir?>cv">cv</a> -
	<a href="<?php echo $root_dir?>about">about</a> - 
	<!--funky email scrambler at: "http://www.mways.co.uk/prog/hidemail.php"-->
	<script language="JavaScript">
		document.write('<a hre'+'f="m'+'ai'+'lto:'+'%77%65%62%6d%61%73%74%65%72%40%6a%6f%68%6e%2e%6d%63%66%61%72%6c%61%6e%65%2e%6e%61%6d%65">&#101;&#109;&#97;&#105;&#108;<\/a>');
	</script>
</p></div>

<div class="body" align="left"><?php 
}

// finish_page: html at the end of the page
function finish_page() 
{?></div>
<div class="footer">
	<p>&copy;2007 John McFarlane</p>
</div></div>

</body>
</html>
<?php 
}?>
