    <h2>Events</h2>
    <p>List of upcoming events or events in progress.</p>
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
                echo "\t\t".'<td>'.$event['start'].'</td>'.PHP_EOL;
                echo "\t\t".'<td>'.$event['end'].'</td>'.PHP_EOL;
                echo "\t\t".'<td>'.$event['photo_count'].'</td>'.PHP_EOL;
                echo "\t".'</tr>'.PHP_EOL;
            }
        ?>
    </table>
    