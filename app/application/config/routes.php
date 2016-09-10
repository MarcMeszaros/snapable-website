<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = '';

// == custom routes ==
$route['goodbye'] = 'home/goodbye'; // exception for the event name

// buy controller

// event controller
$route['event/find'] = 'event/find'; // exception for the event name
$route['event/privacy'] = 'event/privacy'; // exception for the event name

$route['event/get/(:any)'] = 'event/get_tasks/$1';
$route['event/guests/(:any)'] = 'event/guest_tasks/$1';
$route['event/details/(:any)'] = 'event/details_tasks/$1';
$route['event/(:any)/(:any)'] = 'event/event_tasks/$2';
$route['event/(:any)'] = 'event/load_event/$1';

// p controller
$route['p/get/(:any)/(:any)'] = 'p/get_photo/$1/$2';
$route['p/get/(:any)'] = 'p/get_photo/$1';
$route['p/get_photo/(:any)'] = 'p/get_photo/$1';
$route['p/get_event/(:any)'] = 'p/get_event/$1';
$route['p/(:any)'] = 'p/load_photo/$1';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
