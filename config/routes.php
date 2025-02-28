<?php

use Slim\App;
use Agenda\Controllers\ContactController;


return function (App $app) 
{
     $app->get('/contacts', [ContactController::class, 'read']);
     $app->post('/contacts', [ContactController::class, 'create']);
};