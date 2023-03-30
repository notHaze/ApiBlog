<?php

namespace Application\Middleware;

use Application\Command\User\ValidateTokenCommand;
use Application\Query\User\GetRoleQuery;
use Application\Query\User\GetTokenQuery;
use Domain\tokenJWT\Exception\TokenNotFoundException;
use Infrastructure\SynchronousCommandBus;
use Infrastructure\SynchronousQueryBus;
use Domain\tokenJWT\Exception\TokenNotValidException;
use Firebase\JWT\ExpiredException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidateTokenMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $token = SynchronousQueryBus::ask(new GetTokenQuery());
            SynchronousCommandBus::execute(new ValidateTokenCommand($token));
        } catch (TokenNotFoundException $e) {
            $request = $request->withAttribute("idUser", -1)->withAttribute("role", "reader");
            // Keep processing middleware queue as normal
            return $handler->handle($request);
        } catch (TokenNotValidException $e) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write(json_encode(["status" => "failed", "message" => $e->getMessage()]));
            return $response->withAddedHeader("Content-Type", "application/json")->withStatus(401);
        } catch (ExpiredException $e) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write(json_encode(["status" => "failed", "message" => $e->getMessage()]));
            return $response->withAddedHeader("Content-Type", "application/json")->withStatus(401);
        }
        
        $payload  = SynchronousQueryBus::ask(new GetRoleQuery($token));
        $request = $request->withAttribute("idUser", $payload->id)->withAttribute("role", $payload->role);
        // Keep processing middleware queue as normal
        return $handler->handle($request);
    }
}