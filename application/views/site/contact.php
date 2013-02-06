<h1>Contact</h1>

<form id="questionForm" action="/ajax/send_email" method="post">
    <input type="hidden" name="subject" value="Message From Website" />
    <h3>Got a question? We're happy to answer it</h3>
    <label id="contact-email-label">Your Email</label><br/>
    <input type="text" id="contact-email" name="from" maxlength="255" placeholder="Your email" value="" />
    <textarea class="message" name="message">Enter a question, comment or message...</textarea>

    <!-- The following code is invisible to humans and contains some robot trap text fields. -->
    <div style="display: none">
    If you can read this, don't touch the following text fields.<br/>

    <input type="text" name="re-cap[address]" value="" /><br/>
    <input type="text" name="re-cap[contact]" value="" /><br/>
    <textarea cols="40" rows="6" name="re-cap[comment]"></textarea>
    </div>
    <!-- End spam bot trap -->
    <input type="submit" name="submit" value="Send" />
</form>