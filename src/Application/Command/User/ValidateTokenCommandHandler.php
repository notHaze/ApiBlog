<?php

namespace Application\Command\User;

use Application\Command\CommandHandlerInterface;
use Application\Command\CommandInterface;
use Domain\tokenJWT\tokenJWTImpl;
use Domain\tokenJWT\Exception\TokenNotValidException;
use Exception;

class ValidateTokenCommandHandler implements CommandHandlerInterface
{

    public function __construct(){}

    public function handle(CommandInterface $command) {
        if (!$command instanceof ValidateTokenCommand) {
            throw new Exception("ValidateTokenCommandHandler can only handle ValidateTokenCommand");
        }

        $token = $command->getToken();
        $tokenJWT = new tokenJWTImpl();
        if ($tokenJWT->validateToken($token) == false) {
            throw new TokenNotValidException("Token is not valid");
        }
    }
}