<div class="container">
    <div id="metrics" class="col-12">
        <div class="row">
            <div class="col-9">
                <h2>Metrics</h2>
            </div>
            <div id="metrics-range" class="col-3">

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
        <div id="metrics-panels" class="col-12">
            <div id="metric-signups" class="col-2 panel">
                <div class="title">Total Signups</div>
                <div class="description">User signup count.</div>
                <div class="value-container">
                    <div class="value"></div><span class="spinner-wrap status hide"></span>
                </div>
            </div>
            <div id="metric-past-events-count" class="col-2 panel">
                <div class="title">Past Events</div>
                <div class="description">Events ending in range.</div>
                <div class="value-container">
                    <div class="value"></div><span class="spinner-wrap status hide"></span>
                </div>
            </div>
            <div id="metric-past-events-photos" class="col-2 panel">
                <div class="title">Past Events with Photos</div>
                <div class="description">Events with at least 1 photo.</div>
                <div class="value-container">
                    <div class="value"></div><span class="spinner-wrap status hide"></span>
                </div>
            </div>
            <div id="metric-upcoming-events" class="col-2 panel">
                <div class="title">Upcoming Events</div>
                <div class="description">...and current events.</div>
                <div class="value-container">
                    <div class="value"></div><span class="spinner-wrap status hide"></span>
                </div>
            </div>
            <div id="metric-photos-count" class="col-2 panel">
                <div class="title">Photo Count</div>
                <div class="description">Photos taken/uploaded.</div>
                <div class="value-container">
                    <div class="value"></div><span class="spinner-wrap status hide"></span>
                </div>
            </div>
            <div id="metric-avg-event-photos" class="col-2 panel">
                <div class="title">Avg. photos/event</div>
                <div class="description">Average photos per event.</div>
                <div class="value-container">
                    <div class="value"></div><span class="spinner-wrap status hide"></span>
                </div>
            </div>
            <div id="metric-revenue" class="col-2 panel">
                <div class="title">Revenue</div>
                <div class="description">Gross | Net revenue from sales.</div>
                <div class="value-container">
                    <div class="value"></div><span class="spinner-wrap status hide"></span>
                </div>
            </div>
        </div>
    </div>

    <div id="edit" class="col-12">
        <h3>Manage</h3>
        <p>When refunding an account (do that in Stripe), delete the event first, then the user.</p>
        <div class="row">
            <div class="col-4">
                <form id="delete-event-form" method="post" action="/ajax_internal/delete_event">
                    <label for="delete-event">Delete event:</label>
                    <input type="number" id="delete-event" name="event_id" placeholder="Event ID" />
                    <input type="submit" value="Delete" onclick="return sendForm(this, deleteCallback);" />
                </form>
            </div>
            <div class="col-4">
                <form id="delete-user-form" method="post" action="/ajax_internal/delete_user">
                    <label for="delete-user">Delete user:</label>
                    <input type="number" id="delete-user" name="user_id" placeholder="User ID" />
                    <input type="submit" value="Delete" onclick="return sendForm(this, deleteCallback);" />
                </form>
            </div>
            <div class="col-4">
                <form id="delete-photo-form" method="post" action="/ajax_internal/delete_photo">
                    <label for="delete-photo">Delete photo:</label>
                    <input type="number" id="delete-photo" name="photo_id" placeholder="Photo ID" />
                    <input type="submit" value="Delete" onclick="return sendForm(this, deleteCallback);" />
                </form>
            </div>
        </div>
    </div>
    
    <div id="events" class="col-12">
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