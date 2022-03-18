<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// login
$routes->get("/login", "Auth::login");
$routes->post("/login", "Auth::login");

// logout
$routes->get("/logout", "Auth::logout");

// users
$routes->get("/profile", "Users::profile");
$routes->post("/profile", "Users::editProfile");
$routes->post("/profile-edit-password", "Users::profileEditPassword");
$routes->post("/user-list", "Users::listUser", ['filter' => 'auth:onlyadmin']);
$routes->get("/user-add", "Users::addUser", ['filter' => 'auth:onlyadmin']);
$routes->post("/user-add", "Users::addUser", ['filter' => 'auth:onlyadmin']);
$routes->get("/user-detail/(:num)", "Users::detailUser/$1", ['filter' => 'auth:onlyadmin']);
$routes->post("/user-edit", "Users::editUser", ['filter' => 'auth:onlyadmin']);
$routes->post("/user-edit-password", "Users::editPassword", ['filter' => 'auth:onlyadmin']);
$routes->post("/user-delete", "Users::deleteUser", ['filter' => 'auth:onlyadmin']);

// costumers
$routes->post("/costumer-list", "Costumers::listCostumer");
$routes->get("/costumer-add", "Costumers::addCostumer");
$routes->post("/costumer-add", "Costumers::addCostumer");
$routes->get("/costumer-detail/(:num)", "Costumers::detailCostumer/$1");
$routes->post("/costumer-edit", "Costumers::editCostumer");
$routes->post("/costumer-delete", "Costumers::deleteCostumer", ['filter' => 'auth:onlyadmin']);

// services
$routes->post("/service-list", "Services::listService");
$routes->post("/service-add", "Services::addService", ['filter' => 'auth:onlyadmin']);
$routes->post("/service-edit", "Services::editService", ['filter' => 'auth:onlyadmin']);
$routes->post("/service-delete", "Services::deleteService", ['filter' => 'auth:onlyadmin']);

// orders
$routes->post("/order-list", "Orders::listOrder");
$routes->get("/order-add", "Orders::addOrder");
$routes->post("/order-add", "Orders::addOrder");
$routes->get("/order-detail/(:num)", "Orders::detailOrder/$1");
$routes->post("/order-pay", "Orders::payOrder");
$routes->post("/order-cancel", "Orders::cancelOrder");

// transactions
$routes->post("/transaction-list", "Transactions::listTransaction");
$routes->get("/transaction-detail/(:any)", "Transactions::detailTransaction/$1");
$routes->get("/transaction-print/(:any)", "Transactions::detailTransaction/$1/true");

// report
$routes->get("/report", "Report::index", ['filter' => 'auth:onlyadmin']);
$routes->post("/export", "Report::export", ['filter' => 'auth:onlyadmin']);

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
