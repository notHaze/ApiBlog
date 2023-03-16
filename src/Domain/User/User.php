<?php
namespace Domain\User;

class User {
    private $username;
    private $password;
    private $role;
    private $id;

    public function __construct($username, $password, $role, $id) {
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->id = $id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRole() {
        return $this->role;
    }

    public function getId() {
        return $this->id;
    }
}

?>