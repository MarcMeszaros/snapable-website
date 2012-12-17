<h1>Contact</h1>

<form id="questionForm" action="/ajax/send_email" method="post">
    <input type="hidden" name="subject" value="Message From Website" />
    <h3>Got a question? We're happy to answer it</h3>
    <label id="contact-email-label">Your Email</label><br/>
    <input type="text" id="contact-email" name="from" maxlength="255" placeholder="Your email" value="" />
    <textarea class="message" name="message">Enter a question, comment or message...</textarea>
    <input type="submit" name="submit" value="Send" />
</form>