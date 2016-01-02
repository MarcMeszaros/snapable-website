<div class="col-lg-12">

		<h1>Events</h1>

		<p>These are the events associated with your account.</p>

		<table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Event Date</th>
                <th class="text-center">Number of Photos</th>
                <th class="text-center">Privacy</th>
            </tr>
        </thead>
        <tbody>
						<?php foreach ($events as $key => $event) {
							$start = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $event->start_at);
							$end = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $event->end_at);
							?>
							<tr>
	                <td><a href="/event/<?php echo $event->url; ?>"><?php echo $event->title; ?></a></td>
	                <td><?php echo date_format($start, "F j, Y"); ?> to <?php echo date_format($end, "F j, Y"); ?></td>
	                <td class="text-center"><?php echo $event->photo_count; ?></td>
	                <td class="text-center"><?php echo $event->is_public ? 'Public' : 'Private'; ?></td>
	            </tr>
						<?php } ?>

            <?php if (empty($events)) { ?>
            <tr>
                <td colspan="4" class="text-center"><h3>No Events</h3></td>
            </tr>
						<?php } ?>
        </tbody>
		</table>

</div>
