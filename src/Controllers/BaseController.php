<?php

namespace Agenda\Controllers;

use Psr\Http\Message\ResponseInterface as Response;


/**
 * Base para todos os controllers 
 */
abstract class BaseController implements ControllerInterface
{

    /**
     * Cria a resposta adequada, encodando os dados para json e inserindo no corpo da resposta.
     * Também passa o status code e o header Content-Type (apesar de configurar no middleware)
     * 
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $payload
     * @param int $status
     * @return Response
     */
    protected function jsonResponse(Response $response, array $payload = [], int $status = 200): Response
    {
        if($payload){
            $response->getBody()
                     ->write(json_encode($payload));
        }

        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($status);
    }
}
