<?php
class Auth {

    // Check if anyone is logged in
    public static function isLoggedIn() {
        Session::start();
        return Session::has('user_id');
    }

    // Get logged-in user's details
    public static function userId()    { return Session::get('user_id');    }
    public static function userRole()  { return Session::get('user_role');  }
    public static function userName()  { return Session::get('user_name');  }
    public static function userEmail() { return Session::get('user_email'); }

    // Redirect to login if not logged in
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /WebtechProject/public/?route=login');
            exit;
        }
    }

    // Redirect if user does not have the required role
    // Usage: Auth::requireRole('organiser');
    public static function requireRole($role) {
        self::requireLogin();
        if (self::userRole() !== $role) {
            header('Location: /WebtechProject/public/?route=unauthorized');
            exit;
        }
    }

    // Save user info to session on login
    public static function login($user) {
        Session::start();
        session_regenerate_id(true); // security: prevents session hijacking
        Session::set('user_id',    $user['id']);
        Session::set('user_role',  $user['role']);
        Session::set('user_name',  $user['name']);
        Session::set('user_email', $user['email']);
    }

    // Clear session and redirect to login
    public static function logout() {
        Session::destroy();
        header('Location: /WebtechProject/public/?route=login');
        exit;
    }
}