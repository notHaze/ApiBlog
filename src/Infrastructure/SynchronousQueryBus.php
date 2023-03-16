<?php
namespace Infrastructure;

use Application\Query\QueryBusInterface;
use Application\Query\QueryHandlerInterface;
use Application\Query\QueryInterface;
use Exception;


class SynchronousQueryBus implements QueryBusInterface
{
    /** @var QueryHandlerInterface[] */
    private static $handlers = [];

    public static function ask(QueryInterface $query) {
        $queryName = get_class($query);

        if (!array_key_exists($queryName, SynchronousQueryBus::$handlers)) {
            throw new Exception("No handler registered for query $queryName");
        }

        return SynchronousQueryBus::$handlers[$queryName]->ask($query);
    }

    public static function register($queryName, QueryHandlerInterface $handler)
    {
        SynchronousQueryBus::$handlers[$queryName] = $handler;

    }

}

?>