<?php

use Slim\App;
use Agenda\Controllers\ContactController;


return function(App $app) 
{
    // GET
    $app->get('/[{id}]', [ContactController::class, 'read']);
    #$app->get('/{id}', [ContactController::class, 'readOne']);
    
    // POST
    $app->post('/', [ContactController::class, 'create']);
    
    // PUT
    $app->put('/{id}', [ContactController::class, 'update']);
    
    // DELETE
    $app->delete('/{id}', [ContactController::class, 'delete']);
};