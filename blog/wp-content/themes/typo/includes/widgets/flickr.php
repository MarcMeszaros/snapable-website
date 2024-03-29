<?php
/*-----------------------------------------------------------------------------------*/
/* Flickr Widget
/*-----------------------------------------------------------------------------------*/

wp_register_sidebar_widget('flickr','Okay Flickr Widget','show_flickr');

wp_register_widget_control(
'flickr', // your unique widget id
'Okay Flickr Widget', // widget name
'flickr_control' // Callback function
);

function flickr_control(){

	echo "Title: <input type='text' name='flickr-title' value='" .get_option('flickr_title'). "'/><br/></br>";
	if(isset($_POST['flickr-title'])) update_option('flickr_title',$_POST['flickr-title']);
	
	echo "Flickr ID: <input type='text' name='flickr-name' value='" .get_option('flickr_name'). "'/><br/></br>";
	if(isset($_POST['flickr-name'])) update_option('flickr_name',$_POST['flickr-name']);
	
	echo "Use http://idgettr.com to find your ID.<br/></br>"; 
	
	echo "No. of Photos: <input type='text' name='flickr-count' value='" .get_option('flickr_count'). "'/><br/></br>";
	if(isset($_POST['flickr-count'])) update_option('flickr_count',$_POST['flickr-count']);
}
function show_flickr() { ?>
	<div class="widget">
		<div class="flickr">
			<h2 class="widgettitle"><?php echo get_option('flickr_title') ?></h2>
			<?php $flickrname =  get_option('flickr_name') ?>
			<?php $flickrcount =  get_option('flickr_count'); ?>
			<?php getFlickrPhotos($flickrname,6); ?>
		</div>
	</div>
	
	
<?php } ?>
<?php
//Get flickr media and display based on user id
function getFlickrPhotos($id, $limit=9) {
    require_once("flickr/phpFlickr.php");
    $f = new phpFlickr("c1bc021fa23ba9a103aa88743dd9ad33");
    $flickrname =  get_option('flickr_user', '64104492@N02' );
    $flickrcount =  get_option('flickr_count');
    $photos = $f->people_getPublicPhotos($id, NULL, NULL, $flickrcount);
    $return.='<ul class="flickrPhotos">';
    foreach ($photos['photos']['photo'] as $photo) {
        $return.='<li><a target="_blank" rel="flickr" href="http://www.flickr.com/'.get_option('flickr_name').'/' . $photo['id'] . '" title="' . $photo['title'] . '"><img class="flickr-img" src="' . $f->buildPhotoURL($photo, 'medium') . '" alt="' . $photo['title'] . '" title="' . $photo['title'] . '" /></a></li>';
    }
    echo $return.='</ul>';
}
?>