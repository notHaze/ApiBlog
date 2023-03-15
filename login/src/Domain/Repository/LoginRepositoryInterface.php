<?php
namespace Domain\Repository;

use Domain\Login\Login;

interface LoginRepositoryInterface {
    public function login(Login $login);

    public function findRole(Login $login);
}


?>