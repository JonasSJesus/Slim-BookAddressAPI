<?php

namespace Agenda\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class JsonResponseMiddleware implements MiddlewareInterface
{

    /**
     * Verifica se o Header "Content-Type": "application/json" está sendo passado corretamente
     * Se não estiver, define e retorna a resposta com os devidos headers
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return Response
     */
    public function process(Request $request, Handler $handler): Response
    {
        // Recupera a resposta do proximo middleware da fila
        $response = $handler->handle($request);


        // Verifica se "Content-Type": "application/json" esta no header
        if (!$response->hasHeader("Content-Type")){
            // Se não estiver, adiciona aqui
            $response = $response->withHeader("Content-Type", "application/json");
        }

        return $response;   
    }
}
