<?php

namespace Controllers;
use Application\Command\User\LoginCommand;
use Application\Command\User\LoginCommandHandler;
use Application\Query\User\FindIdentityQuery;
use Application\Query\User\FindIdentityQueryHandler;
use Domain\User\LoginRepositoryImpl;
use Database\Database;
use Exception;
use Domain\tokenJWT\tokenJWTImpl;
use Infrastructure\SynchronousCommandBus;
use Infrastructure\SynchronousQueryBus;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController {

    public function login(RequestInterface $request, ResponseInterface $response, array $args) {
        $input = $request->getBody()->getContents();
        $input = json_decode($input, true);
        if (!isset($input['username']) || !isset($input['password']) || empty($input['username']) || empty($input['password']))
            throw new Exception("Username or password not set");
        $username = $input['username'];
        $password = $input['password'];

        $loginCommand = new LoginCommand($username, $password);
        SynchronousCommandBus::execute($loginCommand);
        //Si l'execution arrive ici c'est que l'authentification a reussi car aucune Exception UserNotFoundException n'a ete levee
        //On peut donc recuperer le role de l'utilisateur
        $findIdentityQuery = new FindIdentityQuery($username, $password);
        $tabIdentity = SynchronousQueryBus::ask($findIdentityQuery);

        //On peut maintenant construire le token jwt
        $jwtFactory = new tokenJWTImpl();
        $token = $jwtFactory->createToken($username, $password, $tabIdentity['id'], $tabIdentity['role']);

        $jsonOutput = json_encode(array("status" => "success", "token" => $token));
        $response->getBody()->write($jsonOutput);

        return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);


    }
}


?>