<?php

use Slim\App;
use Agenda\Middlewares\JsonResponseMiddleware;

return function (App $app){
    $app->add(new JsonResponseMiddleware());
}; 