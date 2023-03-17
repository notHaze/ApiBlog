<?php

namespace Application\Query\Article;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\ArticleRepositoryInterface;

class CountNbOfLikeQueryHandler implements QueryHandlerInterface
{
    private $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }


    public function ask(QueryInterface $query)
    {
        if (!$query instanceof CountNbOfLikeQuery) {
            throw new \InvalidArgumentException('CountNbOfLikeQueryHandler can only handle CountNbOfLikeQuery');
        }
        $article = $query->getArticle();
        return $this->articleRepository->getLikes($article);
    }
}
