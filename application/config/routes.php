<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/

# name of route, url, etc. -------------------- controller path #
$route['cms'] = 'cms/dashboard';
$route['cms/index'] = 'cms/dashboard';
$route['cms/index/'] = 'cms/dashboard';
$route['cms/index/(:any)'] = 'cms/dashboard/index/$1';
$route['cms/visitors/cesbie-visitors'] = 'cms/cesbie_visitors';
$route['cms/visitors/cesbie-visitors/index/(:any)'] = 'cms/cesbie_visitors/index/(:any)';
$route['cms/visitors/cesbie-visitors/index'] = 'cms/cesbie_visitors/index/';
$route['cms/visitors/cesbie-visitors/index/'] = 'cms/cesbie_visitors/index/';

$route['cms/visitors/guest-visitors'] = 'cms/guest_visitors';
$route['cms/visitors/guest-visitors/index/(:any)'] = 'cms/guest_visitors/index/(:any)';
$route['cms/visitors/guest-visitors/index'] = 'cms/guest_visitors/index/';
$route['cms/visitors/guest-visitors/index/'] = 'cms/guest_visitors/index/';

$route['cms/attached-agency'] = 'cms/attached_agency';
$route['cms/attached-agency/index/(:any)'] = 'cms/attached_agency/index/(:any)';
$route['cms/attached-agency/index'] = 'cms/attached_agency/index/';
$route['cms/attached-agency/index/'] = 'cms/attached_agency/index/';


$route['cms/cesbie-staffs/'] = 'cms/cesbie';
$route['cms/cesbie-staffs'] = 'cms/cesbie';
$route['cms/cesbie-staffs/index/(:any)'] = 'cms/cesbie/index/(:any)';
$route['cms/cesbie-staffs/index'] = 'cms/cesbie/index/';
$route['cms/cesbie-staffs/index/'] = 'cms/cesbie/index/';

$route['cms/staff/single/(:any)'] = 'cms/cesbie/single/$1';
$route['cms/staff/single/(:any)/index/'] = 'cms/cesbie/single/$1/';
$route['cms/staff/single/(:any)/index'] = 'cms/cesbie/single/$1/';
$route['cms/staff/single/(:any)/index/(:any)'] = 'cms/cesbie/single/$1/index/$2';
# My routes
$route['api/visitors/logout/step-1'] = 'api/visitors/logout_step_one';
$route['api/visitors/logout/step-2'] = 'api/visitors/logout_step_two';
$route['api/visitors/logout/print'] = 'api/visitors/logout_print';
$route['api/visitors/cesbie-logout'] = 'api/visitors/cesbie_logout';
$route['api/visitors/attached-agency/(:any)'] = 'api/visitors/attached_agency/$1';

$route['api/get-cities'] = 'api/cities/cities';
$route['api/get-provinces'] = 'api/cities/provinces';
$route['api/get-provinces-and-cities'] = 'api/cities/provinces_and_cities';
$route['api/visitors/guest-login'] = 'api/visitors/guest_login';
$route['api/visitors/guest-login/step-1'] = 'api/visitors/guest_login_step_one';
$route['api/visitors/guest-login/step-2'] = 'api/visitors/guest_login_step_two';
# karen new endpoint
$route['api/visitors/division'] = 'api/visitors/divisions';
$route['api/visitors/services/(:num)'] = 'api/visitors/service_by_division/$1';
# end
$route['api/visitors/guest-login/step-3'] = 'api/visitors/guest_login_step_three';
$route['api/visitors/cesbie-login'] = 'api/visitors/cesbie_login';
$route['api/example/(:num)'] = 'api/example/single/$1';

$route['migrate/(:any)'] = 'migrate/index/$1';

# Restserver default examples
$route['api/example/users/(:num)'] = 'api/example/users/id/$1';
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
