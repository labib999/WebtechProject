<?php
require_once '../app/core/Database.php';
require_once '../app/core/Session.php';
require_once '../app/core/Auth.php';
require_once '../app/core/Router.php';
require_once '../app/core/Controller.php';

require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/OrganiserController.php';
require_once '../app/controllers/EventController.php';
require_once '../app/controllers/TierController.php';
require_once '../app/controllers/CheckinController.php';
require_once '../app/controllers/BookingController.php';
require_once '../app/controllers/AnalyticsController.php';
require_once '../app/controllers/DiscountController.php';
require_once '../app/controllers/RefundController.php';
require_once '../app/controllers/ReviewController.php';
require_once '../app/controllers/AnnouncementController.php';

Session::start();

$route = trim($_GET['route'] ?? '', '/');
if ($route === '' || $route === 'home') {
    try {
        Database::getInstance();
        echo '<h2 style="color:#0F6E56;">&#10003; Event Platform running.</h2>';
        echo '<p><a href="/WebtechProject/public/login">Go to Login</a></p>';
    } catch (Exception $e) {
        echo '<h2 style="color:red;">Database error: ' . $e->getMessage() . '</h2>';
    }
    exit;
}

$router = new Router();
$router->add('login',                        'AuthController',         'showLogin');
$router->add('login-submit',                 'AuthController',         'processLogin');
$router->add('register',                     'AuthController',         'showRegister');
$router->add('register-submit',              'AuthController',         'processRegister');
$router->add('logout',                       'AuthController',         'logout');
$router->add('organiser/dashboard',          'OrganiserController',    'dashboard');
$router->add('organiser/events',             'EventController',        'index');
$router->add('organiser/events/create',      'EventController',        'create');
$router->add('organiser/events/store',       'EventController',        'store');
$router->add('organiser/events/publish',     'EventController',        'publish');
$router->add('organiser/events/cancel',      'EventController',        'cancel');
$router->add('organiser/events/edit',        'EventController',        'edit');
$router->add('organiser/events/update',      'EventController',        'update');
$router->add('organiser/tiers',              'TierController',         'index');
$router->add('organiser/tiers/store',        'TierController',         'store');
$router->add('organiser/tiers/delete',       'TierController',         'delete');
$router->add('organiser/checkin',            'CheckinController',      'scanner');
$router->add('organiser/checkin/process',    'CheckinController',      'process');
$router->add('organiser/bookings',           'BookingController',      'index');
$router->add('organiser/analytics',          'AnalyticsController',    'index');
$router->add('organiser/discounts',          'DiscountController',     'index');
$router->add('organiser/discounts/store',    'DiscountController',     'store');
$router->add('organiser/discounts/toggle',   'DiscountController',     'toggle');
$router->add('organiser/refunds',            'RefundController',       'index');
$router->add('organiser/refunds/approve',    'RefundController',       'approve');
$router->add('organiser/refunds/reject',     'RefundController',       'reject');
$router->add('organiser/reviews',            'ReviewController',       'index');
$router->add('organiser/reviews/reply',      'ReviewController',       'reply');
$router->add('organiser/announcements',      'AnnouncementController', 'index');
$router->add('organiser/announcements/send', 'AnnouncementController', 'send');

$router->dispatch();