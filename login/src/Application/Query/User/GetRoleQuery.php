<?php
namespace Application\Query\User;

use Application\Query\QueryInterface;


class GetRoleQuery implements QueryInterface
{
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}

?>