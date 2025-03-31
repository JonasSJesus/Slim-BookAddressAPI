<?php

use Agenda\Middlewares\RequireApiKeyMiddleware;
use Slim\App;
use Agenda\Controllers\Contacts\ContactController;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return function(App $app) 
{
    $app->group('/contacts', function (Group $group) {
        // GET
        $group->get('', [ContactController::class, 'read']);
        $group->get('/{id}', [ContactController::class, 'readOne']);
        
        // POST
        $group->post('', [ContactController::class, 'create']);
        
        // PUT
        $group->put('/{id}', [ContactController::class, 'update']);
        
        // DELETE
        $group->delete('/{id}', [ContactController::class, 'delete']);
        
    })->add(RequireApiKeyMiddleware::class);

    // OPTIONS Coringa (aceito em qualquer endpoint)
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response->withHeader('Access-Control-Allow-Origin', '*')
                        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, X-API-Key')
                        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                        ->withStatus(204); 
    });
};