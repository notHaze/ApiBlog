<?php

namespace Application\Query\Article;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Exception;

class getPeopleLikeHandler implements QueryHandlerInterface {

    private $articleRepository;

    public function __construct($articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function ask(QueryInterface $query)
    {
        if (!$query instanceof getPeopleLikeQuery) {
            throw new Exception('getPeopleLikeHandler can only handle getPeopleLikeQuery');
        }
        return $this->articleRepository->getPeopleLike($query->getId());
    }
}