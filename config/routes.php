<?php

use Slim\App;
use Agenda\Controllers\ContactController;


return function (App $app) 
{
     $app->get('/', [ContactController::class, 'read']);
     $app->post('/', [ContactController::class, 'create']);
};