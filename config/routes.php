<?php

use Agenda\Middlewares\SimpleMiddleware;
use Slim\App;
use Agenda\Controllers\TestController;
use Agenda\Controllers\ContactController;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


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

    })->add(new SimpleMiddleware());


    //==========================|Rotas de test|=====================================//
    $app->get('/test', [TestController::class, 'test'])->add(new SimpleMiddleware());
    $app->post('/test', [TestController::class, 'add']);
};