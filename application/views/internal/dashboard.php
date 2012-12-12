<div class="container">
    <div id="metrics" class="span12">
        <h2>Metrics</h2>
        <div class="panel"><div class="title">Total Signups</div><div class="value"><?php echo $total_signups; ?></div></div>
        <div class="panel"><div class="title">Uniques</div><div class="value">N/A</div></div>
        <div class="panel"><div class="title">Signup Conversion</div><div class="value">N/A</div></div>
        <div class="panel"><div class="title"># of events to date</div><div class="value"><?php echo $total_events_to_date; ?></div></div>
        <div class="panel"><div class="title"># of upcoming/in progress events</div><div class="value"><?php echo $total_upcoming_events; ?></div></div>
        <div class="panel"><div class="title"># events to date with photos</div><div class="value">N/A</div></div>
        <div class="panel"><div class="title">Avg. photos/event</div><div class="value">N/A</div></div>
        <div class="panel"><div class="title">Avg. photos/guest</div><div class="value">N/A</div></div>
    </div>
    
    <div id="events" class="span12">
        <h2>Events</h2>
        <p>Next 50 upcoming/in progress events:</p>
        <table border="1">
            <tr>
                <th>EventID</th>
                <th>Title</th>
                <th>URL</th>
                <th>Public</th>
                <th>Start (UTC)</th>
                <th>End (UTC)</th>
                <th>Photo Count</th>
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
                    echo "\t".'</tr>'.PHP_EOL;
                }
            ?>
        </table>
    </div>
</div>