<div id="slides" data-event_id="<?= $event_id ?>" >
  <ul class="slides-container">
    <?php foreach ($photos as $photo) { ?>
    <li>
      <img src="/p/get/<?= SnapApi::resource_pk($photo->resource_uri) ?>/orig" alt="<?= $photo->caption ?>" />
        <?php
            if(!empty($photo->caption)) {
                echo '<div class="container">';
                echo '<div class="contrast">';
                echo '<p>'.$photo->caption.'</p>';
                echo '</div>';
                echo '</div>';
            }
        ?>
    </li>
    <?php } ?>

    <!--
    <li>
      <img src="http://flickholdr.com/400/400" alt="">
      <div class="container">
        <div class="contrast">
            <p>Slide one</p>
        </div>
      </div>
    </li>
    -->
  </ul>
  <nav class="slides-navigation">
    <a href="#" class="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
    <a href="#" class="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
  </nav>
</div>