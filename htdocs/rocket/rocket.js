/* global variables */
var initialised=false
var svgNS='http://www.w3.org/2000/svg';
var gRes=null
var gCenter=null
var gScoreNode=null
//var gWin=null
var gEntities=null
var gShip=null
var gTimer=self.setInterval(tick,50)


///////////////////
// Event handlers

function onload() 
{
	if (initialised)
		return
	initialised=true
	
	var simspace=document.getElementById("simspace")
	gRes=new coords(parseInt(simspace.getAttribute("width")),parseInt(simspace.getAttribute("height")))
	gCenter=new coords(gRes.x*.5,gRes.y*.5)

	gScoreNode = document.getElementById("score_text").childNodes[0]
	score("Rocket Demo")

	/*var fe = window.frameElement
	var od = fe?fe.ownerDocument:document
	if (od) {
		var dv = od.defaultView
		if (dv) {
			gWin = dv
		}
	}*/
	
	initEntities()
	
	initShip()
	initExhaust()
	initStars()
	initBullets()
	
	//document.documentElement.addEventListener('mousemove', onMouseMove, false)
	simspace.addEventListener('mousemove', onMouseMove, false)
	simspace.addEventListener('click', onClick, false)
	simspace.addEventListener('mouseout', onMouseOut, false)
}

function onMouseMove(event) 
{
	gShip.onCommandMove(event.clientX,event.clientY)	// note: some browsers might prefer pageX/pageY
}

function onClick() 
{
	gShip.onCommandFire()
}

function onMouseOut(event) 
{ 
	gShip.onCommandStop()
}

//onload

//onclick
//onmousemove
//onmouseout
//onmouseover
//onkeydown
//onkeyup


///////////////////
// Entities

function initEntities() 
{
	gEntities=Array()
}

function addEntity(e) 
{
	gEntities[gEntities.length]=e
}

function tick() {
	for (var i=0;i<gEntities.length;++i) {
		gEntities[i].tick()
	}
}


///////////////////
// Ship class

function Ship() {
	this.element=document.getElementById("ship")
	this.element.setAttribute("points",buildShipPoints(this.radius,this.wingAngle,this.back))
	this.accel=0
	this.angle=0
	this.pos=new coords(0,0)
	this.vel=new coords(0,0)
	this.acc=new coords(0,0)
	this.impulse=new coords(0,0)
	this.magazine=Array()
}

Ship.prototype.radius=15
Ship.prototype.wingAngle=.5
Ship.prototype.back=.25
Ship.prototype.radius=15
Ship.prototype.deadRadius=30	//circle around ship where mouse has no effect
Ship.prototype.deadRadiusSq=(Ship.prototype.deadRadius*Ship.prototype.deadRadius)	//circle around ship where mouse has no effect
Ship.prototype.accelCo=0.01
Ship.prototype.dragCo=.95
Ship.prototype.max_da=.25	// max delta angle - ship turn per tick
Ship.prototype.gravity=0

function initShip() 
{
	gShip = new Ship()
	gShip.update()
	/*var deadzone=document.getElementById('deadzone')
	if (deadzone) {
		deadzone.setAttribute('r',Ship.prototype.deadRadius)
		deadzone.setAttribute('cx',gCenter.x)
		deadzone.setAttribute('cy',gCenter.y)
	}*/
	addEntity(gShip)
}

Ship.prototype.tick=function() {
	var impulseLength=this.impulse.mag()

	//var accelLine=document.getElementById("accel");
	this.accel=impulseLength-(this.deadRadius)
	var ch_col;
	if (this.accel<0) {
		this.accel=0
		/*if (accelLine)
			accelLine.setAttribute('visibility','hidden');*/
		ch_col='red'
	}
	else {
		/*if (accelLine) {
			accelLine.setAttribute('x1',gCenter.x+Math.sin(this.angle)*this.deadRadius)
			accelLine.setAttribute('y1',gCenter.y-Math.cos(this.angle)*this.deadRadius)
			accelLine.setAttribute('x2',gCenter.x+Math.sin(this.angle)*impulseLength)
			accelLine.setAttribute('y2',gCenter.y-Math.cos(this.angle)*impulseLength)
			accelLine.setAttribute('visibility','visible');
		}*/

		ch_col='green'
	}
	
	if (impulseLength>0) {
		var targetAngle=Math.atan2(this.impulse.x,this.impulse.y)
		var da=clipAngle(targetAngle-this.angle);
		if (da<-this.max_da) da=-this.max_da
		else if (da>this.max_da) da=this.max_da
		this.angle=clipAngle(this.angle+da)
	}
	
	this.accel*=this.accelCo
	this.acc.polar(this.angle,this.accel)
	this.vel.add(this.acc)
	this.vel.y+=this.gravity
	this.vel.mul(this.dragCo)
	this.pos.add(this.vel)
	
	this.update()
}
Ship.prototype.update=function() {
	this.element.setAttribute("transform",'translate('+gCenter.x+','+gCenter.y+') rotate('+this.angle*180/Math.PI+')')
}
Ship.prototype.onCommandMove=function(x, y) {
	this.impulse.x=x-gCenter.x
	this.impulse.y=gCenter.y-y
}
Ship.prototype.onCommandStop=function() {
	this.impulse.x=0
	this.impulse.y=0
}
Ship.prototype.onCommandFire=function() {
	var b=this.getOldestBullet()
	b.age=0
	this.getNose(b.pos)
	b.vel.polar(this.angle,b.speed)
	b.vel.add(this.vel)
}
Ship.prototype.getOldestBullet=function() {
	choice=null
	choice_age=-1
	for (i=0;i<this.magazine.length;++i) {
		m=this.magazine[i]
		a=m.age
		if (a==-1)
			return m
		else if (a>choice_age) {
			choice=m
			choice_age=a
		}
	}
	return choice
}
Ship.prototype.getNose=function(n) {
	n.set(this.pos)
	d=this.radius
	n.x+=Math.sin(this.angle)*d
	n.y-=Math.cos(this.angle)*d
}
Ship.prototype.getTail=function(t) {
	t.set(this.pos)
	d=this.radius*this.back
	t.x+=Math.sin(Math.PI+this.angle)*d
	t.y-=Math.cos(Math.PI+this.angle)*d
}
Ship.prototype.getScreenX=function(x) { 
	return parseInt(x+gCenter.x-this.pos.x) 
}
Ship.prototype.getScreenY=function(y) { 
	return parseInt(y+gCenter.y-this.pos.y) 
}

function buildShipPoints(r,w,b) 
{
	// r=radius (0==nothing,10==20-unit diameter)
	// w=wing angle (0==pin,1==fat)
	// b=back (1==outie tail, -1==empty gShip.element)
	w=(w)*90
	return polarToCart(0,r)+polarToCart(180-w,r)+polarToCart(180,r*b)+polarToCart(180+w,r)
}


///////////////////
// Exhaust class

function Exhaust(e,i) {
	this.element=e
	//e.setAttribute("xlink:href","star.png")

	this.pos=new coords(0,0)
	this.vel=new coords(0,0)
	this.screenPos=new coords(0,0)
	this.power=0

	//this.r=i*this.radiusDelta
	this.age=i
	this.update(0)
}

Exhaust.prototype.maxAge=-1
Exhaust.prototype.radiusDelta=0.1
Exhaust.prototype.emissionWaft=1
Exhaust.prototype.dragCo=.9
Exhaust.prototype.powerCo=10

function initExhaust() {
	i=0
	do {
		e=document.getElementById("exh"+i);
		if (e==null)
			break;
		addEntity(new Exhaust(e,i))
		i++;
	}	while(true)
	
	Exhaust.prototype.maxAge=i
}

Exhaust.prototype.tick=function() {
	++this.age
	var r=this.age*this.radiusDelta*this.power
	this.update(r)
	if (this.age>=this.maxAge)
		this.emit()
	
	this.pos.add(this.vel)
	this.vel.mul(this.dragCo)
}
Exhaust.prototype.update=function(r) {
	this.element.setAttribute('cx',gShip.getScreenX(this.pos.x))
	this.element.setAttribute('cy',gShip.getScreenY(this.pos.y))
	this.element.setAttribute('r',parseInt(r))
	p=1-this.age/this.maxAge
	p*=p
	this.element.setAttribute('fill-opacity',p)
	c=parseInt(223*p+32)
	this.element.setAttribute('fill','rgb(255,'+c+','+32+')')
}
Exhaust.prototype.emit=function() {
	gShip.getTail(this.pos)
	this.age=0
	this.vel.set(gShip.vel)
	//this.vel.mul(-5)
	a=gShip.accel
	this.vel.x+=(Math.random()-.5)*a*this.emissionWaft
	this.vel.y+=(Math.random()-.5)*a*this.emissionWaft
	this.power=a*this.powerCo*(Math.random()+.5)
}


///////////////////
// Bullet class

function Bullet(e) {
	this.element=e
	
	this.pos=new coords(0,0)
	this.vel=new coords(0,0)
	this.age=-1
}

Bullet.prototype.speed=20

Bullet.prototype.tick=function() {
	this.element.setAttribute('x2',gShip.getScreenX(this.pos.x))
	this.element.setAttribute('y2',gShip.getScreenY(this.pos.y))
	this.pos.add(this.vel)
	this.element.setAttribute('x1',gShip.getScreenX(this.pos.x))
	this.element.setAttribute('y1',gShip.getScreenY(this.pos.y))
	this.age++
}

// TODO: refactor the init fns as they are all mostly the same
function initBullets() {
	i=0
	do {
		e=document.getElementById("bullet"+i);
		if (e==null)
			break;
		b=new Bullet(e)
		addEntity(b)
		gShip.magazine[gShip.magazine.length]=b
		i++;
	}	while(true)
}


///////////////////
// Star class

function Star(e) {
	this.element=e

	this.pos=new coords(0,0)
	this.screenPos=new coords(0,0)

	this.randomise()
	this.randomiseX()
	this.randomiseY()
	this.update()
}

Star.prototype.minHeight=12
Star.prototype.maxHeight=36

Star.prototype.outOfMind=new coords(Star.prototype.maxHeight,Star.prototype.maxHeight)
Star.prototype.heightExtent=Star.prototype.maxHeight-Star.prototype.minHeight

function initStars() {
	Star.prototype.minScape=new coords(-Star.prototype.outOfMind.x,-Star.prototype.outOfMind.y)
	Star.prototype.maxScape=new coords(gRes.x+Star.prototype.outOfMind.x,gRes.y+Star.prototype.outOfMind.y)

	Star.prototype.scapeExt=new coords(0,0)
	Star.prototype.scapeExt.set(Star.prototype.maxScape)
	Star.prototype.scapeExt.sub(Star.prototype.minScape)

	i=0
	do {
		e=document.getElementById("star"+i);
		if (e==null)
			break;
		addEntity(new Star(e))
		i++;
	}	while(true)
}

Star.prototype.tick=function() {
	this.calcScreenPos()
	this.update()
}
Star.prototype.update=function() {
	this.element.setAttribute("transform",this.transBegin+parseInt(this.screenPos.x)+','+parseInt(this.screenPos.y)+this.transEnd)
	//this.element.setAttribute("x",this.screenPos.x)
	//this.element.setAttribute("y",this.screenPos.y)
}
Star.prototype.randomise=function() {
	size=Math.random()*this.heightExtent+this.minHeight
	s=parseInt(size)
	this.element.setAttribute("height",s)
	this.element.setAttribute("width",s)
	this.transBegin='translate('
	o=parseInt(size*-.5)
	this.transEnd=') rotate('+360*Math.random()+') translate('+o+' '+o+')'
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
	this.screenPos.x=gShip.getScreenX(this.pos.x)
	this.screenPos.y=gShip.getScreenY(this.pos.y)
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
// misc fns

function polarToCart(a,d) {
	a*=Math.PI/180
	return Math.round(Math.sin(a)*d)+','+Math.round(-Math.cos(a)*d)+' '
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

//////////////////////
// gets around incompatabilities between different browsers
onload()
