<?php

namespace Agenda\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseController
{
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
