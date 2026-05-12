<?php
class Router {
    private $routes = [];

    // Register a route
    // Example: $router->add('organiser/dashboard', 'OrganiserController', 'dashboard');
    public function add($route, $controller, $method) {
        $this->routes[$route] = [
            'controller' => $controller,
            'method'     => $method
        ];
    }

    // Read the URL and call the right controller + method
    public function dispatch() {
        $route = trim($_GET['route'] ?? 'home', '/');

        if (array_key_exists($route, $this->routes)) {
            $controllerName = $this->routes[$route]['controller'];
            $methodName     = $this->routes[$route]['method'];

            $controller = new $controllerName();
            $controller->$methodName();
        } else {
            $this->notFound();
        }
    }

    private function notFound() {
        http_response_code(404);
        echo "<h1>404 — Page not found</h1>";
        echo "<p>The page you are looking for does not exist.</p>";
    }
}