<?php

namespace Domain\Repository;

use Domain\Article\Article;

interface ArticleRepositoryInterface {
    public function save(Article $article);

    public function find(Article $article);

    public function findAll();

    public function delete(Article $article);

    public function like(Article $article);

    public function dislike(Article $article);


}