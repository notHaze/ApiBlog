<?php

namespace Application\Query\User;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\LoginRepositoryInterface;
use Exception;
use Domain\User\User;

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

        $login = new User($query->getUsername(), $query->getPassword(), null, null);
        return $this->loginRepository->findRole($login);
    }
}