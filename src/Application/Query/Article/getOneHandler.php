<?php

namespace Application\Query\Article;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\ArticleRepositoryInterface;

class getOneHandler implements QueryHandlerInterface
{
    private $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function ask(QueryInterface $query)
    {
        if (!$query instanceof getOneQuery) {
            throw new \InvalidArgumentException('getOneHandler can only handle getOneQuery');
        }
        return $this->articleRepository->getOne($query->getId());
    }
}