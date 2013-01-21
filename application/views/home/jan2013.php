<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo (isset($title)) ? 'Snapable - '.$title : "Snapable - The easiest way to instantly capture every photo at your wedding without missing a single moment."; ?></title>
    
    <meta name="Keywords" content="<?php echo (isset($keywords) && is_array($keywords)) ? implode(', ',$keywords): ''; ?>" /> 
	<meta name="Description" content="<?php echo (isset($description)) ? $description : ''; ?>" />
    
    <?php if(isset($meta) && is_array($meta)) {
        foreach ($meta as $key => $value) {
            echo '<meta property="'.$key.'" content="'.$value.'" />'.PHP_EOL;
        }
    } ?>

    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" /> 
	<link rel="SHORTCUT ICON" href="/favicon.ico"/> 
    
    <link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Sans+Caption:400,700">
    <?php 
    // external resources
    if ( isset($ext_css) ) {
        // add assets
        foreach ($ext_css as $ext_asset) {
            echo '<link type="text/css" rel="stylesheet" href="' . $ext_asset . '" media="screen" />'.PHP_EOL;
        }
    } 
    // internal resources
    if ( isset($css) ) {
    	// add assets
		if(defined('DEBUG') && DEBUG == true) {
			foreach ($css as $asset) {
	    		echo '<link type="text/css" rel="stylesheet" href="/' . $asset . '" media="screen" />'.PHP_EOL;
			}
		} else {
			echo '<link type="text/css" rel="stylesheet" href="/min/c/' . base64_encode(implode(',', $css)) . '" media="screen" />';
		}
    } 
    ?>
    
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <?php
        // external resources
        if ( isset($ext_js) ) {
            // add assets
            foreach ($ext_js as $ext_asset) {
                echo '<script type="text/javascript" src="' . $ext_asset . '"></script>'.PHP_EOL;
            }
        }
        // internal resources
	    if ( isset($js) ) {
            // add assets
			if(defined('DEBUG') && DEBUG == true) {
				foreach ($js as $asset) {
		    		echo '<script type="text/javascript" src="/' . $asset . '"></script>'.PHP_EOL;
				}
			} else {
				echo '<script type="text/javascript" src="/min/j/' . base64_encode(implode(',', $js)) . '"></script>';
			}
	    }
    ?>
    <?php if( isset($stripe) ) { ?>
        <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <script type="text/javascript">
            Stripe.setPublishableKey('<?= STRIPE_KEY_PUBLIC ?>');
        </script>
    <?php } ?>

    <?php if (isset($js_vars)) { ?>
    <script type="text/javascript">
        <?php
            foreach ($js_vars as $key => $value) {
                echo 'var '.$key.'='.str_replace('\/', '/', json_encode($value)).';'.PHP_EOL;
            }
        ?>
    </script>
    <? } ?>

    <!--[if lt IE 9]>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js"></script>
    <![endif]--> 
    
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
    
    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "e0bd9da5-9c45-4ad2-b1a3-81ca7d809ede"}); </script>

</head>

<body id="top">

	<div id="headWrap">
		<div id="head">
			
			<div id="logo">Snapable. Every moment at your wedding in photos</div>
			
			<ul id="nav">
				<li><a href="signup" onClick="_gaq.push(['_trackEvent', 'Signups', 'Clicked', 'Signup (Header)']);">Sign-up</a></li>
				<li><a class="signin" href="/account/signin">Sign-in</a></li>
				<li><a href="http://blog.snapable.com/">Blog</a></li>
				<li><a href="#pricing" class="anchorLink">Pricing</a></li>
				<li><a href="#how-it-works" class="anchorLink">How It Works</a></li>
				<li><a href="#why-use" class="anchorLink">Why Snapable?</a></li>
			</ul>
			
		</div>
	</div>
	
	<div id="ctaWrap">
		<div id="cta">
			
			<a id="appstore" href="http://itunes.com/apps/snapable">Available on the App Store</a>
			
			<h1>Every Moment, Captured.</h1>
			<h2>The fun and easy way for your guests to share photos from your wedding.</h2>
			
			<div id="hand">Snapable Screenshot</div>
			
		</div>
	</div>
	
	<!-- LOWER PART OF CONTENT -->
	
	<div id="restOfPageWrap">
		
		<div class="sectionWrap">
			
			<a id="ctaBtn" href="/signup"><img src="/assets/home/img/jan2013/setup_event.png" width="409" height="85" border="0" alt="Setup Your Event" /></a>
			
			<section id="why-use">
		      <div class="inner">
		        <h2>Why Use Snapable?</h2>
		        
		        <h3>Letting your guests take photos during your wedding is a fun and easy way to get them involved. Best of all, the photos will be instantly added to your Snapable album, so you’ll have everything on one place.</h3>
		
		        <div class="box box-1">
			    	<div class="image"></div>
			    	<div class="text">
			        	<h3>A Complete Picture Of Your Wedding Day</h3>
			        	<p>One photographer can't catch all the action. Snapable helps collect each photo taken by friends and family, giving you a full picture of the day.</p>
			        </div>
		        </div>
		
		        <div class="box box-2">
		        	<div class="image"></div>
		        	<div class="text">
		        		<h3>Instantly Capture And Preserve Your Memories</h3>
		        		<p>Photos taken at your wedding end up everywhere. Avoid begging guests for photos and let Snapable instantly upload them into your online album.</p>
		        	</div>
		        </div>
		
		        <div class="box box-3">
		        	<div class="image"></div>
		        	<div class="text">
		        		<h3>Share Photos, Or Keep Them Private </h3>
		        		<p>Let’s face it, not every photo is meant for every pair of eyes. You can choose to share photos with some or all of your guests. Or just keep them private! </p>
		        	</div>
		        </div>
		
		        <div class="box box-4">
		        	<div class="image"></div>
		        	<div class="text">
		        		<h3>Instant Gratification</h3>
		        		<p>Don’t wait weeks for your photos. With Snapable, there is no waiting or sifting through Facebook. You’ll have them all—even before the honeymoon!</p>
		        	</div>
		        </div>
		
		      </div>
		    </section>
		   
		</div>
		<div class="sectionWrap">    
	
		    <section id="how-it-works">
		      <div class="inner">
		        <h2>How It Works</h2>
		
		        <div class="box">
		          <div class="overflow">
		            <div class="browser1"></div>
		          </div>
		          <h3>Before Your Wedding</h3>
		          <p>Create your wedding album in just a few easy steps. You’ll be able to:</p>
		          
		          <ul>
			          <li>Upload photos from your computer.</li>
			          <li>Add photos from the Snapable app. </li>
			          <li>Share the album with friends and family. </li>
		          </ul>

		          <p>Once your album is set up, you’re ready to collect your  photos!</p>
		          
		          <p class="pLink"><a href="http://snapable.com/event/demo" target="_blank">See a sample</a></p>
		        </div>
		
		        <div class="box">
		          <div class="overflow">
		            <div class="phone"></div>
		          </div>
		          <h3>During Your Wedding</h3>
		          <p>Remind guests to use Snapable with an elegant table card that includes:</p>

		          <ul>
		          	<li>How to download and use Snapable.</li>
		          	<li>A description of how it works.</li>
		          	<li>Your wedding album link. </li>
		          </ul>
		          <p>A copy of each photo taken with the Snapable app will be instantly added to your album.</p>
		          
		          <p class="pLink"><a href="/home/app" class="overlay">See a sample</a></p>
		        </div>
		
		        <div class="box">
		          <div class="overflow">
		            <div class="browser2"></div>
		          </div>
		          <h3>After Your Wedding</h3>
		          <p>Once your big day has passed, you’ll be able to peruse your album. You can:</p>

		          <ul>
		          	<li>Download your favorite photos.</li>
		          	<li>Share photos or the entire album with friends. </li>
		          	<li>Upload additional photos after the wedding. </li>
		          </ul>

			       <p>It’s that easy! All of your photos in one spot, ensuring you don’t miss a single moment. </p>
		          
		          <p class="pLink"><a href="/signup">Signup</a></p>
		        </div>
		
		      </div>
		    </section>
		    
		</div>
		<div class="sectionWrap">
	    
		    <section id="pricing">
		      <div class="inner">
		        <h2>Set up your event for just $79 and get started today.</h2>
		        
		        <div class="package-big-box-wrap">
		        	
		        	<div class="package-big-box">
		        	
			        	<h4>You get all this for your event:</h4>
			        	
			        	<ul>
			        		<li>Unlimited guests</li>
			        		<li>Unlimited photos </li>
			        		<li>Download your photos anytime</li>
			        		<li>Upload additional photos</li>
			        		<li>Unlimited guest email notifications</li>
			        		<li>Personalized downloadable instruction<br />cards for guests</li>
			        	</ul>
			        	
		        	</div>
		        	
		        	<div class="package-big-bottom">
		        	
		        		$79
		        		
		        		<a href="/signup"><img src="/assets/home/img/jan2013/setup_event-sm.png" width="300" height="62" border="0" alt="Setup Your Event" /></a>
		        		
		        	</div>
		        	
		        </div>
		        
		        <div class="package-share">
		        	
		        	<div id="package-always">
		        	
			        	<h4>Not getting married but know someone that is?</h4>

			        	<h5>Let them know about Snapable!</h5>
			        	
			        	<div class="package-share-links">
			        		<span class='st_facebook_large' displayText='Facebook'></span>
			        		<span class='st_twitter_large' displayText='Tweet' st_via='getsnapable' st_url="http://snapable.com" st_title="Check out Snapable, they're making it dead simple to capture every moment on your wedding day!"></span>
							<span class='st_email_large' displayText='Email' st_url="http://snapable.com" st_title="Check out Snapable, they're making it dead simple to capture every moment on your wedding day!"></span>
			        	</div>
			        	
		        	</div>
			        <div class="package-always-bottom">&nbsp;</div>
		        	
		        </div>
		        
		        <div class="clearit">&nbsp;</div>
		      </div>
		      
		      <div class="clearit">&nbsp;</div>
		    </section>

		</div>