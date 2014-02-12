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
| There is one reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
*/

$route['default_controller']                    	 = 'page';

$route['login']										 = 'page/user_login';
$route['join']										 = 'page/user_join';
$route['edit']                                  	 = 'page/user_edit';

$route['login/facebook']                        	 = 'user/login_facebook';
$route['logout']									 = 'user/logout';

$route['login/do']                              	 = 'user/login';
$route['join/do']                               	 = 'user/join';

$route['write']                               	 	 = 'page/write';

$route['test']                               	 	 = 'page/test';

$route['(all|my)']                               	 = 'page/index/$1';
$route['(all|my)/(:num)']                            = 'page/index/$1/$2';

$route['edit/(:num)']								 = 'page/edit/$1';
$route['view/(:num)']								 = 'page/story/$1';
$route['preview/(:any)']							 = 'page/story/$1/preview';

$route['tag/(:any)/(:num)']							 = 'page/tag/$1/$2';
$route['tag/(:any)']							 	 = 'page/tag/$1';

$route['search/(:any)/(:num)']						 = 'page/search/$1/$2';
$route['search/(:any)']							 	 = 'page/search/$1';

$route['upload']								 	 = 'ajax/upload';

$route['ajax/(:any)/(:num)']						 = 'ajax/$1/$2';
$route['ajax/(:any)']								 = 'ajax/$1';

$route['user/(:any)']								 = 'page/user/$1';

$route['tools/(:any)']								 = 'tools/$1';

$route['(:num)']								 	 = 'page/story/$1';
$route['(:any)/(:any)']								 = 'page/story/$2/view/$1';
$route['(:any)']								 	 = 'page/user/$1';


/* End of file routes.php */
/* Location: ./application/config/routes.php */