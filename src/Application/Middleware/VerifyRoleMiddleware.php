<?php

namespace Application\Middleware;


use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VerifyRoleMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var Array
     */
    private $roleExpected;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Array $roleExpected
     */
    public function __construct(ResponseFactoryInterface $responseFactory, ...$roleExpected)
    {
        $this->responseFactory = $responseFactory;
        $this->roleExpected = $roleExpected;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //Get role from request (Passed from previous middleware)

        $roleUser = $request->getAttribute("role");

        //If role is not the expected one, return error
        if (in_array($roleUser, $this->roleExpected) == false) {
            /*$response = $this->responseFactory->createResponse();
            $response->getBody()->write(json_encode(["status" => "failed", "message" => "Invalid permission, you cant do this action with the " . $roleUser . " role"]));
            return $response->withAddedHeader("Content-Type", "application/json")->withStatus(401);*/
            throw new \Exception("Invalid permission, you cant do this action with the " . $roleUser . " role");
        }
        
        // Keep processing middleware queue as normal
        return $handler->handle($request);
    }
}