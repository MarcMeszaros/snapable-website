<script language="javascript">
var time_left = 5;
var cinterval;

function time_dec(){
  time_left--;
  document.getElementById('countdown').innerHTML = time_left;
  if(time_left == 0){
    clearInterval(cinterval);
    window.location.replace('/event/<?php echo $event_url; ?>');
  }
}

cinterval = setInterval('time_dec()', 1000);
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

<?php if(isset($url) && strlen($url) > 0) { ?>
<img src="<?php echo $url; ?>" width="1" height="1"> 
<?php } ?>
