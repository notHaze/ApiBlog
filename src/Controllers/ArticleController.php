<?php

namespace Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Application\Query\Article\getOneQuery;
use Application\Query\Article\getOwnQuery;
use Infrastructure\SynchronousQueryBus;
use Psr\Http\Message\ResponseInterface;
use Domain\Article\Article;
use Infrastructure\SynchronousCommandBus;
use Application\Query\Article\PublishQuery;
use Application\Command\Article\CommandDelete;
use Application\Command\Article\CommandDislike;
use Application\Command\Article\CommandLike;
use Application\Query\Article\ConsultQuery;
use Application\Query\Article\CountNbOfDislikeQuery;
use Application\Query\Article\CountNbOfLikeQuery;
use Application\Query\Article\getPeopleDislikeQuery;
use Application\Query\Article\getPeopleLikeQuery;
use Application\Query\User\getUsernameQuery;

class ArticleController
{
    public function getAll($request, ResponseInterface $response, array $args)
    {
       $role = $request->getAttribute("role");

       $articles = SynchronousQueryBus::ask(new ConsultQuery());

       if ($role != null) {
        $prefixURL =    $request->getUri()->getScheme() . '://' .
                        $request->getUri()->getHost()."/api/ApiBlog/article/";
       }

       $articleFormate = array();
        //Pour chaque article, on récupère le nombre de like , le nombre de dislike et on construit les url de modification et de suppression
        $i = 0;
        foreach ($articles as $article) {
            
            $articleFormate += array( $i => array(
                "date" => $article['datePubli'],
                "body" => $article['contenu'],
                "auteur" => $article['username']
                
            ));
            if ($role == "publisher") {
                $art = new Article($article['idArticle'], $article['datePubli'], $article['contenu'], $article['username']);
                $articleFormate[$i] += array(
                    "nbLike" => SynchronousQueryBus::ask(new CountNbOfLikeQuery($art))[0]['nbLike'],
                    "nbDislike" => SynchronousQueryBus::ask(new CountNbOfDislikeQuery($art))[0]['nbDislike'],
                );
                //l'article n'est pas le sien, il peut liker ou disliker
                if ($article['idUser'] != $request->getAttribute("idUser")) {
                    $articleFormate[$i] += array( "action" => array(
                        "liker" => array("url" => $prefixURL.$article['idArticle']."/like", "method" => "PATCH"),
                        "disliker" => array("url" => $prefixURL.$article['idArticle']."/dislike", "method" => "PATCH")
                    ));
                }
            }
            if ($role == "moderator") {
                $art = new Article($article['idArticle'], $article['datePubli'], $article['contenu'], $article['username']);
                $articleFormate[$i] += array(
                    "nbLike" => SynchronousQueryBus::ask(new CountNbOfLikeQuery($art))[0]['nbLike'],
                    "nbDislike" => SynchronousQueryBus::ask(new CountNbOfDislikeQuery($art))[0]['nbDislike'],
                    "likeUsers" => SynchronousQueryBus::ask(new getPeopleLikeQuery($art->getId())),
                    "dislikeUsers" => SynchronousQueryBus::ask(new getPeopleDislikeQuery($art->getId())),
                );
                //Si c'est un moderateur il peut tout surppimer ou modifier
                $articleFormate[$i] += array( "action" => array(
                    "delete" => array("url" => $prefixURL.$article['idArticle'], "method" => "DELETE"),
                    "update" => array("url" => $prefixURL.$article['idArticle'], "method" => "PATCH")
                ));

                //l'article n'est pas le sien, il peut liker ou disliker
                if ($article['idUser'] != $request->getAttribute("idUser")) {
                    $articleFormate[$i]["action"] += array(
                        "liker" => array("url" => $prefixURL.$article['idArticle']."/like", "method" => "PATCH"),
                        "disliker" => array("url" => $prefixURL.$article['idArticle']."/dislike", "method" => "PATCH")
                    );
                }
            }

            $i++;
        }

       $response->getBody()->write(json_encode(array("status" => "success", "code"=> 200, "articles" => $articleFormate)));
         return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);


    }

    //Lorsque un publisher ou un moderateur veut recuperer ses articles
    public function get($request, ResponseInterface $response, array $args)
    {
        //On récupère l'id de l'utilisateur
        $id = $request->getAttribute("idUser");
        $role = $request->getAttribute("role");

        //On récupère les articles de l'utilisateur
        $articles = SynchronousQueryBus::ask(new getOwnQuery($id));

        //Get url from request
        $prefixURL =    $request->getUri()->getScheme() . '://' .
                        $request->getUri()->getHost()."/apiBlog/article/";


        $articleFormate = array();
        //Pour chaque article, on récupère le nombre de like , le nombre de dislike et on construit les url de modification et de suppression
        $i = 0;
        foreach ($articles as $article) {
            $art = new Article($article['idArticle'], $article['datePubli'], $article['contenu'], $article['username']);
            $articleFormate += array( $i => array(
                "date" => $article['datePubli'],
                "body" => $article['contenu'],
                "auteur" => $article['username'],
                "likes" => SynchronousQueryBus::ask(new CountNbOfLikeQuery($art))[0]['nbLike'],
                "dislikes" => SynchronousQueryBus::ask(new CountNbOfDislikeQuery($art))[0]['nbDislike'],
                "action" => array(
                    "update" => array(
                        "url" => $prefixURL.$article['idArticle'],
                        "method" => "PATCH"
                    ),
                    "delete" => array(
                        "url" => $prefixURL.$article['idArticle'],
                        "method" => "DELETE"
                    )
                ),
            ));
            if ($role=="moderator") {
                $articleFormate[$i] += array(
                    "likeUsers" => SynchronousQueryBus::ask(new getPeopleLikeQuery($art->getId())),
                    "dislikeUsers" => SynchronousQueryBus::ask(new getPeopleDislikeQuery($art->getId())),
                );
            }
            $i++;
        }

        $jsonOutput = json_encode(array("status" => "success", "articles" => $articleFormate));
        $response->getBody()->write($jsonOutput);

        return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);

    }

    public function create($request, ResponseInterface $response, array $args)
    {
        $input = $request->getBody()->getContents();
        $input = json_decode($input, true);
        if (!isset($input['body']) || empty($input['body'])) {
            $response->getBody()->write(json_encode(array("status" => "failure", "message" => "body not set")));
            return $response->withStatus(400);
        }
        $body = $input['body'];

        //On récupère l'id de l'utilisateur
        $id = $request->getAttribute("idUser");
        $role = $request->getAttribute("role");

        $date = date("Y-m-d H:i:s");
        //On crée l'article
        $article = new Article(null, $date, $body, $id);

        //On enregistre l'article
        $idArticle = SynchronousQueryBus::ask(new PublishQuery($article));

        $prefixURL =    $request->getUri()->getScheme() . '://' .
                        $request->getUri()->getHost()."/apiBlog/article/";

        $article->setId($idArticle);


        $articleFormate = array(
            "date" => $article->getPublicationDate(),
            "body" => $article->getBody(),
            "auteur" => SynchronousQueryBus::ask(new getUsernameQuery($article->getWriter()))['username'],
            "likes" => SynchronousQueryBus::ask(new CountNbOfLikeQuery($article))[0]['nbLike'],
            "dislikes" => SynchronousQueryBus::ask(new CountNbOfDislikeQuery($article))[0]['nbDislike'],
            "action" => array(
                "liker" => array("url" => $prefixURL.$article->getId(), "method" => "PATCH"),
                "dislike" => array("url" => $prefixURL.$article->getId(), "method" => "PATCH")
            )
        );
        if ($role=="moderator") {
            $articleFormate += array(
                "likeUsers" => SynchronousQueryBus::ask(new getPeopleLikeQuery($article->getId())),
                "dislikeUsers" => SynchronousQueryBus::ask(new getPeopleDislikeQuery($article->getId())),
            );
        }

        $jsonOutput = json_encode(array("status" => "success", "article" => $articleFormate));
        $response->getBody()->write($jsonOutput);

        return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);
    }

    public function update($request, ResponseInterface $response, array $args)
    {
        //On récupère l'id de l article à modifier 
        $idArticle = $args['id'];

        $input = $request->getBody()->getContents();
        $input = json_decode($input, true);
        if (!isset($input['body']) || empty($input['body'])) {
            $response->getBody()->write(json_encode(array("status" => "failure", "message" => "body not set")));
            return $response->withStatus(400);
        }
        $body = $input['body'];

        //On récupère l'id de l'utilisateur
        $id = $request->getAttribute("idUser");
        $role = $request->getAttribute("role");

        //On récupère la date de publication
        $articleExistant = SynchronousQueryBus::ask(new getOneQuery($idArticle));
        //Si l'article est vide
        if (empty($articleExistant)) {
            $response->getBody()->write(json_encode(array("status" => "failure", "message" => "Article not found")));
            return $response->withStatus(404)->withAddedHeader("Content-Type", "application/json");
        }
        if ($articleExistant[0]['idAuteur'] != $id && $role != "moderator" ) {
            $response->getBody()->write(json_encode(array("status" => "failure", "message" => "You can't update this article")));
            return $response->withStatus(403)->withAddedHeader("Content-Type", "application/json");
        }

        //On crée l'article
        $article = new Article($idArticle, $articleExistant[0]['datePubli'], $body, $articleExistant[0]['idAuteur']);

        //On enregistre l'article
        SynchronousQueryBus::ask(new PublishQuery($article));

        $prefixURL =    $request->getUri()->getScheme() . '://' .
                        $request->getUri()->getHost()."/api/ApiBlog/article/";


        $articleFormate = array(
            "date" => $article->getPublicationDate(),
            "body" => $article->getBody(),
            "auteur" => SynchronousQueryBus::ask(new getUsernameQuery($article->getWriter()))['username'],
            "likes" => SynchronousQueryBus::ask(new CountNbOfLikeQuery($article))[0]['nbLike'],
            "dislikes" => SynchronousQueryBus::ask(new CountNbOfDislikeQuery($article))[0]['nbDislike'],
            "action" => array(
                "liker" => array("url" => $prefixURL.$article->getId(), "method" => "PATCH"),
                "dislike" => array("url" => $prefixURL.$article->getId(), "method" => "PATCH")
            )
        );
        if ($role=="moderator") {
            $articleFormate += array(
                "likeUsers" => SynchronousQueryBus::ask(new getPeopleLikeQuery($article->getId())),
                "dislikeUsers" => SynchronousQueryBus::ask(new getPeopleDislikeQuery($article->getId())),
            );
        }

        $jsonOutput = json_encode(array("status" => "success", "article" => $articleFormate));
        $response->getBody()->write($jsonOutput);

        return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);
    }

    public function delete($request, ResponseInterface $response, array $args)
    {
        //On récupère l'id de l article à modifier 
        $idArticle = $args['id'];

        //On récupère l'id de l'utilisateur
        $id = $request->getAttribute("idUser");
        $role = $request->getAttribute("role");

        //On récupère la date de publication
        $articleExistant = SynchronousQueryBus::ask(new getOneQuery($idArticle));
        //Si l'article est vide
        if (empty($articleExistant)) {
            $response->getBody()->write(json_encode(array("status" => "failure", "message" => "Article not found")));
            return $response->withStatus(404)->withAddedHeader("Content-Type", "application/json");
        }
        if ($articleExistant[0]['idAuteur'] != $id && $role != "moderator") {
            $response->getBody()->write(json_encode(array("status" => "failure", "message" => "You can't delete this article")));
            return $response->withStatus(403)->withAddedHeader("Content-Type", "application/json");
        }

        //On supprime l'article
        SynchronousCommandBus::execute(new CommandDelete($idArticle));

        $jsonOutput = json_encode(array("status" => "success"));
        $response->getBody()->write($jsonOutput);

        return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);
    }

    public function like($request, ResponseInterface $response, array $args)
    {
        //On récupère l'id de l article à modifier 
        $idArticle = $args['id'];

        //On récupère l'id de l'utilisateur
        $id = $request->getAttribute("idUser");

        //On récupère l'article
        $articleExistant = SynchronousQueryBus::ask(new getOneQuery($idArticle));
        //Si l'article est vide
        if (empty($articleExistant)) {
            $response->getBody()->write(json_encode(array("status" => "failure", "message" => "Article not found")));
            return $response->withStatus(404)->withAddedHeader("Content-Type", "application/json");
        }


        if ($articleExistant[0]['idAuteur'] == $id) {
            $response->getBody()->write(json_encode(array("status" => "failure", "message" => "You can't like your own article")));
            return $response->withStatus(403)->withAddedHeader("Content-Type", "application/json");
        }

        //On like l'article
        SynchronousCommandBus::execute(new CommandLike($id, $idArticle));

        $jsonOutput = json_encode(array("status" => "success"));
        $response->getBody()->write($jsonOutput);

        return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);
    }

    public function dislike($request, ResponseInterface $response, array $args) {
         //On récupère l'id de l article à modifier 
         $idArticle = $args['id'];

         //On récupère l'id de l'utilisateur
         $id = $request->getAttribute("idUser");
 
         //On récupère l'article
         $articleExistant = SynchronousQueryBus::ask(new getOneQuery($idArticle));
         //Si l'article est vide
        if (empty($articleExistant)) {
            $response->getBody()->write(json_encode(array("status" => "failure", "message" => "Article not found")));
            return $response->withStatus(404)->withAddedHeader("Content-Type", "application/json");
        }
        if ($articleExistant[0]['idAuteur'] == $id) {
            $response->getBody()->write(json_encode(array("status" => "failure", "message" => "You can't dislike your own article")));
            return $response->withStatus(403)->withAddedHeader("Content-Type", "application/json");
        }
 
         //On like l'article
         SynchronousCommandBus::execute(new CommandDislike($id, $idArticle));
 
         $jsonOutput = json_encode(array("status" => "success"));
         $response->getBody()->write($jsonOutput);
 
         return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);
    }
}