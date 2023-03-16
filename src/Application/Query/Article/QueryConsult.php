<?php

namespace Application\Query\Article;
use Application\Query\QueryInterface;

class QueryConsult implements QueryInterface
{
    private $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }
}
?>