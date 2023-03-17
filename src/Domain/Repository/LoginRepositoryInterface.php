<?php
namespace Domain\Repository;

use Domain\User\User;

interface LoginRepositoryInterface {
    public function login(User $login);

    public function findRole(User $login);

    public function getUsername(User $login);
}


?>