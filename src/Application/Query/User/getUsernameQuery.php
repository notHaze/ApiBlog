<?php

namespace Application\Query\User;

use Application\Query\QueryInterface;

class getUsernameQuery implements QueryInterface
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->id;
    }
}