<?php

namespace Domain\Repository;

use Domain\Article\Article;
use Domain\User\User;

interface ArticleRepositoryInterface {
    public function save(Article $article, User $user);

    public function find(Article $article);

    public function findAll();

    public function delete(Article $article);

    public function like(Article $article, User $user);

    public function dislike(Article $article, User $user);


}