/* global variables */
var svgNS='http://www.w3.org/2000/svg';
var gRes=null
var gCenter=null
var gScoreNode=null
//var gWin=null
var gShip=null
var gStars=null
var gTimer=self.setInterval(tick,50)

// initialisation
function initGame() {
	document.documentElement.addEventListener('mousemove', onmousemove, false)
	
	var simspace=document.getElementById("simspace")
	gRes=new coords(parseInt(simspace.getAttribute("width")),parseInt(simspace.getAttribute("height")))
	gCenter=new coords(gRes.x*.5,gRes.y*.5)

	gScoreNode = document.getElementById("score_text").childNodes[0]
	score("Score:10")

	var fe = window.frameElement
	var od = fe?fe.ownerDocument:document
	if (od) {
		var dv = od.defaultView
		if (dv) {
			gWin = dv
		}
	}
	
	initShip()
	initStars()
}

///////////////////
function initShip() {
	gShip = new Ship()
	gShip.update()
	var deadzone=document.getElementById('deadzone')
	if (deadzone) {
		deadzone.setAttribute('r',Ship.prototype.deadRadius)
		deadzone.setAttribute('cx',gCenter.x)
		deadzone.setAttribute('cy',gCenter.y)
	}
}
function Ship() {
	this.element=document.getElementById("ship")
	this.element.setAttribute("points",buildShipPoints(15,.5,.25))
	this.accel=0
	this.angle=0
	this.pos=new coords(0,0)
	this.vel=new coords(0,0)
	this.acc=new coords(0,0)
	this.impulse=new coords(0,0)
}

Ship.prototype.deadRadius=30	//circle around ship where mouse has no effect
Ship.prototype.deadRadiusSq=(Ship.prototype.deadRadius*Ship.prototype.deadRadius)	//circle around ship where mouse has no effect
Ship.prototype.accelCo=0.01
Ship.prototype.dragCo=.95
Ship.prototype.max_da=.25	// max delta angle - ship turn per tick
Ship.prototype.gravity=.25


Ship.prototype.tick=function() {
	var impulseLength=this.impulse.mag()

	var accelLine=document.getElementById("accel");
	var accel=impulseLength-(this.deadRadius)
	var ch_col;
	if (accel<0) {
		accel=0
		if (accelLine)
			accelLine.setAttribute('visibility','hidden');
		ch_col='red'
	}
	else {
		if (accelLine) {
			accelLine.setAttribute('x1',gCenter.x+Math.sin(this.angle)*this.deadRadius)
			accelLine.setAttribute('y1',gCenter.y-Math.cos(this.angle)*this.deadRadius)
			accelLine.setAttribute('x2',gCenter.x+Math.sin(this.angle)*impulseLength)
			accelLine.setAttribute('y2',gCenter.y-Math.cos(this.angle)*impulseLength)
			accelLine.setAttribute('visibility','visible');
		}

		ch_col='green'
	}
//	var crosshair=document.getElementById('crosshair');
//	if (crosshair)
//		crosshair.setAttributeNS(null,'stroke',ch_col)
	accel*=this.accelCo
	
	var targetAngle=Math.atan2(this.impulse.x,this.impulse.y)
	var da=clipAngle(targetAngle-this.angle);
	if (da<-this.max_da) da=-this.max_da
	else if (da>this.max_da) da=this.max_da
	this.angle=clipAngle(this.angle+da)
	
	this.acc.polar(this.angle,accel)
	this.acc.y+=this.gravity;
	this.vel.add(this.acc)
	this.vel.mul(this.dragCo)
	this.pos.add(this.vel)
	
	this.update()
}
Ship.prototype.update=function() {
	this.element.setAttribute("transform",'translate('+this.pos.x+','+this.pos.y+') rotate('+this.angle*180/Math.PI+')')
	simspace=document.getElementById('simspace')
//	simspace.setAttribute('viewBox',this.pos.x-gCenter.x+' '+this.pos.y-gCenter.y+' '+gRes.x+' '+gRes.y)
	score(parseInt(this.pos.x-gCenter.x)+' '+parseInt(this.pos.y-gCenter.y)+' '+gRes.x+' '+gRes.y)
}
Ship.prototype.onMouseMove=function(x, y) {
//	var crosshair=document.getElementById("crosshair");
//	crosshair.setAttribute("cx", x)
//	crosshair.setAttribute("cy", y)
	
	this.impulse.x=x-gCenter.x
	this.impulse.y=gCenter.y-y
}

function buildShipPoints(r,w,b) {
	// r=radius (0==nothing,10==20-unit diameter)
	// w=wing angle (0==pin,1==fat)
	// b=back (1==outie tail, -1==empty gShip.element)
	w=(w)*90
	return polarToCart(0,r)+polarToCart(180-w,r)+polarToCart(180,r*b)+polarToCart(180+w,r)
}

///////////////////
function initStars() {
	Star.prototype.minScape=new coords(-Star.prototype.outOfMind.x,-Star.prototype.outOfMind.y)
	Star.prototype.maxScape=new coords(Star.prototype.outOfMind.x+gRes.x,Star.prototype.outOfMind.y+gRes.y)

	Star.prototype.scapeExt=new coords(0,0)
	Star.prototype.scapeExt.set(Star.prototype.maxScape)
	Star.prototype.scapeExt.sub(Star.prototype.minScape)

	gStars=new Array()

	var e=null
	var i=0
	do {
		e=document.getElementById("star"+i);
		if (e==null)
			break;
		gStars[i]=new Star(e)
		i++;
	}	while(true)
}

function Star(e) {
	this.element=e
	this.pos=new coords(0,0)
	this.screenPos=new coords(0,0)

	this.randomise()
	this.randomiseX()
	this.randomiseY()
	this.update()
}
Star.prototype.outOfMind=new coords(50,50)

Star.prototype.tick=function() {
	this.calcScreenPos()
	this.update()
}
Star.prototype.update=function() {
	this.element.setAttribute("transform",'translate('+this.screenPos.x+','+this.screenPos.y+')')
}
Star.prototype.randomise=function() {
	var family=(Math.random()<.5)?'serif':'sans-serif';
	this.element.setAttribute("style",'font-family:'+family+';font-size:'+Math.round(Math.random()*32+12)+'px')
	var r=randomColorComp()
	var g=randomColorComp()
	var b=randomColorComp()
	this.element.setAttribute("fill",'rgb('+r+','+g+','+b+')')
}
function randomColorComp() {
	return Math.round(Math.random()*192+63)
}
Star.prototype.randomiseX=function() {
	this.pos.x=parseInt((Math.random()-.5)*this.scapeExt.x+gShip.pos.x)
}
Star.prototype.randomiseY=function() {
	this.pos.y=parseInt((Math.random()-.5)*this.scapeExt.y+gShip.pos.y)
}
Star.prototype.calcScreenPos=function() {
	this.screenPos.set(this.pos)
	this.screenPos.add(gCenter)
	this.screenPos.sub(gShip.pos)
	this.screenPos.round()
	this.clipScreenPos()
}
Star.prototype.clipScreenPos=function() {
	if (this.screenPos.x<this.minScape.x) {
		this.pos.x+=this.scapeExt.x
		this.randomise()
		this.randomiseY()
	}
	else if (this.screenPos.x>this.maxScape.x) {
		this.pos.x-=this.scapeExt.x
		this.randomise()
		this.randomiseY()
	}

	if (this.screenPos.y<this.minScape.y) {
		this.pos.y+=this.scapeExt.y
		this.randomise()
		this.randomiseX()
	}
	else if (this.screenPos.y>this.maxScape.y) {
		this.pos.y-=this.scapeExt.y
		this.randomise()
		this.randomiseX()
	}
}
///////////////////
function polarToCart(a,d) {
	a*=Math.PI/180
	return Math.round(Math.sin(a)*d)+','+Math.round(-Math.cos(a)*d)+' '
}

/* running */
function tick() {
	gShip.tick()
	for (var i=0;i<gStars.length;++i) {
			gStars[i].tick()
	}
}
function onkey(event) {
	score("up")
}
function onmousemove(event) {
	// note: some browsers might prefer pageX/pageY
	gShip.onMouseMove(event.clientX,event.clientY)
}

/* misc */
function coords(x,y) {
	this.x=x
	this.y=y
	this.set=function(r) {
		this.x=r.x
		this.y=r.y
	}
	this.add=function(r) {
		this.x+=r.x
		this.y+=r.y
	}
	this.sub=function(r) {
		this.x-=r.x
		this.y-=r.y
	}
	this.mul=function(r) {
		this.x*=r
		this.y*=r
	}
	this.magSquared=function() {
		return this.x*this.x+this.y*this.y
	}
	this.mag=function() {
		return Math.sqrt(this.magSquared())
	}
	this.round=function() {
		this.x=parseInt(this.x)
		this.y=parseInt(this.y)
	}
	this.polar=function(a,d) {
		this.x=Math.sin(a)*d
		this.y=-Math.cos(a)*d
	}
}
// note: only clips angles between +/- 2*PI
function clipAngle(a) {
	if (a>Math.PI) return a-Math.PI*2
	else if (a<-Math.PI) return a+Math.PI*2
	else return a
}
function score(str) {
	gScoreNode.nodeValue = str
}
