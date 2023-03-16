<?php

namespace Application\Query\User;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\LoginRepositoryInterface;
use Domain\tokenJWT\tokenJWTImpl;
use Exception;
use Domain\User\User;

class GetRoleQueryHandler implements QueryHandlerInterface
{

    public function __construct()
    {
    }

    public function ask(QueryInterface $query) {
        if (!$query instanceof GetRoleQuery) {
            throw new Exception("GetRoleQueryHandler can only handle GetRoleQuery");
        }

        $tokenJWT = new tokenJWTImpl();
        return $tokenJWT->getPayload($query->getToken());
    }
}