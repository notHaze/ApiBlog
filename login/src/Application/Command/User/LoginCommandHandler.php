<?php

namespace Application\Command\User;

use Application\Command\CommandHandlerInterface;
use Application\Command\CommandInterface;
use Domain\Repository\LoginRepositoryInterface;
use Exception;
use Domain\User\Login;

class LoginCommandHandler implements CommandHandlerInterface
{
    private LoginRepositoryInterface $loginRepository;

    public function __construct(LoginRepositoryInterface $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    public function handle(CommandInterface $command) {
        if (!$command instanceof LoginCommand) {
            throw new Exception("CreatePostHandler can only handle CreatePostCommand");
        }

        $login = new Login($command->getUsername(), $command->getPassword());
        $this->loginRepository->login($login);
    }
}