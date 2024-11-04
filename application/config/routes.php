<?php

defined('BASEPATH') or exit('No direct script access allowed');

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
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|   $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|       my-controller/my-method -> my_controller/my_method
*/

$route['default_controller']   = 'clients';
$route['404_override']         = '';
$route['translate_uri_dashes'] = false;

/**
 * Dashboard clean route
 */
$route['admin'] = 'admin/dashboard';

/**
 * Misc controller routes
 */
$route['admin/access_denied'] = 'admin/misc/access_denied';
$route['admin/not_found']     = 'admin/misc/not_found';

/**
 * Staff Routes
 */
$route['admin/profile']           = 'admin/staff/profile';
$route['admin/profile/(:num)']    = 'admin/staff/profile/$1';
$route['admin/tasks/view/(:any)'] = 'admin/tasks/index/$1';

/**
 * Items search rewrite
 */
$route['admin/items/search'] = 'admin/invoice_items/search';

/**
 * In case if client access directly to url without the arguments redirect to clients url
 */
$route['/'] = 'clients';

/**
 * @deprecated
 */
$route['viewinvoice/(:num)/(:any)'] = 'invoice/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['invoice/(:num)/(:any)'] = 'invoice/index/$1/$2';

/**
 * @deprecated
 */
$route['viewestimate/(:num)/(:any)'] = 'estimate/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['estimate/(:num)/(:any)'] = 'estimate/index/$1/$2';
$route['subscription/(:any)']    = 'subscription/index/$1';

/**
 * @deprecated
 */
$route['viewproposal/(:num)/(:any)'] = 'proposal/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['proposal/(:num)/(:any)'] = 'proposal/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['contract/(:num)/(:any)'] = 'contract/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['knowledge-base']                 = 'knowledge_base/index';
$route['knowledge-base/search']          = 'knowledge_base/search';
$route['knowledge-base/article']         = 'knowledge_base/index';
$route['knowledge-base/article/(:any)']  = 'knowledge_base/article/$1';
$route['knowledge-base/category']        = 'knowledge_base/index';
$route['knowledge-base/category/(:any)'] = 'knowledge_base/category/$1';

/**
 * @deprecated 2.2.0
 */
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'add_kb_answer') === false) {
    $route['knowledge-base/(:any)']         = 'knowledge_base/article/$1';
    $route['knowledge_base/(:any)']         = 'knowledge_base/article/$1';
    $route['clients/knowledge_base/(:any)'] = 'knowledge_base/article/$1';
    $route['clients/knowledge-base/(:any)'] = 'knowledge_base/article/$1';
}

/**
 * @deprecated 2.2.0
 * Fallback for auth clients area, changed in version 2.2.0
 */
$route['clients/reset_password']  = 'authentication/reset_password';
$route['clients/forgot_password'] = 'authentication/forgot_password';
$route['clients/logout']          = 'authentication/logout';
$route['clients/register']        = 'authentication/register';
$route['clients/login']           = 'authentication/login';

// Aliases for short routes
$route['reset_password']  = 'authentication/reset_password';
$route['forgot_password'] = 'authentication/forgot_password';
$route['login']           = 'authentication/login';
$route['logout']          = 'authentication/logout';
$route['register']        = 'authentication/register';

/**
 * Terms and conditions and Privacy Policy routes
 */
$route['terms-and-conditions'] = 'terms_and_conditions';
$route['privacy-policy']       = 'privacy_policy';

/**
 * @since 2.3.0
 * Routes for admin/modules URL because Modules.php class is used in application/third_party/MX
 */
$route['admin/modules']               = 'admin/mods';
$route['admin/modules/(:any)']        = 'admin/mods/$1';
$route['admin/modules/(:any)/(:any)'] = 'admin/mods/$1/$2';

// Public single ticket route
$route['forms/tickets/(:any)'] = 'forms/public_ticket/$1';

/**
 * @since  2.3.0
 * Route for clients set password URL, because it's using the same controller for staff to
 * If user addded block /admin by .htaccess this won't work, so we need to rewrite the URL
 * In future if there is implementation for clients set password, this route should be removed
 */
$route['authentication/set_password/(:num)/(:num)/(:any)'] = 'admin/authentication/set_password/$1/$2/$3';
// Solution Category

$route['admin/solution-categories'] = 'admin/SolutionCategories/index';
$route['admin/solution-categories/edit/(:num)'] = 'admin/SolutionCategories/edit/$1';
$route['admin/solution-categories/update/(:num)'] = 'admin/SolutionCategories/update/$1';
$route['admin/solution-categories/delete/(:num)'] = 'admin/SolutionCategories/delete/$1';
$route['admin/solution-categories/steps/(:num)'] = 'admin/SolutionCategories/steps/$1';

$route['admin/solution-categories/add_step'] = 'admin/SolutionCategories/add_step';
$route['admin/solution-categories/edit_step/(:num)'] = 'admin/SolutionCategories/edit_step/$1';
$route['admin/solution-categories/delete_step/(:num)'] = 'admin/SolutionCategories/delete_step/$1';
$route['admin/solution-categories/update_step'] = 'admin/SolutionCategories/update_step';

// Project Locations
$route['admin/project-locations/(:num)'] = 'admin/ProjectLocations/index/$1';
$route['admin/project-locations/add/(:num)'] = 'admin/ProjectLocations/add/$1';
$route['admin/project-locations/edit/(:num)/(:num)'] = 'admin/ProjectLocations/edit/$1/$2';
$route['admin/project-locations/delete/(:num)/(:num)'] = 'admin/ProjectLocations/delete/$1/$2';

// BOQ
$route['admin/boq/(:num)'] = 'admin/Boq/index/$1';
$route['admin/boq/add/(:num)/(:num)'] = 'admin/Boq/add/$1/$2';
$route['admin/boq/import/(:num)/(:num)'] = 'admin/Boq/import/$1/$2';
$route['admin/boq/view/(:num)'] = 'admin/Boq/view/$1';
$route['admin/boq/report/(:num)'] = 'admin/Boq/report/$1';
$route['admin/boq/item-request/(:num)'] = 'admin/Boq/item_request/$1';
$route['admin/boq/request-items/(:num)'] = 'admin/Boq/request_items/$1';
$route['admin/boq/update-status/(:num)'] = 'admin/Boq/update_status/$1';
$route['admin/boq/requisitions/(:num)'] = 'admin/Boq/requisitions/$1';
$route['admin/boq/requisition/(:num)/(:num)'] = 'admin/Boq/requisitions/$1/$2';
$route['admin/boq/recieve-items/(:num)/(:num)'] = 'admin/Boq/issue/$1/$2';
$route['admin/boq/update-requisition-status/(:num)/(:num)'] = 'admin/Boq/update_requisition_status/$1/$2';
$route['admin/boq/recieve/(:num)/(:num)'] = 'admin/Boq/recieve/$1/$2';
$route['admin/boq/edit/(:num)/(:num)'] = 'admin/Boq/edit/$1/$2';
$route['admin/boq/delete/(:num)/(:num)'] = 'admin/Boq/delete/$1/$2';
$route['admin/boq/get_item_price'] = 'admin/boq/get_item_price';
$route['admin/boq/get_item_details'] = 'admin/boq/get_item_details';

// Inventory
$route['admin/inventory/(:num)'] = 'admin/Inventory/index/$1';

// Inventory
$route['admin/sub-contractor'] = 'admin/Subcontractor';
$route['admin/sub-contractor/get_sub_contractor/(:num)'] = 'admin/Subcontractor/get_sub_contractor/$1';
$route['admin/sub-contractor/boq/(:num)'] = 'admin/Subcontractor/boq/$1';
$route['admin/sub-contractor/import/(:num)'] = 'admin/Subcontractor/import/$1';
$route['admin/sub-contractor/view/(:num)'] = 'admin/Subcontractor/view/$1';
$route['admin/sub-contractor/edit_boq/(:num)/(:num)'] = 'admin/Subcontractor/edit_boq/$1/$2';
$route['admin/sub-contractor/delete_boq/(:num)/(:num)'] = 'admin/Subcontractor/delete_boq/$1/$2';
$route['admin/sub-contractor/projects/(:num)'] = 'admin/Subcontractor/projects/$1';
$route['admin/sub-contractor/track-material-issue/(:num)/(:num)'] = 'admin/Subcontractor/track_material_issue/$1/$2';

// Solution Matrix
$route['admin/solution-matrix'] = 'admin/SolutionMatrix/matrix';
$route['admin/solution-matrix/(:num)'] = 'admin/SolutionMatrix/matrix/$1';
$route['admin/solution-matrix/(:num)/(:num)'] = 'admin/SolutionMatrix/matrix/$1/$2';
$route['admin/solution-matrix/update-matrix'] = 'admin/SolutionMatrix/update_matrix';
// $route['admin/solution-matrix/insert-record'] = 'admin/SolutionMatrix/insert_record';
// $route['admin/solution-matrix/delete-record'] = 'admin/SolutionMatrix/delete_record';

// $route['solution-categories'] = 'admin/solution-categories/add-new';
// For backward compatilibilty
$route['survey/(:num)/(:any)'] = 'surveys/participate/index/$1/$2';

if (file_exists(APPPATH . 'config/my_routes.php')) {
    include_once(APPPATH . 'config/my_routes.php'); 
}
