<?php

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;


return function (ContainerBuilder $containerBuilder)
{
     $containerBuilder->addDefinitions([
          "db.dsn" => 'sqlite:' . __DIR__ . '/../banco.sqlite',

          PDO::class => function (ContainerInterface $c) {
                    $dsn = $c->get('db.dsn');
                    
                    return new PDO($dsn);
               }
     ]);
};