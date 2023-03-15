<?php
namespace Infrastructure;

use Exception;
use Application\Command\CommandInterface;
use Application\Command\CommandBusInterface;
use Application\Command\CommandHandlerInterface;

class SynchronousCommandBus implements CommandBusInterface
{
    /** @var CommandHandlerInterface[] */
    private static $handlers = [];

    public static function execute(CommandInterface $command) {
        $commandName = get_class($command);

        if (!array_key_exists($commandName, SynchronousCommandBus::$handlers)) {
            throw new Exception("No handler registered for command $commandName");
        }

        return SynchronousCommandBus::$handlers[$commandName]->handle($command);
    }

    public static function register($commandName, CommandHandlerInterface $handler)
    {
        SynchronousCommandBus::$handlers[$commandName] = $handler;

    }

}

?>