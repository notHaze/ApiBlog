<?php

namespace Application\Query\Article;

use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Domain\Repository\ArticleRepositoryInterface;

class PublishQueryHandler implements QueryHandlerInterface
{

    private $articleRepository;
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }



    public function ask(QueryInterface $query)
    {
        if (!$query instanceof PublishQuery) {
            throw new \InvalidArgumentException('PublishQueryHandler can only handle PublishQuery');
        }
        $article = $query->getArticle();
        $this->articleRepository->save($article);
    }
}
