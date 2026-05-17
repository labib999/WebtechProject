<?php
/**
 * Base Model Class
 * All models extend this class to get database access.
 * Implements the Model layer of the MVC pattern.
 */
class Model {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }
}