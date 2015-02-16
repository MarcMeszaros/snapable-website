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
    <div class="col-lg-10 col-lg-push-1" style="padding-left:0;">
	<section id="footer">
    	&copy; <?= date("Y") ?> Snapable | <a href="/site/contact">Contact</a> | <a href="/site/faq">FAQ</a> | <a href="http://blog.snapable.com">Blog</a> | <a href="/site/terms">Terms</a> | <a href="/site/privacy">Privacy</a> | <a href="http://status.snapable.com">Status <span id="service-status" class="glyphicon"></span></a>
    	
    	<div id="sm-links">
    		<a id="sm-twitter" href="http://twitter.com/getsnapable" target="_blank">Follow us</a>
    		<a id="sm-facebook" href="http://facebook.com/snapable" target="_blank">Like us</a>
    		<a id="sm-pinterest" href="http://pinterest.com/snapable" target="_blank">Follow us</a>
    	</div>
    </section>
	</div>