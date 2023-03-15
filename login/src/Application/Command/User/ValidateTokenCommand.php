<?php

namespace Application\Command\User;

use Application\Command\CommandInterface;

class ValidateTokenCommand implements CommandInterface
{
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}

?>