    <?php if ( isset($loggedInBar) )
		{
			echo "<style type='text/css'> #homeHeadWrap { height: 100px; } </style>";
		}
	?>
	<div id="homeHeadWrap">
	
		<?php if ( isset($loggedInBar) )
		{
			if (  $loggedInBar == "owner" )
			{
				/*
				print_r($this->session->userdata('logged_in'));
				
				Array ( [email] => andrew@snapable.com [fname] => Andrew [lname] => Draper [resource_uri] => /private_v1/user/92/ [loggedin] => 1 )
				*/
				$arr = $this->session->userdata('logged_in');
				$name = $arr['fname'] . " " . substr($arr['lname'], 0,1) . ".";
				$signout_url = "/account/signout";
				$dash_link = "<a href='/account/dashboard'>Dashboard</a> / ";
			}
			else if ( $loggedInBar == "guest" )
			{
				$arr = $this->session->userdata('guest_login');
				$name = $arr['name'];
				$signout_url = "/event/" . $url . "/signout";
				$dash_link = "";
			} else {
				$name = "Unknown";
				$signout_url = "unknown";
				$dash_link = "";
			}
			echo "<div id='signedInBar'><div id='signedInText'>Signed In as <strong>" . $name . "</strong> / <a href='" . $signout_url . "'>Sign Out</a></div></div>";
		}
		?>
		
		<div id="homeHead">
			
			<a id="headLogo" href="/">Snapable</a>
			
			<?php if ( !isset($noTagline) ) { ?>
			<div id="headTagline">Every moment. Captured.</div>
			<?php 
			}
			if ( isset($linkHome) ) { ?>
			<div id="headNav"><a href="/">‹ Back to Home</a></div>
			<?php } ?>
			
		</div>
	</div>
	
	<div id="restOfPageWrap">