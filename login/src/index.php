<?php

require __DIR__ . '/../vendor/autoload.php';

use Controllers\LoginController;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\NotFoundException;
use Application\Command\Login\LoginCommandHandler;
use Infrastructure\Database\Database;
use Infrastructure\SynchronousCommandBus;
use Infrastructure\SynchronousQueryBus;
use Application\Query\Login\FindIdentityQueryHandler;
use Domain\Repository\LoginRepositoryImpl;

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->setBasePath("/api/login/");

$loginRepository = new LoginRepositoryImpl();

SynchronousCommandBus::register(Application\Command\Login\LoginCommand::class, new LoginCommandHandler($loginRepository));
SynchronousQueryBus::register(Application\Query\Login\FindIdentityQuery::class, new FindIdentityQueryHandler($loginRepository));



$app->post('', [LoginController::class, 'login']);

try {
    $app->run();     
} catch (Exception $e) {    
  // We display a error message
  die( json_encode(array("status" => "failed", "message" => "This action is not allowed"))); 
}