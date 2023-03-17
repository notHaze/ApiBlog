<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Domain\Article\Article;


class ArticleController
{
    public function getAll(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $article = new Article(1, "2020-01-01", "body", "writer");

        $jsonOutput = json_encode(array("status" => "success", "article" => $article));
        $response->getBody()->write($jsonOutput);

        return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);
    }

    public function get(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $article = new Article(1, "2020-01-01", "body", "writer");

        $jsonOutput = json_encode(array("status" => "success", "article" => $article));
        $response->getBody()->write($jsonOutput);

        return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);
    }
}