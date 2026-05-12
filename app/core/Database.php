<?php
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        require_once dirname(__DIR__) . '/config/database.php';

        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }

        $this->conn->set_charset(DB_CHARSET);
    }

    // Returns the single shared connection
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Use this for SELECT queries — returns array of rows
    public function query($sql, $types = '', $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Query error: " . $this->conn->error);
        }
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Use this for INSERT, UPDATE, DELETE — returns affected rows
    public function execute($sql, $types = '', $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Query error: " . $this->conn->error);
        }
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->affected_rows;
    }

    // Returns the ID of the last inserted row
    public function lastInsertId() {
        return $this->conn->insert_id;
    }
}