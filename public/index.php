<?php

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\Factory\AppFactory;


require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$containerBuilder = new ContainerBuilder();

// Setando as dependencias com PHP Definitions 
$dependencies = require_once __DIR__ . '/../config/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

// Informando o DIcontainer e Iniciando o App 
AppFactory::setContainer($container);
$app = AppFactory::create();

// Parse json, form data E xml
$app->addBodyParsingMiddleware();

// Definindo os Middlewares Globais
$middlewares = require_once __DIR__ . '/../config/middlewares.php';
$middlewares($app);

// Middleware de tratamento de erros
$errorMiddleware = $app->addErrorMiddleware(false, false, false);

// Definindo Rotas
$routes = require_once __DIR__ . '/../config/routes.php';
$routes($app);

$app->run();
