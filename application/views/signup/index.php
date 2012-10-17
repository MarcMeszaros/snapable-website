
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title><?php if ( isset($title) ) { echo $title; } else { echo "Sign up for Snapable - The easiest way to instantly capture every photo at your wedding without missing a single moment."; } ?></title>
    
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

	<div id="restOfPageWrap">
		
		<div id="topOthaPage">
			<img src="/assets/img/snapable-sm.png" width="200" height="54" alt="Snapable" border="0" />
			<div>Every Moment, Captured.</div>
		</div>
		
		<form name="create_event" method="post" action="/signup/complete">
		
			<input type="hidden" id="lat" name="event[lat]" value="0" />
			<input type="hidden" id="lng" name="event[lng]" value="0" />
			<input type="hidden" id="timezone" name="event[timezone]" value="0" />
			
			<section id="your-details" class="form-fields">
		
				<h1>Sign-up</h1>
				<h2>It’ll only take a minute to setup your event.</h2>
			
				<h3>Your Details</h3>
			
				<div class="form-box">
					
					
					<div class="form-field field-separated">
						<label for="user_first_name">First name</label>
						<input id="user_first_name" name="user[first_name]" size="30" type="text">
						<div class="field-error" id="user_first_name_error">You must provide a first name.</div>
					</div>
		    
					<div class="form-field">
						<label for="user_last_name">Last name</label>
						<input id="user_last_name" name="user[last_name]" size="30" type="text">
						<div class="field-error" id="user_last_name_error">You must provide a last name.</div>
					</div>
		  
					<div class="form-field field-separated">
						<label for="user_email">Email address <em>(you'll use this to sign in)</em></label>
						<span id="email_status">&nbsp;</span>
						<input id="user_email" name="user[email]" size="40" type="text">
						<div class="field-error" id="user_email_error">You must provide a properly formatted email address.</div>
					</div>
		  
					<hr />
		  
					<div class="password-field field-separated">
						<label for="user_password">Password<br /><em>(6 characters or longer)</em></label>
						<input id="user_password" name="user[password]" size="30" type="password">
						<div class="field-error" id="user_password_error">Error.</div>
					</div>
		      
					<div class="password-field">
						<label for="user_password_confirmation">Enter password again<br /><em>(for confirmation)</em></label>
						<input id="user_password_confirmation" name="user[password_confirmation]" size="30" type="password">
						<div class="field-error" id="user_password_confirmation_error">Error.</div>
					</div>
		    
					<div class="clearit">&nbsp;</div>
					
				</div>
			
			</section>
			
			<div class="clearit">&nbsp;</div>
			
			<section id="event-details" class="form-fields">
			
				<hr class="thick" />
				
				<h3>Event Details</h3>
				
				<div class="form-box">
					
					<div class="form-field field-separated">
						<label for="event_title">Title</label>
						<input id="event_title" name="event[title]" size="40" type="text"> 
						<div class="field-error" id="event_title_error">You must provide a title for your event.</div>
					</div>
					
					<!--
					<div class="form-field field-separated">
						<label for="event_date">Date</label>
						<input id="event_date" class="long-field" name="event[date]" size="40" type="text" value="Make date/time picker for start and end of event"> 
						<div class="field-error" id="event_date_error">You must specify a date and time for your event.</div>
					</div>
					-->
					
					<div class="small-field field-separated">
						<div class="small-field-inner">
							<label for="event-start-date">Start Date</label>
							<input class="longer" id="event-start-date" name="event[start_date]" type="text" value="<?= date("M j, Y", time()) ?>">
						</div>
						<div class="small-field-inner">
							<label for="event-start-time">Time</label>
							<input id="event-start-time" name="event[start_time]" type="text" value="<?= date("h:00 A", time() + 3600) ?>">
						</div>
						
						<div class="small-field-inner">
							<label for="event-start-date">End Date</label>
							<input class="longer" id="event-end-date" name="event[end_date]" type="text" value="<?= date("M j, Y", time()) ?>">
						</div>
						<div class="small-field-inner">
							<label for="event-end-time">Time</label>
							<input id="event-end-time" name="event[end_time]" type="text" value="<?= date("h:00 A", time() + 18000) ?>">
						</div>
					</div>
					
					<div class="form-field">
						<label for="event_location">Location</label>
						<span id="event_location_status">&nbsp;</span>
						<input id="event_location" name="event[location]" size="40" type="text"> 
						<div class="field-error" id="event_location_error">You must provide a location for your event.</div>
						<div class="form-field_hint">Example: 255 Bremner Blvd, Toronto, Canada, M5V 3M9</div>
					</div>
					
					<hr />
					
					<div class="form-field">
						<label for="event_url">Pick a custom URL</label>
						<span class="info left">snapable.com/event/</span><input id="event_url" name="event[url]" type="text" />
						<span id="event_url_status">&nbsp;</span>
						<div class="clearit">&nbsp;</div>
						<div class="field-error" id="event_url_error">This URL is already in use.</div>
						<div class="form-field_hint">Example: http://snapable.com/event/<b>my-big-fat-greek-wedding</div>
					</div>
					<div class="clearit">&nbsp;</div>
				</div>
				
			</section>
			
			<div class="clearit">&nbsp;</div>
			
			<a href="#" id="btn-sign-up"><img src="/assets/img/complete.png" width="250" height="75" alt="Complete Purchase" border="0" /></a>
					
		</form>		
		
		<section id="footer">
	    	&copy; 2012 Snapable
	    	
	    	<div id="sm-links">
	    		<a id="sm-twitter" href="http://twitter.com/getsnapable" target="_blank">Follow us</a>
	    		<a id="sm-facebook" href="http://facebook.com/snapable" target="_blank">Like us</a>
	    	</div>
	    </section>
	    
	</div>

</body>
</html>