<?php

namespace Application\Query\Article;

use Application\Query\QueryInterface;

class getOneQuery implements QueryInterface
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}