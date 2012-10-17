
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title><?php if ( isset($title) ) { echo $title; } else { echo "Snapable - The easiest way to instantly capture every photo at your wedding without missing a single moment."; } ?></title>
    
    <meta name="Keywords" content="" /> 
	<meta name="Description" content="" />
    
    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" /> 
	<link rel="SHORTCUT ICON" href="/favicon.ico"/> 
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700' rel='stylesheet' type='text/css'>
    <?php if ( isset($css) ) { ?>
    <link rel="stylesheet" href="/min/c/<?= $css ?>" type="text/css" media="screen" />
    <?php } ?>
    <?php if ( isset($js) ) { ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/min/j/<?= $js ?>"></script>
    <?php } ?>
    <script type="text/javascript">
<?php if ( $_SERVER['HTTP_HOST'] == "snapable.com" || $_SERVER['HTTP_HOST'] == "www.snapable.com" ) { ?>  
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-295382-36']);
	  _gaq.push(['_setDomainName', 'snapable.com']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
 <?php } else { ?> 
 	var _gaq = _gaq || [];
 <?php } ?>
	</script>
	
</head>

<body id="top">

	<div id="notification"></div>

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
			echo "<div id='signedInBar'><div id='signedInText'>Signed In as <strong>" . $name . "</strong> / " . $dash_link . "<a href='" . $signout_url . "'>Sign Out</a></div></div>";
		}
		?>
		<div id="homeHead">
			<?php if ( isset($type) && $type == "checkout" ) { ?>
			<div id="eventBackWrap"><a id="eventBack" href="<?= $url ?>">‹ Back to Event</a></div>
			<?php } ?>
			<?php if ( isset($type) && $type == "event" && isset($loggedInBar) && $loggedInBar == "owner" && $_SERVER['HTTP_HOST'] == "snapable" ) { ?>
			<div id="upgradeChoicesMenu">
				<div class="upgradeMenuTop">&nbsp;</div>
				<div class="upgradeMenuWrap">
					<ul class="menuContents"></ul>
				</div>
			</div>
			<div id="checkoutMenu">
				<div class="checkoutMenuTop">&nbsp;</div>
				<div class="checkoutMenuWrap">
					<div id="checkoutReviewTitle">Order Review</div>
					<div class="menuContents">
						<ul>
							<li>
								<div class="checkoutReviewLeft">
									<div class="checkoutReviewItem"><span>1 x </span>12 Print Upgrade <a class="checkoutRemove" href="#" title="Remove">X</a></div>
									<div class="checkoutReviewNote">You’ve only chosen 4 photos so far, you can pick up to 21 more.</div>
								</div>
								<div class="checkoutReviewRight">$11</div>
							</li>
							<li>
								<div class="checkoutReviewLeft">
									<div class="checkoutReviewItem"><span>7 x </span>Single Prints @ $1 ea. <a class="checkoutRemove" href="#" title="Remove">X</a></div>
								</div>
								<div class="checkoutReviewRight">$7</div>
							</li>
						</ul>
						
						<div id="checkoutReviewSubTotalText" class="checkoutReviewBottomText">Sub-total:</div>
						<div id="checkoutReviewSubTotalNum" class="checkoutReviewBottomNum">$17</div>
						<div class="clearit">&nbsp;</div>
						
						<div id="checkoutReviewShippingText" class="checkoutReviewBottomText">Shipping:</div>
						<div id="checkoutReviewShippingNum" class="checkoutReviewBottomNum freeShipping">FREE</div>
						<div class="clearit">&nbsp;</div>
						
						<div id="checkoutReviewTotalText" class="checkoutReviewBottomText">Total:</div>
						<div id="checkoutReviewTotalNum" class="checkoutReviewBottomNum">$17</div>
						<div class="clearit">&nbsp;</div>
						
						<a id="checkoutReviewContinue" href="#">Continue</a>
						
					</div>
				</div>
			</div>
			<div id="checkout-buttons">
				<a id="upgradeChoices" href="#">See Upgrades</a>
				<div id="in-cart">
					<div id="in-cart-number">0</div>
					Photos in cart
				</div>
				<a id="checkout" href="#">Checkout</a>
			</div>
			<a id="headLogo" href="/">Snapable</a>
			<?php } else { ?>
			<a id="centeredLogo" href="/">Snapable</a>
			<?php } ?>
			
		</div>
	</div>
	
	<div id="restOfPageWrap">