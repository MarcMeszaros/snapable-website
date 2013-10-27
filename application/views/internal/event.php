<div class="container">
    <div class="col-lg-12">
        <h2>Create Event</h2>
        <form id="eventForm" class="form-horizontal" action="/internal/ajax_create_event" method="post" data-validate="parsley" novalidate>
            <fieldset>
                <!-- some required data magically figured out via AJAXy stuff -->
                <input type="hidden" id="lat" name="event[lat]" value="0" />
                <input type="hidden" id="lng" name="event[lng]" value="0" />
                <input type="hidden" id="timezone" name="event[tz_offset]" value="0" />

                <div class="form-group">
                    <label for="account_id">Account ID</label>
                    <input id="account_id" class="form-control" name="event[account_id]" type="number" data-required="true" data-notblank="true" data-error-message="You must provide an account id." /> 
                    <span class="help-block">Make sure this is correct!</span>
                </div>

                <div class="form-group">
                    <label for="event_title">Title</label>
                    <input id="event_title" class="form-control" name="event[title]" type="text" data-required="true" data-notblank="true" data-error-message="You must provide a title for your event." /> 
                </div>

                <div class="form-group row">
                    <div class="form-group col-sm-4">
                        <label for="event-start-date">Date</label>
                        <input id="event-start-date" class="form-control" name="event[date]" type="date" />
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="event-start-time">Time</label>
                        <input id="event-start-time" class="form-control" name="event[time]" type="time" />
                    </div>
                    <div class="form-group col-sm-5">
                        <label for="event-duration-num">Duration</label>
                        <div class="form-inline">
                            <select id="event-duration-num" class="form-control" name="event[duration_num]" style="width:49%;">
                            <?php
                            for ($i=1; $i<=23; $i++) {
                                if ( $i == 12 ) {
                                    $selected = " SELECTED";
                                } else {
                                    $selected = "";
                                }
                                echo "<option value='" . $i . "'" . $selected . ">" . $i . "</option>";
                            }
                            ?>
                            </select>
                            <select id="event-duration-type" class="form-control" name="event[duration_type]" style="width:49%;">
                                <option value="hours">Hours</option>
                                <option value="days">Days</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="event_url">Choose a unique event URL</label>
                    <div class="form-inline">
                        <span id="event_url-start">snapable.com/event/</span><input id="event_url" class="form-control" name="event[url]" type="text" data-required="true" data-notblank="true" />
                        <span id="event_url_status"></span>
                    </div>
                    <span class="help-block">Example: https://snapable.com/event/<b>my-big-fat-greek-wedding</b></span>
                </div>

                <div class="form-group">
                    <label for="event_location">Location</label>
                    <div class="form-inline">
                        <input id="event_location" class="form-control" name="event[location]" type="text" data-required="true" data-notblank="true" data-error-message="You must provide a location for your event." /> 
                        <span id="event_location_status"></span>
                    </div>
                    <span class="help-block">Example: 255 Bremner Blvd, Toronto, Canada, M5V 3M9</span>
                </div>

                <div id="map_canvas_container" class="form-group" style="display:none;">
                    <div id="map_canvas" style="width: 500px; height: 400px;"></div>
                    <p style="width:350px; margin-top:10px;">Here's where we've got your event, if we're wrong you can drag the location marker to the correct location.</p>
                </div>

                <div>
                    <button type="submit" id="completSignup" class="btn btn-default">Setup Event</button>
                    <span id="signup-spinner" class="spinner-wrap hide" data-length="8" data-radius="5" data-width="4"></span>
                </div>
            </fieldset>
        </form>
    </div>
</div>