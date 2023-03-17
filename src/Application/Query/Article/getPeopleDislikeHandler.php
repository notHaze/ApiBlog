<?php

namespace Application\Query\Article;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Exception;

class getPeopleDislikeHandler implements QueryHandlerInterface {

    private $articleRepository;

    public function __construct($articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function ask(QueryInterface $query)
    {
        if (!$query instanceof getPeopleDislikeQuery) {
            throw new Exception('getPeopleDislikeHandler can only handle getPeopleDislikeQuery');
        }
        return $this->articleRepository->getPeopleDislike($query->getId());
    }
}