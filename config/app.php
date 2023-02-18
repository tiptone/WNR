<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Tiptone\Mvc\Controller\IndexController;
use Tiptone\Mvc\Controller\ReviewsController;
use Tiptone\Mvc\Service\ReviewService;
use Tiptone\Mvc\Database\Db;

return [
    'app_name' => 'WNR',
    'routes' => [
        'home' => [
            'path' => '/',
            'default' => 'index',
            'controller' => IndexController::class,
        ],
        'reviews' => [
            'path' => '/reviews',
            'default' => 'index',
            'controller' => ReviewsController::class,
        ],
    ],
    'container' => [
        LoggerInterface::class => DI\factory(function() {
            $logger = new Logger('wnr');
            $logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO, false));
            $logger->pushHandler(new StreamHandler('php://stderr', Logger::ERROR, false));

            return $logger;
        }),
        Environment::class => DI\factory(function() {
            $loader = new FilesystemLoader(__DIR__ . '/../templates');
            $twig = new Environment($loader);

            return $twig;
        }),
        Db::class => DI\factory(function() {
            $db = new Db(__DIR__ . '/../data/reviews.db');
            $db->enableExceptions(true);

            return $db;
        }),
        ReviewService::class => function(ContainerInterface $container) {
            $db = $container->get(Db::class);
            $service = new ReviewService($db);

            return $service;
        },
        IndexController::class => function(ContainerInterface $container) {
            $controller = new IndexController();
            $controller->setLogger($container->get(LoggerInterface::class));

            return $controller;
        },
        ReviewsController::class => function(ContainerInterface $container) {
            $service = $container->get(ReviewService::class);
            $controller = new ReviewsController($service);
            $controller->setLogger($container->get(LoggerInterface::class));

            return $controller;
        }
    ],
];
