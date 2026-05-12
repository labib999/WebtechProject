<?php
// Front Controller — every page request comes through this file

require_once '../app/core/Database.php';
require_once '../app/core/Session.php';
require_once '../app/core/Auth.php';
require_once '../app/core/Router.php';

Session::start();

// Temporary home page — proves everything is connected
$route = trim($_GET['route'] ?? '', '/');
if ($route === '' || $route === 'home') {
    try {
        Database::getInstance();
        echo '<h2 style="color:green;">&#10003; Event Platform is running. Database connected.</h2>';
    } catch (Exception $e) {
        echo '<h2 style="color:red;">Database error: ' . $e->getMessage() . '</h2>';
    }
    exit;
}

// Controllers — we uncomment each one as we build it
// require_once '../app/controllers/AuthController.php';
// require_once '../app/controllers/OrganiserController.php';

// Routes — we add each one as we build each feature
$router = new Router();
// $router->add('login',               'AuthController',      'showLogin');
// $router->add('register',            'AuthController',      'showRegister');
// $router->add('logout',              'AuthController',      'logout');
// $router->add('organiser/dashboard', 'OrganiserController', 'dashboard');

$router->dispatch();