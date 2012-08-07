<script type="text/javascript">
var eventID = "<?= $eventDeets->resource_uri ?>";
var guestID = "/private_v1/guest/1/";
var typeID = "/private_v1/type/1/";
</script>

<div id="event-top">

	<div id="event-cover-wrap"><img id="event-cover-image" src="/assets/img/FPO/cover-image.jpg" /></div>
	<div id="event-title-wrap">
		<h1><?= $eventDeets->display_timedate ?></h1>
		<h2><?= $eventDeets->title ?></h2>
		<ul id="event-nav">
			<li><span>Photostream</span></li>
			<li><a id="uploadBTN" href="#">Upload Photos</a></li>
			<li><a href="#slideshow" id="slideshowBTN">Slideshow</a></li>
			<li><a href="#guest" id="guestBTN">Invite Guests</a></li>
			<li>
				<a id="event-nav-share" href="#">Share</a>
				<div id="event-nav-menu-share" class="event-nav-menu">
					<a class="photo-share-twitter" href="#">Tweet</a> 
					<a class="photo-share-facebook" href="#">Share</a> 
					<a class="photo-share-email" href="#">Email</a>
				</div>
			</li>
			<li>
				<a id="event-nav-privacy" href="#">Privacy</a>
				<div id="event-nav-menu-privacy" class="event-nav-menu">
					
					<p>Select the groups you'd like to be able to see photos that are uploaded.</p>
					
					<ul>
						<li><input type="checkbox" name="privacy-setting" value="1" /> Only You</li>
						<li><input type="checkbox" name="privacy-setting" value="2" /> Bride/Groom</li>
						<li><input type="checkbox" name="privacy-setting" value="3" /> Wedding party</li>
					</ul>
					<ul>
						<li><input type="checkbox" name="privacy-setting" value="4" /> Family</li>
						<li><input type="checkbox" name="privacy-setting" value="5" /> All Guests</li>
						<li><input type="checkbox" name="privacy-setting" value="6" CHECKED /> Public</li>
					</ul>
					<div class="clearit">&nbsp;</div>
					<input type="button" value="Save" />
				</div>
			</li>
		</ul>
	</div>
	
	<div id="checkout-buttons">
		<div id="in-cart">
			<div id="in-cart-number">0</div>
			Photos in cart
		</div>
		<a id="checkout" href="#">Checkout</a>
	</div>
	
</div>

<div id="uploadArea"></div>
<div id="uploadedArea"></div>
<div class="clearit">&nbsp;</div>

<div id="slideshow">
	<h3>Configure Slideshow</h3>
</div>

<div id="guest">
	<h3>Invite Guests</h3>
	
	<ul class="tabs">
		<li class="active"><a href="#add">Add</a></li><li><a href="#notify">Notify</a></li>
	</ul>
	
	<div id="addBox" class="tab-content">
		
		<p>Add guests and let them know to download the Snapable mobile app before your big day. <em>Include their type* to give more access to some guests.</em></p>
		<p><em><a name="guest-type"></a><strong>* Types include: </strong>Organizer, Bride/Groom, Wedding Party, Family, Guest</em></p>
		
		<div id="guests-choices">
		
			<a id="guest-link-upload" href="#">
		
				Upload from File			
			
			</a>
	
			<a id="guest-link-manual" href="#">
	
				<!--
				<textarea>Name, Email, Type</textarea>
				<input type="button" value="Add Guests" />
				* One guest per line
				-->
				
				Copy/Paste or Input Manually
				
			</a>
			
			<div class="clearit">&nbsp;</div>
			
		</div>
		
		<div id="guests-upload" class="guests-hide">
			
			<a class="guests-back-to-choices" href="#">‹ Back</a>
			
			<p><strong>Select a CSV</strong> file from your computer and <strong>click upload</strong>.</p>
			
			<form id="guests-file-uploader"><input type="file" /> <input id="guests-upload-csv" type="button" value="Upload" /></form>
			
			<p><em>The expected format is <strong>Name, Email, Type</strong></em></p>	
			
			<p>&rarr; <a href="#" id="guests-file-how-to-csv-link">How-to Save a CSV file</a> &nbsp; &nbsp; &rarr; <a href="#">Download a Sample CSV file</a></p>		
			
			<div id="guests-file-how-to-csv">
			
				<p><strong>Saving a CSV File from Microsoft Excel</strong></p>

				<p>Select <strong>Save As</strong>strong from the <strong>File</strong> menu.</p> 

				<p>In the window that opens, under the file browser, you'll see a drop down called <strong>Save as type</strong>, you can select CSV (Comma Delimited). Name your file, and click <strong>Save.</strong></p>

				<p><em>You will usually get a warning from Excel telling you that a CSV file does not support multiple files types. You can ignore this and simply select <strong>Ok</strong>.</em></p>

			</div>
			
		</div>
		
		<div id="guests-manual" class="guests-hide">
			
			<a class="guests-back-to-choices" href="#">‹ Back</a>
			
			Copy/paste (or type) your guests below then click "Done".
			<br /><em>The expected format is <strong>Name, Email, Type</strong> (one per line)</em>
			
			<textarea></textarea>
			
			<a class="buttonBox" href="#">Done</a>
			
		</div>
		
	</div>
	
	<div id="notifyBox" class="tab-content">
		
		<p>Let your guests know to download Snapable in advance.</p>
		
		<div id="notify-group">
		
			<p><strong>Notify:</strong></p>
			
			<p>
				<input type="checkbox" name="privacy-setting" value="2" /> Bride/Groom
				<br /><input type="checkbox" name="privacy-setting" value="3" /> Wedding party
				<br /><input type="checkbox" name="privacy-setting" value="4" /> Family
				<br /><input type="checkbox" name="privacy-setting" value="5" CHECKED /> All Guests
			</p>
		</div>		
		<div id="notify-message">
			<p>ADD EMAIL TEMPLATE TO SHOW FULL MESSAGE</p>
			
			<textarea>Message</textarea>
		</div>
		<div class="clearit">&nbsp;</div>
		
	</div>
</div>

<div id="photoArea">

	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-1.jpg" />
		<img class="photo-comment" title="Uncle Bob dancing up a storm on the dance floor." src="/assets/img/icons/comment.png" /> Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-2.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-3.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-4.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-5.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-6.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-7.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-8.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-1.jpg" />
		<img class="photo-comment" title="Uncle Bob dancing up a storm on the dance floor." src="/assets/img/icons/comment.png" /> Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-2.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-3.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-4.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-5.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-6.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-7.jpg" />
		Andrew D.	
	</div>
	<div class="photo">
		<div class="photo-overlay">
			<ul class="photo-share">
				<li><a class="photo-share-twitter" href="#">Tweet</a></li>
				<li><a class="photo-share-facebook" href="#">Share</a></li>
				<li><a class="photo-share-email" href="#">Email</a></li>
			</ul>
			<div class="photo-buttons">
				<a class="button addto-prints" href="#">Add to Prints</a>
			</div>
			<a class="photo-enlarge" href="/p/123" title="Enlarge">Enlarge</a>
			<!--<a class="photo-download" href="#" title="Download">Download</a>-->
		</div>
		<img src="/assets/img/FPO/event-photo-8.jpg" />
		Andrew D.	
	</div>

</div>

<div class="clearit">&nbsp;</div>