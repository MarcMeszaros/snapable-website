<nav id="homeHeadWrap" class="navbar navbar-default navbar-fixed-top" role="navigation">
	<?php if (isset($display_name) && isset($signout_url)) { ?>
	<div id="signedInBar"><div id="signedInText">Signed In as <strong><?= $display_name ?></strong> / <a href="<?= $signout_url ?>">Sign Out</a></div></div>
	<?php } ?>

	<div class="container">
		<a class="centeredLogo" href="/"><img src="/assets/img/snapable-sm.png" alt="Snapable" /></a>
	</div>
</nav>

<div id="restOfPageWrap">