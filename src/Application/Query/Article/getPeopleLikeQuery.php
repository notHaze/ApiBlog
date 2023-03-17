<?php

namespace Application\Query\Article;

use Application\Query\QueryInterface;

class getPeopleLikeQuery implements QueryInterface
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}