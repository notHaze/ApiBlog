<?php

namespace Application\Query\User;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\LoginRepositoryInterface;
use Domain\User\User;

class getUsernameHandler implements QueryHandlerInterface
{
    private $loginRepository;

    public function __construct(LoginRepositoryInterface $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    public function ask(QueryInterface $query)
    {
        if (!$query instanceof getUsernameQuery) {
            throw new \InvalidArgumentException('getUsernameHandler can only handle getUsernameQuery');
        }
        $login = new User(null, null, null, $query->getLogin());
        return $this->loginRepository->getUsername($login);
    }
}