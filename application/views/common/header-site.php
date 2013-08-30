	<div id="wrap" class="container">
		
		<div class="row">
			<div class="col-lg-2 col-lg-offset-1">
			<div id="left">
				<a id="logo" href="/">Snapable</a>
				<ul>
					<li><a href="/">Home</a></li>
					<li><a href="faq"<?php if($active == "faq") { echo ' class="active"'; } ?>>FAQ</a></li>
					<li><a href="terms"<?php if($active == "terms") { echo ' class="active"'; } ?>>Terms</a></li>
					<li><a href="privacy"<?php if($active == "privacy") { echo ' class="active"'; } ?>>Privacy</a></li>
					<li><a href="contact"<?php if($active == "contact") { echo ' class="active"'; } ?>>Contact</a></li>
				</ul>

				<a id="signup-link" href="/signup" class="ajax btn" onClick="_gaq.push(['_trackEvent', 'Signups', 'Clicked', 'Help Sidebar']);">Sign up</a>

				<dl>
					<dt>Connect:</dt>
					<dd><a href="http://twitter.com/getsnapable" target="_blank" id="twitter">Twitter</a></dd>
					<dd><a href="http://facebook.com/snapable" target="_blank" id="facebook">Facebook</a></dd>
					<dd><a href="http://pinterest.com/snapable" target="_blank" id="pinterest">Pinterest</a></dd>
				</dl>
				
				<div class="copyright">&copy; <?= date("Y") ?> Snapable</div>
			</div>
			</div>
			
