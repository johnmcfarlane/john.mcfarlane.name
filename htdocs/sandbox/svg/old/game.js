function coordinates(event)
{
	var x=event.pageX+'px';
	var y=event.pageY+'px';
//	var i = document.getElementById("test");
//	i.style.left=x;
//	i.style.top=y;
	document.getElementById("txt").innerHTML="x="+x+",y="+y;
	document.getElementById("stxt").innerHTML="x="+x+",y="+y;
	
	document.getElementById("poly1").style.stroke = "red";
}
