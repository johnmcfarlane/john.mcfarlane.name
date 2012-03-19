<?php

function redirect($destination)
{
	$title = "Redirecting to " . $destination . "";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?php echo $title;?></title>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<meta name="author" content="John McFarlane">
<meta HTTP-EQUIV="REFRESH" content="0; url=<?php echo $destination;?>">
</head>
        <p>You are being redirected to <a href="<?php echo $destination;?>"><?php echo $destination;?></a><p>
</html><?php
}

?>
