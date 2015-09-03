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
	<?php if (isset($loggedInBar) && isset($name) && isset($signout_url)) { ?>
	<div id="signedInBar"><div id="signedInText">Signed In as <strong><?= $name ?></strong> / <a href=" <?= $signout_url ?>">Sign Out</a></div></div>
	<?php } ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-offset-1"><a href="/"><img src="/assets/img/snapable-sm.png" alt="Snapable" /></a></div>
		</div>
		<div class="row">
			<div class="col-lg-offset-1"><h4 id="headTagline">Every moment. Captured.</h4></div>
		</div>
	</div>
</nav>
