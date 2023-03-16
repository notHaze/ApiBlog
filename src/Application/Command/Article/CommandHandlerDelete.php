<?php

namespace Application\Command\Article;
use Application\Command\CommandHandlerInterface;
use Application\Command\CommandInterface;
use Exception;

class commandHandlerDelete implements CommandHandlerInterface
{
    public function __construct()
    {
    }

    public function handle(CommandInterface $command)
    {
        if (!$command instanceof commandDelete) {
            throw new Exception("commandHandlerDelete can only handle commandDelete");
        }

    }
}