<?php
// Front Controller — every page request comes through here

require_once '../app/core/Database.php';
require_once '../app/core/Session.php';
require_once '../app/core/Auth.php';
require_once '../app/core/Router.php';
require_once '../app/core/Controller.php';

// Controllers — we add each one as we build it
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/OrganiserController.php'; // coming soon

Session::start();

// Temporary home page
$route = trim($_GET['route'] ?? '', '/');
if ($route === '' || $route === 'home') {
    try {
        Database::getInstance();
        echo '<h2 style="color:#0F6E56;">&#10003; Event Platform running.</h2>';
        echo '<p><a href="/WebtechProject/public/login">Go to Login page</a></p>';
    } catch (Exception $e) {
        echo '<h2 style="color:red;">Database error: ' . $e->getMessage() . '</h2>';
    }
    exit;
}

// Routes — we add each one as we build each feature
$router = new Router();
$router->add('login',           'AuthController', 'showLogin');
$router->add('login-submit',    'AuthController', 'processLogin');
$router->add('register',        'AuthController', 'showRegister');
$router->add('register-submit', 'AuthController', 'processRegister');
$router->add('logout',          'AuthController', 'logout');
$router->add('organiser/dashboard', 'OrganiserController', 'dashboard');

$router->dispatch();