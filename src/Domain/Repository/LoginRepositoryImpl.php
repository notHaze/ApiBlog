<?php
namespace Domain\Repository;

use Domain\User\Exception\UserNotFoundException;
use Domain\User\User;
use Infrastructure\Database\Database;
use PDO;

class LoginRepositoryImpl implements LoginRepositoryInterface {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getDb();
    }

    public function login(User $login) {
        $username = $login->getUsername();
        $password = $login->getPassword();
        $sql = "SELECT count(*) FROM user WHERE username = :username AND password = :password";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result[0] == 0) {
            throw new UserNotFoundException();
        }
    }

    public function findRole(User $login) {
        $username = $login->getUsername();
        $password = $login->getPassword();
        $sql = "SELECT role,idUser FROM user WHERE username = :username AND password = :password";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $result = $stmt->fetch();
        $tabOutput = array("idUser" => $result[1], "role" => $result[0]);
        return $tabOutput;
    }

    public function getUsername(User $login)
    {
        $id = $login->getId();
        $sql = "SELECT username FROM user WHERE idUser = :idUser";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':idUser', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

}

?>