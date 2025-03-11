<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;


require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

// Setando as dependencias com PHP Definitions 
$dependencies = require_once __DIR__ . '/../config/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

// Informando o DIcontainer e Iniciando o App 
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

// Definindo os Middlewares Globais
$middlewares = require_once __DIR__ . '/../config/middlewares.php';
$middlewares($app);

// Middleware de tratamento de erros
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Definindo Rotas
$routes = require_once __DIR__ . '/../config/routes.php';
$routes($app);

$app->run();