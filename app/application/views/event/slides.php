<div id="slides" data-event_id="<?= $event_id ?>">
  <div id="overlay">
    <button id="fullscreen" class="btn btn-primary fullscreen" href="#" >Full Screen</button>
    <br>
    <a id="hide-controls" href="#">Hide/Show Arrows</a>
  </div>
  <ul class="slides-container letterbox">
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
  </ul>
  <nav id="navigation-controls" class="slides-navigation">
    <a href="#" class="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
    <a href="#" class="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
  </nav>

</div>