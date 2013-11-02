<div class="col-lg-7">
    <h1>Contact</h1>

    <form id="questionForm" role="form" method="POST" action="/ajax/send_email" data-validate="parsley">
        <input type="hidden" name="subject" value="Message From Website" />
        <fieldset>
            <h3>Got a question? We're happy to answer it.</h3>
            <div class="form-group">
                <label id="contact-email-label">Your Email</label><br/>
                <input type="email" id="contact-email" class="form-control" name="from" data-required="true" data-notblank="true" data-error-message="You must provide your email." placeholder="Your email" />
            </div>
            <div class="form-group">
                <label id="contact-message-label">Message</label><br/>
                <textarea class="form-control message" name="message" rows="6" data-required="true" data-notblank="true" data-error-message="You must provide a message." placeholder="Enter a question, comment or message..."></textarea>
            </div>

            <!-- The following code is invisible to humans and contains some robot trap text fields. -->
            <div style="display: none">
            If you can read this, don't touch the following text fields.<br/>

            <input type="text" name="re-cap[address]" value="" /><br/>
            <input type="text" name="re-cap[contact]" value="" /><br/>
            <textarea cols="40" rows="6" name="re-cap[comment]"></textarea>
            </div>
            <!-- End spam bot trap -->
            <button type="submit" class="btn btn-primary" onclick="return sendForm(this, questionSuccess, questionError);">Send</button>
        </fieldset>
    </form>
</div>