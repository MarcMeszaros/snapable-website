
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title>Snapable - The easiest way to instantly capture every photo at your wedding without missing a single moment.</title>
    
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
    
</head>

<body id="top">
	
	<div id="eventCountdown"><?= $days_until ?> Days <?= $days_verb ?> Your Event.</div>

	<div id='signedInBar'><div id='signedInText'>Signed In as <strong><?= $session['fname'] . " " . substr($session['lname'], 0, 1) . "." ?></strong> / <a href='/account/signout'>Sign Out</a></div></div>
	
	<div id="wrap">
		
		<div class="left">
			
			<div id="logo">Snapable</div>
			
		</div>
		
		<div class="right">
			
			<h1>You're signed up, what now?</h1>
			
			<h2>Here's a few things you can do to get the most out of Snapable and help make your wedding day photos a success.</h2>
		
		</div>
		
		<div class="clearit">&nbsp;</div>
		
		<div class="left">
		
			<ul>
				<li><a href="/event/<?= $eventDeets['url'] ?>">Your Event</a></li>
				<li><a href="#before" class="active">Before</a></li>
				<li><a href="#during">During</a></li>
				<li><a href="#after">After</a></li>
				<li><a id="questions-link" href="#questions">Questions?</a></li>
				<li><a href="/account/signout">Sign out</a></li>
			</ul>
			
			<dl>
				<dt>Connect:</dt>
				<dd><a href="http://twitter.com/getsnapable" target="_blank" id="twitter">Twitter</a></dd>
				<dd><a href="http://facebook.com/snapable" target="_blank" id="facebook">Facebook</a></dd>
				<dd><a href="#" id="email">Email</a></dd>
			</dl>
			
		</div>
		
		<div class="right">
			
			<div id="before" class="content showing">
			
				<h3><a href="#share" class="opened">Share</a></h3>
			
				<div id="share-content" class="showing">
				
					<p>The URL for <strong><?= $eventDeets['title'] ?></strong> is:</p>
				
					<p><input id="event-url" type="text" value="http://snapable.com/event/<?= $eventDeets['url'] ?>" READONLY /></p>
					
					<h2>If you set your event to private your guests will require this Event PIN to take photos:</h2>
					
					<div id="event-pin"><?= $eventDeets['pin'] ?></div>

					<p>Share it!</p>
					
					<ul id="shareLinks">
						<li><a class="facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=http://snapable.com/event/<?= $eventDeets['url'] ?>">Facebook</a></li>
						<li><a class="twitter" target="_blank" href="http://twitter.com/share?text=<?= urlencode("Follow the photos on " . date("D M j", $eventDeets['start_epoch']) . " at " .  date("g:i a", $eventDeets['start_epoch']) . " for " . $eventDeets['title'] . " with @getsnapable") ?>&url=http://snapable.com/event/<?= $eventDeets['url'] ?>">Twitter</a></li>
						<li><a class="email" href="#">Email</a></li>
					</ul>
					
					<form id="shareViaEmail" name="questions">
			
						<label for="to">To</label>
						<input type="text" name="to" value="" />
						
						<label for="from">From</label>
						<input id="shareViaFrom" type="text" READONLY name="from" value="<?= $session['email'] ?>" />
				
						<label for="messageBody">Message</label>
						<textarea name="messageBody"><?= "Follow the photos at http://snapable.com/event/" . $eventDeets['url'] . " on " . date("D M j", $eventDeets['start_epoch']) . " at " .  date("g:i a", $eventDeets['start_epoch']) . " for " . $eventDeets['title'] . " with http://snapable.com" ?></textarea>
					
						<input type="submit" name="submit" value="Send" />
					
				</form>
					
					<p>Or share it wherever you want people to know where they can find photos from your wedding.</p>
			
				</div>
			
				<h3><a href="#guest" class="closed">Add and notify guests</a></h3>
			
				<div id="guest-content" class="hiding">
				
					<p>Go to your event page, add your guest list, set their roles* and let them know to get Snapable and share their photos.</p>

					<p><em>* Roles will allow you to set who gets to see certain photos (in case you don’t want Aunt Ida seeing that photos of Uncle Jed dancing with the bridesmaids)</em></p>
				
				</div>
			
				<h3><a href="#upload" class="closed">Upload photos</a></h3>
			
				<div id="upload-content" class="hiding">
				
					<p>Go to your event page, click the "Upload Photos" link under the title, grab the photos you want to upload, drag them into the upload box, and click the ‘upload’ button.</p>

					<p>Use this to upload photos of the bride/groom as kids, seed a slideshow, etc.</p>
				
				</div>
			
				
			
			</div>
			
			<div id="during" class="content hiding">
			
				<h3><a href="#participate" class="opened">Encourage guests to participate</a></h3>
				
				<div id="participate-content" class="showing">
				
					<p>Let guests know how to take part via customized table cards. <a href="/event/setup/<?= $eventDeets['url'] ?>/cards" id="get-table-card" class="button" rel="facebox">Get&nbsp;Table&nbsp;Cards &rarr;</a></p>
				
				</div>
				
				<h3><a href="#slideshow" class="closed">Setup a slideshow</a></h3>
				
				<div id="slideshow-content" class="hiding">
				
					<p>To create a slideshow <a href="/event/<?= $eventDeets['url'] ?>">go to your event page</a>, click the slideshow link and follow the instructions.</p>
				
				</div>
			
			</div>
			
			<div id="after" class="content hiding">
			
				<h3><a href="#create" class="opened">Create photo albums</a></h3>
				
				<div id="create-content" class="showing">
				
					<p>To create photo albums, first upload photos, then go to your <a href="/event/<?= $eventDeets['url'] ?>">event page</a>, select the photo(s) you want to add to an album, then select the album (or create a new one) you'd like the photo to be a part of.</p>
				
				</div>
				
				<h3><a href="#get" class="closed">Get prints</a></h3>
				
				<div id="get-content" class="hiding">
				
					<p>To order print, first go to your <a href="/event/<?= $eventDeets['url'] ?>">event page</a>, then select the photo(s) you want to have printed. Once you've picked all that you want click the orange "Checkout" button on the top right of the screen and follow the instructions.</p>
				
				</div>
				
				<h3><a href="#remind" class="closed">Remind your guests</a></h3>
				
				<div id="remind-content" class="hiding">
				
					<p>Let your guests know to come view the photos (and allow them to order prints too!). <a href="/event/setup/<?= $eventDeets['url'] ?>/reminders" id="send-reminders" class="button" rel="facebox">Send Reminders &rarr;</a></p>
				
				</div>
			
			</div>
			
			<div id="questions" class="content hiding">
				
				<form id="questionForm" name="questions" action="/account/email" method="post">
			
					<h3>Got a question? We're happy to answer it</h3>
				
					<textarea name="message">Enter a question, comment or message...</textarea>
					
					<input type="hidden" name="email" value="<?= $session['email'] ?>" />
					
					<input type="submit" name="submit" value="Send" />
					
				</form>
				
			</div>
			
			<div class="clearit">&nbsp;</div>
			<a id="event-link" href="/event/<?= $eventDeets['url'] ?>">Go to your event page</a>
			
		</div>
		
	</div>

</body>
</html>