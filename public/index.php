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
use Application\Query\User\GetRoleQueryHandler;
use Application\Query\User\GetTokenQueryHandler;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;



$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);


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
    $app->get('/{id}', [ArticleController::class, 'get']);
    $app->post('', [ArticleController::class, 'create']);
    $app->patch('/{id}', [ArticleController::class, 'update']);
    $app->delete('/{id}', [ArticleController::class, 'delete']);
    $app->get('', [ArticleController::class, 'getAll']);
})->addMiddleware(new ValidateTokenMiddleware($app->getResponseFactory()));

try {
    $app->run();     
} catch (Exception $e) {    
  // We display a error message
  die( json_encode(array("status" => "failed", "message" => "This action is not allowed"))); 
}