<?php

namespace Application\Query\Login;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\LoginRepositoryInterface;
use Exception;
use Domain\Login\Login;

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