<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Agenda\Controllers\ContactController;


require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

// Setando as dependencias com PHP Definitions 
$dependencies = require_once __DIR__ . '/../config/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

// Informando o DIcontainer e Iniciando o App 
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();

// Definindo Rotas
$routes = require_once __DIR__ . '/../config/routes.php';
$routes($app);

$app->run();