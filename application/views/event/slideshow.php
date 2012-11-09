    <script type="text/javascript">
    var url = "<?= $url ?>"
    </script>

	<div id="toolbar">
		<a href="#" id="hide" class="arrowRight">Hide</a><a href="#" id="prev">prev</a><a href="#" id="pause" rel="pause" class="btnPause">pause</a><a href="#" id="next">next</a><a href="/event/<?= $url ?>" id="back">back to event</a>
	</div>
<!--
Slideshow baby, black background, get photo ids for event (only load 1 image at a time, once loaded display, also check for new photos before displaying/loading next image (if there's a new one start over and display that image first), toolbar with: hold time, hide tool bar, next/prev
-->

	<div id="spinner"><img src="/assets/img/spinner-grey_on_black.gif" alt="Loading..." /></div>
	
	<ul id="photos"></ul>
