<?php if ( isset($loggedInBar) ) {
	if (  $loggedInBar == "owner" ) {
		$arr = $this->session->userdata('logged_in');
		$name = $arr['first_name'] . " " . substr($arr['last_name'], 0,1) . ".";
		$signout_url = "/account/signout";
	} else if ( $loggedInBar == "guest" ) {
		$arr = $this->session->userdata('guest_login');
		$name = $arr['name'];
		$signout_url = "/event/" . $url . "/signout";
	} else {
		$name = "Unknown";
		$signout_url = "unknown";
	}
} ?>
<nav id="homeHeadWrap" class="navbar navbar-default navbar-fixed-top" role="navigation">
	<?php if (isset($name) && isset($signout_url)) { ?>
	<div id="signedInBar"><div id="signedInText">Signed In as <strong><?= $name ?></strong> / <a href=" <?= $signout_url ?>">Sign Out</a></div></div>
	<? } ?>

	<div class="container">
		<a class="centeredLogo" href="/"><img src="/assets/img/snapable-sm.png" alt="Snapable" /></a>
	</div>
</nav>

<div id="restOfPageWrap">