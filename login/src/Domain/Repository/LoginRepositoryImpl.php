<?php
namespace Domain\Repository;

use Domain\Login\Exception\LoginNotFoundException;
use Domain\Login\Login;
use Infrastructure\Database\Database;

class LoginRepositoryImpl implements LoginRepositoryInterface {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getDb();
    }

    public function login(Login $login) {
        $username = $login->getUsername();
        $password = $login->getPassword();
        $sql = "SELECT count(*) FROM user WHERE username = :username AND password = :password";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result[0] == 0) {
            throw new LoginNotFoundException();
        }
    }

    public function findRole(Login $login) {
        $username = $login->getUsername();
        $password = $login->getPassword();
        $sql = "SELECT role,id FROM user WHERE username = :username AND password = :password";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $result = $stmt->fetch();
        $tabOutput = array("id" => $result[1], "role" => $result[0]);
        return $tabOutput;
    }

}

?>