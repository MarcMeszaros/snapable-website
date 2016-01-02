	<script>
        window.StatusPage.getStatus({
            success : function(data) {
                if (data.status.indicator != 'none') {
                    $('#service-status').addClass('glyphicon-exclamation-sign');
                    // add the correct color
                    switch (data.status.indicator) {
                        case 'minor':
                            $('#service-status').css('color', 'goldenrod');
                            break;

                        case 'major':
                            $('#service-status').css('color', 'darkorange');
                            break;

                        case 'critical':
                            $('#service-status').css('color', 'red');
                            break;

                        default:
                            $('#service-status').css('color', 'red');
                            break;
                    }
                }
            }
        });
    </script>

		<section id="footer" class="col-lg-12">
		    <div class="col-lg-9">
		        <ul>
		            <li>&copy; <?= date("Y") ?> Snapable |</li>
		            <li><a href="http://status.snapable.com">Status</a></li>
		            <li><a href="http://blog.snapable.com">Blog</a></li>
		            <li><a href="http://snapable.com/site/contact">Contact</a></li>
		            <li><a href="/site/privacy/">Privacy</a></li>
		            <li><a href="/site/terms/">Terms</a></li>
		            <li><a href="/site/faq/">FAQ</a></li>
		        </ul>
		    </div>
		    <div class="col-lg-3">
		        <ul class="sm-links">
		            <li><a class="twitter" href="http://twitter.com/getsnapable" target="_blank">Follow us</a></li>
		            <li><a class="facebook" href="http://facebook.com/snapable" target="_blank">Like us</a></li>
		            <li><a class="pinterest" href="http://pinterest.com/snapable" target="_blank">Follow us</a></li>
		        </ul>
		    </div>
		</section>

</div><!-- /container -->
