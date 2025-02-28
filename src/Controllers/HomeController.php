<?php

namespace Test\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class HomeController
{
    public function test(Request $request, Response $response): Response
    {
        var_dump('teste');

        return $response;
    }
}