<?php

namespace Application\Query\Article;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\ArticleRepositoryInterface;
use Exception;

class getOwnQueryHandler implements QueryHandlerInterface
{
    private ArticleRepositoryInterface $repository;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function ask(QueryInterface $query)
    {
        if (!$query instanceof getOwnQuery) {
            throw new Exception('getOwnQueryHandler can only handle getOwnQuery');
        }
        return $this->repository->findOwn($query->getId());
    }
}