<script type="text/javascript">
var time_left = 5;
var cinterval;

function time_dec(){
  time_left--;
  document.getElementById('countdown').innerHTML = time_left;
  if(time_left == 0){
    clearInterval(cinterval);
    window.location.replace('/event/<?= $event_url ?>');
  }
}

// set the countdown timer
cinterval = setInterval('time_dec()', 1000);

$(document).ready(function() {
  <?php if(!empty($order_id)) { ?>
  ga('ecommerce:addTransaction', {
    'id': '<?= $order_id ?>',
    'revenue': '<?= $amount_total ?>'
  });
  ga('ecommerce:send');
  <?php } ?>
  _gaq.push(['_trackPageview', 'signup/submit']);
});
</script>

<div class="logo"></div>
<h1>
    Your Snapable event has been successfuly created.
</h1>
<h1>
    You will now be redirected to your event page.
</h1>

<br>
<h2><a href="/event/<?php echo $event_url; ?>">Continue to Your Event</a></h2>

<br>
<h2>
    Redirecting In <span id="countdown">5</span>.
</h2>

<?php if(!empty($url)) { ?>
<img src="<?= $url ?>" width="1" height="1">
<?php } ?>
