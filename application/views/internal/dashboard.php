<div class="container">
    <div id="metrics" class="span12">
        <div class="row">
            <div class="span9">
                <h2>Metrics</h2>
            </div>
            <div id="metrics-range" class="span3">
                <form>
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
        <div id="metrics-panels" class="span12">
            <div id="metric-signups" class="panel">
                <div class="title">Total Signups</div>
                <div class="description">User signup count.</div>
                <div class="value-container">
                    <div class="value"></div><span class="status hide">&nbsp;</span>
                </div>
            </div>
            <!--
            <div id="metric-uniques" class="panel">
                <div class="title">Uniques</div>
                <div class="description">TBD</div>
                <div class="value-container">
                    <div class="value">N/A</div><span class="status hide">&nbsp;</span>
                </div>
            </div>
            <div id="metric-signup-conversion" class="panel">
                <div class="title">Signup Conversion</div>
                <div class="description">TBD</div>
                <div class="value-container">
                    <div class="value">N/A</div><span class="status hide">&nbsp;</span>
                </div>
            </div>
            -->
            <div id="metric-past-events-count" class="panel">
                <div class="title">Past Events</div>
                <div class="description">Events ending in range.</div>
                <div class="value-container">
                    <div class="value"></div><span class="status hide">&nbsp;</span>
                </div>
            </div>
            <div id="metric-past-events-photos" class="panel">
                <div class="title">Past Events with Photos</div>
                <div class="description">Events with at least 1 photo.</div>
                <div class="value-container">
                    <div class="value"></div><span class="status hide">&nbsp;</span>
                </div>
            </div>
            <div id="metric-upcoming-events" class="panel">
                <div class="title">Upcoming Events</div>
                <div class="description">...and current events.</div>
                <div class="value-container">
                    <div class="value"></div><span class="status hide">&nbsp;</span>
                </div>
            </div>
            <div id="metric-photos-count" class="panel">
                <div class="title">Photo Count</div>
                <div class="description">Photos taken/uploaded.</div>
                <div class="value-container">
                    <div class="value"></div><span class="status hide">&nbsp;</span>
                </div>
            </div>
            <div id="metric-avg-event-photos" class="panel">
                <div class="title">Avg. photos/event</div>
                <div class="description">Average photos per event.</div>
                <div class="value-container">
                    <div class="value"></div><span class="status hide">&nbsp;</span>
                </div>
            </div>
            <div id="metric-avg-guest-photos" class="panel">
                <div class="title">Avg. photos/guest</div>
                <div class="description">TBD</div>
                <div class="value-container">
                    <div class="value">N/A</div><span class="status hide">&nbsp;</span>
                </div>
            </div>
        </div>
    </div>
    
    <div id="events" class="span12">
        <h2>Events</h2>
        <p>Next 50 events:</p>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>URL</th>
                <th>Public</th>
                <th>Start (UTC)</th>
                <th>End (UTC)</th>
                <th>Photos</th>
                <th>Actions</d>
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
                    echo "\t\t".'<td>'.str_replace('T', ' ', substr($event['start'], 0, -6)).'</td>'.PHP_EOL;
                    echo "\t\t".'<td>'.str_replace('T',' ', substr($event['end'], 0, -6)).'</td>'.PHP_EOL;
                    echo "\t\t".'<td>'.$event['photo_count'].'</td>'.PHP_EOL;
                    echo "\t\t".'<td>(delete, edit, etc...)</td>'.PHP_EOL;
                    echo "\t".'</tr>'.PHP_EOL;
                }
            ?>
        </table>
    </div>
</div>