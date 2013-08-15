<div class="col-lg-5">
    <h1>Contact</h1>

    <form id="questionForm" class="form" action="/ajax/send_email" method="post">
        <input type="hidden" name="subject" value="Message From Website" />
        <fieldset>
            <h3>Got a question? We're happy to answer it</h3>
            <div class="form-group">
                <label id="contact-email-label">Your Email</label><br/>
                <input type="email" id="contact-email" class="form-control" name="from" placeholder="Your email" />
            </div>
            <div class="form-group">
                <textarea class="form-control message" name="message">Enter a question, comment or message...</textarea>
            </div>

            <!-- The following code is invisible to humans and contains some robot trap text fields. -->
            <div style="display: none">
            If you can read this, don't touch the following text fields.<br/>

            <input type="text" name="re-cap[address]" value="" /><br/>
            <input type="text" name="re-cap[contact]" value="" /><br/>
            <textarea cols="40" rows="6" name="re-cap[comment]"></textarea>
            </div>
            <!-- End spam bot trap -->
            <input type="submit" class="btn btn-primary" name="submit" value="Send" />
        </fieldset>
    </form>
</div>