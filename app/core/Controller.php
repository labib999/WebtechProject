<?php
class Controller {

    // Load a view file and pass data to it
    // Usage: $this->view('auth/login', ['error' => 'Wrong password'])
    protected function view($viewPath, $data = []) {
        extract($data); // turns array keys into variables
        $file = '../app/views/' . $viewPath . '.php';
        if (file_exists($file)) {
            require $file;
        } else {
            echo "View not found: " . htmlspecialchars($viewPath);
        }
    }

    // Redirect to another page
    // Usage: $this->redirect('organiser/dashboard')
    protected function redirect($route) {
        header('Location: /WebtechProject/public/' . $route);
        exit;
    }

    // Send a JSON response (used for all AJAX endpoints)
    // Usage: $this->json(['status' => 'ok', 'message' => 'Checked in'])
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}