    <h1>Upcoming Events</h1>
    <p>List of upcoming events or events in progress.</p>
    <p>#EventID Title (event url) - start ~ end</p>
    <ul>
        <?php
            foreach ($events as $event) {
                $eventID = explode('/', $event['resource_uri']);
                echo '<li>#'.$eventID[3].' '.$event['title'].' ('.$event['url'].') - '.$event['start'].' ~ '.$event['end'].'</li>'; 
            }
        ?>
    </ul>
