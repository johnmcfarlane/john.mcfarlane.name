<html>
<head>
	<script type="text/javascript">
		var num_bubbles = 10
		var bubbles = new Array()
		var int=self.setInterval("doSomething()",500);
		if (!IE) document.captureEvents(Event.MOUSEMOVE)
		function coordinates(event) {
			var x=event.pageX-20+'px';
			var y=event.pageY-20+'px';
			var i = document.getElementById("test");
			i.style.left=x;
			i.style.top=y;
			document.getElementById("txt").innerHTML="x="+x+",y="+y;
			//document.getElementById("stxt").innerHTML="x="+x+",y="+y;
			
		//	document.getElementById("poly1").style.stroke = "red";
		}
		function doSomething() {
			var r = parseInt(255*Math.random());
			var g = parseInt(255*Math.random());
			var b = parseInt(255*Math.random());
			var str = "rgb("+r+","+g+","+b+")";
			var e = document.getElementById("test2");
			e.style.color=str;
			e.innerHTML=str;
		}
	</script>
</head>

<body onmousemove="coordinates(event)">

<h1 style="text-align:center">
	Sandbox
</h1>

<hr />

<p id="txt">Coords here</p>

<img id="test" src="../john_and_heather.png" style="position:fixed;left:10px;top:10px;width:40px;height:40px" />
<div>
TeSt
</div>




<!--input type="button" onclick="doSomething()" value="do it!" /-->




<!--p>Link: <a href="http://picasaweb.google.com/mcfarlane.john/AWalkInThePark">http://picasaweb.google.com/mcfarlane.john/AWalkInThePark</a></p>

<p>Thumbnail:</p>
<table style="width: 194px;"><tbody><tr><td style="background: transparent url(http://picasaweb.google.com/f/img/transparent_album_background.gif) no-repeat scroll left center; height: 194px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" align="center"><a href="http://picasaweb.google.com/mcfarlane.john/AWalkInThePark"><img src="http://lh4.google.com/mcfarlane.john/Rq_MWrHvNfE/AAAAAAAAAHk/N6e1Oa2ES_8/s160-c/AWalkInThePark.jpg" style="margin: 1px 0pt 0pt 4px;" height="160" width="160" /></a></td></tr><tr><td style="text-align: center; font-family: arial,sans-serif; font-size: 11px;"><a href="http://picasaweb.google.com/mcfarlane.john/AWalkInThePark" style="color: rgb(77, 77, 77); font-weight: bold; text-decoration: none;">A Walk in the Park</a></td></tr></tbody></table>

<p>Slideshow:</p>
<embed type="application/x-shockwave-flash" src="http://picasaweb.google.com/s/c/bin/slideshow.swf" flashvars="host=picasaweb.google.com&RGB=0x000000&amp;feed=http%3A%2F%2Fpicasaweb.google.com%2Fdata%2Ffeed%2Fapi%2Fuser%2Fmcfarlane.john%2Falbumid%2F5093514393483687409%3Fkind%3Dphoto%26alt%3Drss" pluginspage="http://www.macromedia.com/go/getflashplayer" height="192" width="288"></embed>-->

<!--div style="text-align:center; width: 165px;"><a href="http://flashandburn.net/youtubeBadge/mcfirkin/goto"><img border="0" src="http://flashandburn.net/youtubeBadge/mcfirkin/logo:1-color:000000/recent.png"></a><br /><a href="http://www.flashandburn.net/youtubeBadge/mcfirkin/refer" style="color: #666; font-size: 10px;">Get your own youTube badge</a></div-->

<!--
//1:09:00 Start recording
//1:09:?? Start
//1:10:00 Stop  Duration 0:??
//1:11:28 Start Period   1:58
//1:12:07 Stop  Duration 0:39
//1:13:15 Start Period   1:47
//1:14:00 Stop  Duration 0:45
//1:17:05 Start Period   3:50
//1:17:57 Stop  Duration 0:52
//1:18:51 Start Period   1:46
//1:19:38 Stop  Duration 0:47
//1:22:41 Start Period   3:50
//1:23:35 Stop  Duration 0:54
//1:24:08 Start Period   1:27
//1:24:50 Stop  Duration 0:42
//1:26:26 Start Period   2:18
//1:27:10 Stop  Duration 0:44
//1:28:28 Start Period   2:02
//1:29:15 Stop  Duration 0:47
-->
</body>
</html>