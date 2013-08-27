	<div id="homeHeadWrap">
		<?php if ( isset($loggedInBar) )
		{
			if (  $loggedInBar == "owner" )
			{
				$arr = $this->session->userdata('logged_in');
				$name = $arr['first_name'] . " " . substr($arr['last_name'], 0,1) . ".";
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
						<ul></ul>
						
						<div id="checkoutReviewInstructions">instructions</div>
						
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