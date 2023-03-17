<?php

namespace Domain\Repository;

use Domain\Article\Article;
use Domain\User\User;

interface ArticleRepositoryInterface {
    public function save(Article $article);

    public function findOwn($id);

    public function findAll();

    public function delete(Article $article);

    public function like(Article $article, User $user);

    public function dislike(Article $article, User $user);

    public function getLikes(Article $article);

    public function getDislikes(Article $article);


}