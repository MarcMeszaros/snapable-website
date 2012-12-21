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

</head>

<body id="top">

	<div id="headWrap">
		<div id="head">
			
			<div id="logo">Snapable. Every moment at your wedding in photos</div>
			
			<ul id="nav">
				<li><a href="signup" onClick="_gaq.push(['_trackEvent', 'Signups', 'Clicked', 'Signup (Header)']);">Sign-up</a></li>
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
		        <h2>Why Should You Use Snapable?</h2>
		        
		        <h3>Letting your guests take photos during your wedding is a fun and easy way to get them involved. Best of all there's no need to beg for photos or sift through their Facebook pages to find them, you'll have them all in one place.</h3>
		
		        <div class="box box-1">
			    	<div class="image"></div>
			    	<div class="text">
			        	<h3>A Complete Picture Of Your Wedding Day</h3>
			        	<p>With all the excitement at your wedding one photographer can't catch all the action. Snapable captures every photo taken by your wedding guests, giving you a complete picture of the day.</p>
			        </div>
		        </div>
		
		        <div class="box box-2">
		        	<div class="image"></div>
		        	<div class="text">
		        		<h3>Instantly Capture And Preserve Your Memories</h3>
		        		<p>Photos taken at your wedding end up everywhere. Avoid begging guests for their photos and let Snapable instantly capture and preserve your wedding photos in one place. </p>
		        	</div>
		        </div>
		
		        <div class="box box-3">
		        	<div class="image"></div>
		        	<div class="text">
		        		<h3>Choose Which Photos To Print, Share, Or Keep Private.</h3>
		        		<p>Let’s face it, not every photo is meant for every pair of eyes. After your wedding, you can choose which photos to print, share, or keep private, giving you ultimate control. </p>
		        	</div>
		        </div>
		
		        <div class="box box-4">
		        	<div class="image"></div>
		        	<div class="text">
		        		<h3>Instant Gratification</h3>
		        		<p>Couples often wait up to 6 weeks for their photographs. With Snapable, you won't wait for your photos or have to sift through your friend’s Facebook. They’re all in one handy spot&mdash;Even before the honeymoon.</p>
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
		          <p>Create your wedding album in just a few easy steps. You can upload photos before the wedding and share the album with friends and family. That’s it, you’re done and are ready to capture each moment on your big day.</p>
		        </div>
		
		        <div class="box">
		          <div class="overflow">
		            <div class="phone"></div>
		          </div>
		          <h3>During Your Wedding</h3>
		          <p>Let guests know about Snapable with an elegant table card (provided by us!) with instructions on how to download and use the app. Then, a copy of any photo taken by friends and family will be instantly added to your album.</p>
		        </div>
		
		        <div class="box">
		          <div class="overflow">
		            <div class="browser2"></div>
		          </div>
		          <h3>After Your Wedding</h3>
		          <p>Decide which photos you would like to print, download, share or keep private. You can also upload an unlimited number of photos after your wedding, ensuring that all your wedding memories are in one spot!</p>
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
			        		<li>Personalized downloadable instruction cards for guests</li>
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
		
		<div id="footerWrap">
	    
	    		<section id="footer">
			    	&copy; <?= date("Y") ?> Snapable &nbsp; <a href="site/faq">FAQ</a>  / <a href="http://blog.snapable.com">Blog</a> / <a href="site/terms">Terms of Service</a> / <a href="site/privacy">Privacy</a>
			    	
			    	<div id="sm-links">
			    		<a id="sm-twitter" href="http://twitter.com/getsnapable" target="_blank">Follow us</a>
			    		<a id="sm-facebook" href="http://facebook.com/snapable" target="_blank">Like us</a>
			    	</div>
			    </section>

		</div>
		
	</div>

</body>
</html>