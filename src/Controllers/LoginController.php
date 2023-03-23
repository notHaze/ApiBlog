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
use Domain\User\Exception\UserNotFoundException;
use Infrastructure\SynchronousCommandBus;
use Infrastructure\SynchronousQueryBus;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController {

    public function login(RequestInterface $request, ResponseInterface $response, array $args) {
        $input = $request->getBody()->getContents();
        $input = json_decode($input, true);
        if (!isset($input['username']) || !isset($input['password']) || empty($input['username']) || empty($input['password'])) {
            $jsonOutput = json_encode(array("status" => "failure", "message" => "Missing username or password"));
            $response->getBody()->write($jsonOutput);
            return $response->withAddedHeader("Content-Type", "application/json")->withStatus(400);
        }
        $username = $input['username'];
        $password = $input['password'];

        
        try {
            $loginCommand = new LoginCommand($username, $password);
            SynchronousCommandBus::execute($loginCommand);
        } catch (UserNotFoundException $e) {
            $jsonOutput = json_encode(array("status" => "failure", "message" => "User not found, Invalid credentiels"));
            $response->getBody()->write($jsonOutput);
            return $response->withAddedHeader("Content-Type", "application/json")->withStatus(400);
        } catch (Exception $e) {
            $jsonOutput = json_encode(array("status" => "failure", "message" => "User not found, Invalid credentiels"));
            $response->getBody()->write($jsonOutput);
            return $response->withAddedHeader("Content-Type", "application/json")->withStatus(500);
        }
        //Si l'execution arrive ici c'est que l'authentification a reussi car aucune Exception UserNotFoundException n'a ete levee
        //On peut donc recuperer le role de l'utilisateur
        $findIdentityQuery = new FindIdentityQuery($username, $password);
        $tabIdentity = SynchronousQueryBus::ask($findIdentityQuery);

        //On peut maintenant construire le token jwt
        $jwtFactory = new tokenJWTImpl();
        $token = $jwtFactory->createToken($username, $password, $tabIdentity['idUser'], $tabIdentity['role']);

        $jsonOutput = json_encode(array("status" => "success", "token" => $token));
        $response->getBody()->write($jsonOutput);

        return $response->withAddedHeader("Content-Type", "application/json")->withStatus(200);


    }
}


?>