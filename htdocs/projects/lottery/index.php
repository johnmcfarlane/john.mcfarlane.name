<?php 
	include("../../php/page.php");
	begin_head("Lottery Number Generator", "wallpaper2.png", "../../"); 
	end_head();
	begin_body();
?>

<h1>Lottery Number Generator</h1>

<h2>	What?</h2>
<p>	Here's a computer program I wrote in late August, 2007.
	It generates lottery numbers that are compatible with the <a href="http://www.arizonalottery.com/">Arizona state lottery</a> (and probably quite a few others besides).</p>

<h2>	How?</h2>
<p>	The program implements a simple <a href="http://en.wikipedia.org/wiki/Cellular_automata">Cellular Automaton</a>. 
	<acronym title="Cellular Automaton">CA</acronym>s are grids of cells which can each be in a number of discrete states. 
	The <acronym title="Cellular Automaton">CA</acronym> also has a set of rules which dictate in what state a cell will be in the next time step.
	These rules typically take into account the states of the neighbouring cells.
	While in principle such systems are simple, the interaction of many cells in a grid can quickly produce highly random results.
	Hence, they make excellent random number generators.</p>
<p>	The specific <acronym title="Cellular Automaton">CA</acronym> I have chosen uses a 3-dimensional grid of cells which can each be in one of two states.
	Each cell takes all 27 neighbouring cells (including itself) into account when determining what state to be in next.
	This means that there are 2<sup>27</sup> (over 130 million) states the neighbourhood can be in and a separate rule for each of these.</p>
<p>	I've made the rules random. I've also made the initial state of the grid random. 
	(When I say random, I mean quite random for a computer program; the exact time the program starts dictates the numbers that are used to determine the starting conditions.)</p>
<p>	The grid is 73<sup>3</sup> cells in size and the simulation ticks over for 75 iterations.
	By this time the cells in the grid are in an extremely jumbled state.
	The program finally pecks at cells in the grid to pull out bits which make up numbers until we have our lottery numbers!</p>

<h2>	Why?</h2>
<p>	I've never been a big fan of lotteries, instead preferring games that don't have lousy odds. 
	(Once or twice I've picked out numbers, sat in front of the telly and shouted: "Yes, I saved a pound!"
	That's about it though.)
	My wife, on the other hand, is a hardened gambling fiend and at time of writing, the local <a href="http://www.arizonalottery.com/Powerball/">Powerball jackpot</a> stands at over a hundred million dollars.</p>
<p>	There's no way I can improve the odds of winning. 
	I can, however, try and reduce the chance of sharing the jackpot with anyone else.
	Any set of numbers we pick is just as lucky as any other so we might as well make them as random as possible.
	That way, there's less chance that anyone else has picked the same ones.</p>
<p>	(Imagine the numbers 1, 2, 3, 4 and 5 come up. 
	This may seem highly improbably but actually, they're just as likely as any others.
	Now imagine just how many unimaginative people have picked those numbers - quite a few!
	If those numbers come up, the winners will be splitting the pot many ways.)</p>
<p>	Ok, maybe I'm getting a case of gold fever. 
	It's probably not even a good week to buy a ticket but any excuse to write a nerdy computer program is a good excuse to me.</p>

<h2>	Where?</h2>
<p>	The source code for the program is available as a <a href="lottery.zip">zip archive</a>.
	I've only tested it using the <acronym title="GNU Compiler Collection">gcc</acronym> compiler under OS X 10.3.9 (GCC3.3) and Ubuntu Feisty (GCC4.1.2) with the following command:</p>
<tt>	g++ -O2 *.cpp</tt>
<p>	One or two optional numbers passed to the program as command-line parameters provide random seeds for the standard random number generator (<code>rand()</code>) used in the program.
	Alternatively, time is used to generate the seeds.</p>

<h2>	When?</h2>
<p>	The Lottery Number Generator was written between 22nd - 24th August 2007.</p>
<p>	There are plenty of improvements I could make but I can spot obsession in its early stages so I'm quitting now. 
	The best improvement I can think to suggest is to collect previous numbers used by winners and weight the results so as to avoid those numbers. 
	For example, results which contain the number seven are a bad idea because so many people choose this number.</p>

<?php end_body(); ?>