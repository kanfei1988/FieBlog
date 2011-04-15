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

$route['default_controller']		= "welcome";
$route['posts/(:num)']				= 'posts/index/$1';
$route['post/(:any)']				= 'post/index/$1';
$route['trackback/(:num)']			= 'comments/trackback/$1';
$route['comment/(:num)']			= 'comment/index/$1';
$route['feed/(:any)']				= 'feed/index/$1';
$route['category/(:any)']			= 'posts/category/$1';
$route['tag/(:any)']				= 'posts/tag/$1';
$route['author/(:any)']				= 'posts/author/$1';
$route['archive/(:any)']			= 'posts/archive/$1';
$route['search']					= 'posts/search';

$route['admin']						= "admin/login/index";
$route['admin/settings/general']	= 'admin/settings/index/general';
$route['admin/settings/discussion'] = 'admin/settings/index/discussion';
$route['admin/settings/reading']	= 'admin/settings/index/reading';
$route['admin/settings/cache']		= 'admin/settings/index/cache';
$route['404_override']				= '';

/* End of file routes.php */
/* Location: ./application/config/routes.php */