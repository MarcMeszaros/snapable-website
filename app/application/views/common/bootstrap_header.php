<?php if (isset($navigation)) { ?>
<nav class="navbar navbar-default" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-account">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand float" href="/"><img src="/assets/img/logo-circle.png" width="30" height="30" alt="Snapable" /> Snapable</a>
    </div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="#navbar-account">
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<?php if(isset($navigation['session'])) { ?>
              <?php if(isset($navigation['session']['email'])) {
                $default = 'https://snapable.com/assets/img/photo_blank.png';
                $grav_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($navigation['session']['email']))) . "?d=" . urlencode($default ). "&s=40";
              ?>
							<img src="<?php echo $grav_url; ?>" width="25" height="25" />
              <?php } ?>
							<?php if (isset($navigation['full_name'])) { ?>
							<strong><?php echo $navigation['full_name']; ?></strong> <b class="caret"></b>
							<?php } ?>
						<?php } else { ?>
							Account <b class="caret"></b>
						<?php } ?>
					</a>
					<ul class="dropdown-menu">
						<?php if (isset($navigation['session'])) { ?>
							<?php if (isset($navigation['session']['account_uri'])) { ?>
							<li><a href="/account/dashboard">Account</a></li>
							<li class="divider"></li>
							<?php } ?>
							<li><a href="/account/signout/">Logout</a></li>
						<?php } else { ?>
							<li><a href="/account/login/?next={{ request.path }}">Login</a></li>
						<?php } ?>
					</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav>
<?php } ?>
<div id="container" class="container"> <!-- container -->
