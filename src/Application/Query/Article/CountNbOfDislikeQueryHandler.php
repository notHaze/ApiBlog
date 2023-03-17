<?php

namespace Application\Query\Article;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\ArticleRepositoryInterface;

class CountNbOfDislikeQueryHandler implements QueryHandlerInterface
{
    private $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function ask(QueryInterface $query)
    {
        if (!$query instanceof CountNbOfDislikeQuery) {
            throw new \InvalidArgumentException('CountNbOfDislikeQueryHandler can only handle CountNbOfDislikeQuery');
        }
        $article = $query->getArticle();
        return $this->articleRepository->getDislikes($article);
    }
}
