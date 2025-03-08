<?php

namespace Agenda\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class SimpleMiddleware implements MiddlewareInterface
{
    public function process(Request $request, Handler $handler): Response
    {
        echo "antes da rota";
        $response = $handler->handle($request);
        echo "depois da rota";

        return $response;
    }
}
