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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;



$app = AppFactory::create();




$app->setBasePath("/api/ApiBlog");

$loginRepository = new LoginRepositoryImpl();

// Register the command and query handlers
SynchronousCommandBus::register(Application\Command\User\LoginCommand::class, new LoginCommandHandler($loginRepository));
SynchronousCommandBus::register(Application\Command\User\ValidateTokenCommand::class, new ValidateTokenCommandHandler());

SynchronousQueryBus::register(Application\Query\User\FindIdentityQuery::class, new FindIdentityQueryHandler($loginRepository));
SynchronousQueryBus::register(Application\Query\User\GetRoleQuery::class, new GetRoleQueryHandler());
SynchronousQueryBus::register(Application\Query\User\GetTokenQuery::class, new GetTokenQueryHandler());


$app->post('/login', [LoginController::class, 'login']);

$app->group('/article', function ($app) {
    $app->get('/own',new VerifyRoleMiddleware($app->getResponseFactory(), "publisher"), [ArticleController::class, 'get']); //Get all articles of the user publisher
    $app->post('', new VerifyRoleMiddleware($app->getResponseFactory(), "publisher"), [ArticleController::class, 'create']); //Publier un article
    $app->patch('/{id}',new VerifyRoleMiddleware($app->getResponseFactory(), "publisher"), [ArticleController::class, 'update']); //Modifier un article ou liker/disliker
    $app->delete('/{id}',new VerifyRoleMiddleware($app->getResponseFactory(), "moderator", "publisher"), [ArticleController::class, 'delete']); //Supprimer un article
    $app->get('', [ArticleController::class, 'getAll']); //Get all articles for moderator and publisher and reader
})->addMiddleware(new ValidateTokenMiddleware($app->getResponseFactory()));

$app->addErrorMiddleware(true, true, true);

try {
    $app->run();     
} catch (Exception $e) {    
  // We display a error message
  die( json_encode(array("status" => "failed", "message" => "This action is not allowed"))); 
}