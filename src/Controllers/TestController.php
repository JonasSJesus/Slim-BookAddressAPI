<?php

namespace Agenda\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class TestController
{
    public function test(Request $request, Response $response): Response
    {
        $data = [
            "id" => "1",
            "name" => "User Test",
            "email" => "test@email.com"
        ];

        $jsonEncoded = json_encode($data);
        
        $response->getBody()->write($jsonEncoded);
        return $response;
    }

    public function add(Request $request, Response $response): Response
    {
        var_dump($request->getParsedBody());
        
        return $response;
    }
}