<?php

namespace Agenda\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class RequireApiKeyMiddleware implements MiddlewareInterface
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function process(Request $request, Handler $handler): Response
    {
        if (!$request->hasHeader('X-API-Key')) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write(json_encode("API Key is Missing"));

            return $response;
        }

        $savedKey = $_ENV['API_SAVED_USER_KEY']; // Hash da chave publica "salva no banco" 

        $userKey = $request->getHeaderLine('X-API-Key');
        $hashedKey = hash_hmac('sha256', $userKey, $_ENV['API_SECRET_KEY']); // Cria um token com base na chave

        if ($hashedKey !== $savedKey){
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write(json_encode("API Key is not authorized"));

            return $response->withStatus(401);
        }

        return $handler->handle($request);
    }
}
