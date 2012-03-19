<?php 
	include "../../php/page.php";
	begin_head("Real-time Ray Tracing", "wallpaper2.png", "../../");
?>
	<style type="text/css">
		img { text-align:center;width:100% }
	</style>
<?php
	end_head();
	begin_body();
?>
	<h1>Real-time Ray Tracing</h1>
	<p>
		Graphics rendering is one of my interests. 
		Despite a relative lack of expertise in real-time rendering and 3D geometry (or perhaps because of this), my work in computer games has convinced me that there must be a primitives/pixels threshold beyond which it becomes more efficient to render scenes on a per-pixel basis.
	</p>
	<p>
		Current advances in dedicated 3D graphics hardware (from the likes of Silicon Graphics, 3dfx (RIP) and now nVidia) mean that scenes of immense complexity and detail can be rendered in real-time. 
		This entails passing to a graphics card the co-ordinates and texturing information for many thousands of polygons (triangles), each of which are rendered with depth sorting to produce complex forms.
	</p>
	<p>
		It won't be long before the number of triangles in a scene exceeds the number of pixels in the resultant image. If a scene has <i>n</i> triangles, the number of vertices (corners) involved is between <i>n</i> and 3<i>n</i>.
		Each vertex requires various calculations including shading to give the shape a sense of depth. 
		It seems crazy to me to be performing these calculations more times than there are pixels on the screen.
	</p>
	<p>
		Many advanced techniques for producing more realistic effects in images such as shadowing, radiosity (realistically calculated 'soft' shadows) and reflectivity are becoming commonplace in games. 
		Some of these can be produced fairly well using modern techniques but all of them can be done at least as easily using traditional ray-tracing techniques - especially reflectivity.
	</p>
	<p>
		There are other reasons why a per-pixel approach to real-time rendering has advantages. 
		For example, because each pixel is calculated independently, it is the ideal application for multi-processor systems which are able to share scene information. 
		Such systems include dual-processor PCs right up to Silicon Graphics super computers. 
		Also, a per-poly scene is visibly incomplete until the last polygon has been drawn. 
		This means that while the current frame of animation is being displayed, the next one is being rendered on a 'back buffer' and these are swapped around when the scene has become completed. 
		By contrast, a per-pixel system can simply carry on rendering pixels as fast as it can with relatively little interruption and without having to wait for the right moment to flip buffers.
	</p>
	<p>
		To advance my argument, I have written a demonstration real-time ray-tracer which includes real-time radiosity. 
		It's written in C++ for Win32 systems and is far from optimal. 
		It doesn't use DirectX or any graphics acceleration that isn't an integral part of the window manager (specifically Device Independent Bitmaps or DIBs). 
		The images it produces are quite small and it isn't fast enough to be a practical alternative to polygon rendering yet. 
		But I believe it is a taste of things to come.
	</p>
	<p>
		I've provided several version which show off different aspects of the engine:
	</p>
	<p>
		<a href="rt1_bsp_demo.zip">This version</a> shows the KD-like tree which ensure space is divided for better searching.
	</p>
	<p>
		<a href="rt1_light_demo.zip">This version</a> is the same structure with three none-too pleasantly light sources rotating around it - not pretty but very well lit!
	</p>
	<p>
		<a href="rt1_radiosity_demo.zip">This version</a> has a few textures associated with it and shows of a very simple approximation of a real-time radiosity system. 
		It's not exactly Quake 3 (although I pinched a few texture from the demo - sorry) and the radiosity is not fast but it is possible ... sort-of.
	</p>
	<p>
		You can move the camera with 'A', 'S', 'D', 'W', 'Space' and 'Shift' and rotate it with the arrow keys and 'Q' and 'E'. 
		Press 'T' to toggle information about the image and 'B' to display a graphical representation of the KD-like space partitioning tree being used.
	</p>
	<hr />
	<p>
		Here are a couple of example images. 
		The first shows KD information in the form of gray 'walls' which are partitioning space.
	</p>
	<p><img src="tracey1.jpg" alt="Ray-traced scene including space partitioning information" /></p>
	<hr />
	<p>
		This image is from the downloadable demo. Observe the soft shadow the shiny sphere casts on the earth. 
		That's calculated in real-time ... sort of!
	</p>
	<p><img src="tracey2.jpg" alt="Ray-traced scene with example of soft shadow" /></p>
<?php end_body();?>