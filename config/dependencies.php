<?php

use Agenda\Middlewares\RequireApiKeyMiddleware;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;


return function (ContainerBuilder $containerBuilder)
{
    $containerBuilder->addDefinitions([
        "db.dsn" => 'sqlite:' . __DIR__ . '/../banco.sqlite',
        
        PDO::class => function (ContainerInterface $c) {
            $dsn = $c->get("db.dsn");
            
            return new PDO($dsn);
        },

        ResponseFactoryInterface::class => function (ContainerInterface $c){
            return $c->get(ResponseFactory::class);
        }
    ]);
};