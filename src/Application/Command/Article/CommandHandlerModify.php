<?php

namespace Application\Command\Article;

use Application\Command\CommandInterface;
use Application\Command\CommandHandlerInterface;
use Exception;
class CommandHandlerDelete implements  CommandHandlerInterface
{
    public function __construct(){}
    public function handle(CommandInterface $command)
    {
        if (!$command instanceof CommandDelete) {
            throw new Exception("CommandHandlerDelete can only handle CommandDelete");
        }
    }
}

?>
