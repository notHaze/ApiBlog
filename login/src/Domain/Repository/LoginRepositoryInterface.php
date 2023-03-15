<?php
namespace Domain\Repository;

use Domain\User\Login;

interface LoginRepositoryInterface {
    public function login(Login $login);

    public function findRole(Login $login);
}


?>