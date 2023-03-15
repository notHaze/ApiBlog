<?php

namespace Infrastructure\Database;

use \PDO;

class Database {

    static private $instance = null;
    private $pdo;


    private function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
    }

    public static function getInstance() {
        if (empty(Database::$instance)) {
            Database::$instance = new Database();
        }
        return Database::$instance;
    }

    public function getDb() {
        return $this->pdo;
    }

}

?>