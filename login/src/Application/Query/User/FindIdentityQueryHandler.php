<?php

namespace Application\Query\User;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\LoginRepositoryInterface;
use Exception;
use Domain\User\Login;

class FindIdentityQUeryHandler implements QueryHandlerInterface
{
    private LoginRepositoryInterface $loginRepository;

    public function __construct(LoginRepositoryInterface $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    public function ask(QueryInterface $query) {
        if (!$query instanceof FindIdentityQuery) {
            throw new Exception("FIndIdentityQueryHandler can only handle FindIdentityQuery");
        }

        $login = new Login($query->getUsername(), $query->getPassword());
        return $this->loginRepository->findRole($login);
    }
}