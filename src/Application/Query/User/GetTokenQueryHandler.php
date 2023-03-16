<?php

namespace Application\Query\User;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\tokenJWT\Exception\TokenNotFoundException;
use Exception;
use Domain\User\User;
use Domain\tokenJWT\tokenJWTImpl;

class GetTokenQueryHandler implements QueryHandlerInterface
{

    public function __construct()
    {
    }

    public function ask(QueryInterface $query) {
        if (!$query instanceof GetTokenQuery) {
            throw new Exception("GetTokenQueryHandler can only handle GetTokenQuery");
        }

        $jwt = new tokenJWTImpl();
        $bearer = $jwt->getBearerToken();
        if ($bearer == null) {
            throw new TokenNotFoundException("Token not found in bearer");
        }
        return $bearer;
    }
}