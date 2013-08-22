<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <h2>Metrics</h2>
        </div>
        <div id="metrics-range" class="col-lg-3">
            <form>
                <span id="metrics-refresh">[Refresh]</span> |
                <input type="radio" id="metrics-range-7d" name="metrics-range" value="7" checked />
                <label for="metrics-range-7d">7d</label> |
                <input type="radio" id="metrics-range-14d" name="metrics-range" value="14" />
                <label for="metrics-range-14d">14d</label> |
                <input type="radio"  id="metrics-range-1m" name="metrics-range" value="30" />
                <label for="metrics-range-1m">1m</label> |
                <input type="radio" id="metrics-range-3m" name="metrics-range" value="90" />
                <label for="metrics-range-3m">3m</label> |
                <input type="radio" id="metrics-range-all" name="metrics-range" value="-1" />
                <label for="metrics-range-all">All</label>
            </form>
        </div>
    </div>

    <div id="metrics" class="row">
        <div class="col-lg-3">
            <div id="metric-signups" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Total Signups</h3>
                    User signup count.
                </div>
                <div class="panel-body">
                    <span class="value"></span><span class="spinner-wrap status hide"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div id="metric-past-events-count" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Past Events</h3>
                    Events ending in range.
                </div>
                <div class="panel-body">
                    <span class="value"></span><span class="spinner-wrap status hide"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div id="metric-past-events-photos" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Past Events with Photos</h3>
                    Events with at least 1 photo.
                </div>
                <div class="panel-body">
                    <span class="value"></span><span class="spinner-wrap status hide"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div id="metric-upcoming-events" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Upcoming Events</h3>
                    ...and current events.
                </div>
                <div class="panel-body">
                    <span class="value"></span><span class="spinner-wrap status hide"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div id="metric-photos-count" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Photo Count</h3>
                    Photos taken/uploaded.
                </div>
                <div class="panel-body">
                    <span class="value"></span><span class="spinner-wrap status hide"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div id="metric-avg-event-photos" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Avg. photos/event</h3>
                    Average photos per event.
                </div>
                <div class="panel-body">
                    <span class="value"></span><span class="spinner-wrap status hide"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div id="metric-revenue" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Revenue</h3>
                    Gross | Net revenue from sales.
                </div>
                <div class="panel-body">
                    <span class="value"></span><span class="spinner-wrap status hide"></span>
                </div>
            </div>
        </div>
    </div>

    <div id="edit" class="row">
        <div class="col-lg-12">
            <h3>Manage</h3>
            <p>When refunding an account (do that in Stripe), delete the event first, then the user.</p>
        </div>
        <div class="col-lg-4">
            <form id="delete-event-form" method="post" action="/ajax_internal/delete_event">
                <label for="delete-event">Delete event:</label>
                <input type="number" id="delete-event" name="event_id" placeholder="Event ID" />
                <input type="submit" value="Delete" onclick="return sendForm(this, deleteCallback);" />
            </form>
        </div>
        <div class="col-lg-4">
            <form id="delete-user-form" method="post" action="/ajax_internal/delete_user">
                <label for="delete-user">Delete user:</label>
                <input type="number" id="delete-user" name="user_id" placeholder="User ID" />
                <input type="submit" value="Delete" onclick="return sendForm(this, deleteCallback);" />
            </form>
        </div>
        <div class="col-lg-4">
            <form id="delete-photo-form" method="post" action="/ajax_internal/delete_photo">
                <label for="delete-photo">Delete photo:</label>
                <input type="number" id="delete-photo" name="photo_id" placeholder="Photo ID" />
                <input type="submit" value="Delete" onclick="return sendForm(this, deleteCallback);" />
            </form>
        </div>
    </div>
    
    <div id="events" class="row">
        <div class="col-lg-12">
            <h2>Events</h2>
            <p>Next 50 events:</p>
            <table class="table table-hover">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Public</th>
                    <th>PIN</th>
                    <th>Start (UTC)</th>
                    <th>End (UTC)</th>
                    <th>Photos</th>
                </tr>
                <?php
                    foreach ($events as $event) {
                        $eventID = explode('/', $event['resource_uri']);
                        $public = ($event['public']) ? 'True': 'False';

                        echo "\t".'<tr>'.PHP_EOL;
                        echo "\t\t".'<td>'.$eventID[3].'</td>'.PHP_EOL;
                        echo "\t\t".'<td>'.$event['title'].'</td>'.PHP_EOL;
                        echo "\t\t".'<td><a target="_blank" href="/event/'.$event['url'].'">'.$event['url'].'</a></td>'.PHP_EOL;
                        echo "\t\t".'<td>'.$public.'</td>'.PHP_EOL;
                        echo "\t\t".'<td>'.$event['pin'].'</td>'.PHP_EOL;
                        echo "\t\t".'<td>'.str_replace('T', ' ', substr($event['start'], 0, -9)).'</td>'.PHP_EOL;
                        echo "\t\t".'<td>'.str_replace('T',' ', substr($event['end'], 0, -9)).'</td>'.PHP_EOL;
                        echo "\t\t".'<td>'.$event['photo_count'].'</td>'.PHP_EOL;
                        echo "\t".'</tr>'.PHP_EOL;
                    }
                ?>
            </table>
        </div>
    </div>
</div>