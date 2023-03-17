<?php

namespace Application\Query\Article;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\ArticleRepositoryInterface;

class ConsultQueryHandler implements QueryHandlerInterface
{
    private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function ask(QueryInterface $query)
    {
        if (!$query instanceof ConsultQuery) {
            throw new \InvalidArgumentException('ConsultQueryHandler can only handle ConsultQuery');
        }
        return $this->repository->findAll();
    }
}