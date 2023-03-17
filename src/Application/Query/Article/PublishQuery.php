<?php
namespace Application\Query\Article;
use Application\Query\QueryInterface;
use Domain\Article\Article;

class PublishQuery implements QueryInterface
{

    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }
}
?>
