
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <title>Snapable - Internal</title>
    <meta name="Keywords" content="" /> 
    <meta name="Description" content="" />
    
    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" /> 
    <link rel="SHORTCUT ICON" href="/favicon.ico"/> 
    
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700' rel='stylesheet' type='text/css'>
    <?php if ( isset($css) ) { ?>
    <link rel="stylesheet" href="/min/c/<?= $css ?>" type="text/css" media="screen" />
    <?php } ?>
    <?php if ( isset($js) ) { ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/min/j/<?= $js ?>"></script>
    <?php } ?>
</head>

<body id="top">

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

</body>
</html>