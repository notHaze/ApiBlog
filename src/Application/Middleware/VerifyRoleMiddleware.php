<?php

namespace Application\Middleware;


use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Handlers\Strategies\RequestHandler;
use Slim\Psr7\Request;

class VerifyRoleMiddleware
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var Array
     */
    private $roleExpected = array("moderator", "publisher");

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Array $roleExpected
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {

        $this->responseFactory = $responseFactory;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        //Get role from request (Passed from previous middleware)
        $roleUser = $request->getAttribute("role");
        


        //If role is not the expected one, return error
        if (in_array($roleUser, $this->roleExpected) == false) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write(json_encode(["status" => "failed", "message" => "Invalid permission, you cant do this action with the " . $roleUser . " role"]));
            return $response->withAddedHeader("Content-Type", "application/json")->withStatus(403);
        }
        
        // Keep processing middleware queue as normal
        return $handler->handle($request);
    }
}