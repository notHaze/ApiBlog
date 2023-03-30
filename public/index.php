<?php

require __DIR__ . '/../vendor/autoload.php';

use Controllers\LoginController;
use Controllers\ArticleController;
use Slim\Factory\AppFactory;
use Application\Command\User\LoginCommandHandler;
use Infrastructure\SynchronousCommandBus;
use Infrastructure\SynchronousQueryBus;
use Application\Query\User\FindIdentityQueryHandler;
use Domain\Repository\LoginRepositoryImpl;
use Application\Command\User\ValidateTokenCommandHandler;
use Application\Middleware\ValidateTokenMiddleware;
use Application\Middleware\VerifyRoleMiddleware;
use Application\Query\User\GetRoleQueryHandler;
use Application\Query\User\GetTokenQueryHandler;
use Application\Query\User\getUsernameHandler;
use Domain\Repository\ArticleRepositoryImpl;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;



$app = AppFactory::create();




$app->setBasePath("/apiBlog");

$loginRepository = new LoginRepositoryImpl();
$articleRepository = new ArticleRepositoryImpl();

// Register the command and query handlers

//LOGIN
SynchronousCommandBus::register(Application\Command\User\LoginCommand::class, new LoginCommandHandler($loginRepository));
SynchronousCommandBus::register(Application\Command\User\ValidateTokenCommand::class, new ValidateTokenCommandHandler());

SynchronousQueryBus::register(Application\Query\User\FindIdentityQuery::class, new FindIdentityQueryHandler($loginRepository));
SynchronousQueryBus::register(Application\Query\User\GetRoleQuery::class, new GetRoleQueryHandler());
SynchronousQueryBus::register(Application\Query\User\GetTokenQuery::class, new GetTokenQueryHandler());
SynchronousQueryBus::register(Application\Query\User\getUsernameQuery::class, new getUsernameHandler($loginRepository));

//ARTICLE
SynchronousCommandBus::register(\Application\Command\Article\CommandDelete::class, new \Application\Command\Article\CommandDeleteHandler($articleRepository));
SynchronousCommandBus::register(\Application\Command\Article\CommandLike::class, new \Application\Command\Article\CommandLikeHandler($articleRepository));
SynchronousCommandBus::register(\Application\Command\Article\CommandDislike::class, new \Application\Command\Article\CommandDislikeHandler($articleRepository));
SynchronousCommandBus::register(\Application\Command\Article\CommandModify::class, new \Application\Command\Article\CommandModifyHandler($articleRepository));
SynchronousCommandBus::register(\Application\Command\Article\CommandNeutre::class, new \Application\Command\Article\CommandNeutreHandler($articleRepository));

SynchronousQueryBus::register(\Application\Query\Article\ConsultQuery::class, new \Application\Query\Article\ConsultQueryHandler($articleRepository));
SynchronousQueryBus::register(\Application\Query\Article\CountNbOfDislikeQuery::class, new \Application\Query\Article\CountNbOfDislikeQueryHandler($articleRepository));
SynchronousQueryBus::register(\Application\Query\Article\CountNbOfLikeQuery::class, new \Application\Query\Article\CountNbOfLikeQueryHandler($articleRepository));
SynchronousQueryBus::register(\Application\Query\Article\getOwnQuery::class, new \Application\Query\Article\getOwnQueryHandler($articleRepository));
SynchronousQueryBus::register(\Application\Query\Article\PublishQuery::class, new \Application\Query\Article\publishQueryHandler($articleRepository));
SynchronousQueryBus::register(\Application\Query\Article\getOneQuery::class, new \Application\Query\Article\getOneHandler($articleRepository));
SynchronousQueryBus::register(\Application\Query\Article\getPeopleLikeQuery::class, new \Application\Query\Article\getPeopleLikeHandler($articleRepository));
SynchronousQueryBus::register(\Application\Query\Article\getPeopleDislikeQuery::class, new \Application\Query\Article\getPeopleDislikeHandler($articleRepository));




$app->post('/login', [LoginController::class, 'login']); //Login route

$app->group('/article', function ($app) {
    $app->get('/own',[ArticleController::class, 'get'])->add( new VerifyRoleMiddleware($app->getResponseFactory()));        //Get all articles of the user publisher
    $app->post('', [ArticleController::class, 'create'])->add(new VerifyRoleMiddleware($app->getResponseFactory()));        //Publier un article
    $app->patch('/{id}', [ArticleController::class, 'update'])->add(new VerifyRoleMiddleware($app->getResponseFactory()));  //Modifier un article ou liker/disliker
    $app->delete('/{id}', [ArticleController::class, 'delete'])->add(new VerifyRoleMiddleware($app->getResponseFactory())); //Supprimer un article
    $app->patch('/like/{id}', [ArticleController::class, 'like'])->add(new VerifyRoleMiddleware($app->getResponseFactory())); //Liker un article
    $app->patch('/dislike/{id}', [ArticleController::class, 'dislike'])->add(new VerifyRoleMiddleware($app->getResponseFactory())); //Disliker un article
    $app->patch('/neutre/{id}', [ArticleController::class, 'neutre'])->add(new VerifyRoleMiddleware($app->getResponseFactory())); //Neutre un article
    $app->get('', [ArticleController::class, 'getAll']);                                                                    //Get all articles for moderator and publisher and reader
})->addMiddleware(new ValidateTokenMiddleware($app->getResponseFactory()));//Verification of the validity of the token

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Set the Not Found Handler
$errorMiddleware->setErrorHandler(\Slim\Exception\HttpNotFoundException::class,
    function (\Psr\Http\Message\ServerRequestInterface $request, \Throwable $exception, bool $displayErrorDetails) {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(array("status" => "failure", "error" => "404 method not implemented")));
        return $response->withStatus(404)->withAddedHeader("Content-Type", "application/json");
    });

try {
    $app->run();     
} catch (Exception $e) {    
  // We display a error message
  die( json_encode(array("status" => "failed", "message" => "This action is not allowed"))); 
}