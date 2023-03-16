<?php
namespace Application\Command\Article;
use Application\Command\CommandInterface;
use Application\Command\CommandHandlerInterface;
use Exception;
class CommandHandlerDislike implements CommandHandlerInterface
{
    public function __construct()
    {
    }
    public function handle(CommandInterface $command)
    {
        if (!$command instanceof CommandDislike) {
            throw new Exception("CommandHandlerDislike can only handle CommandDislike");
        }
    }
}
?>