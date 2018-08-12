<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$container = $app->getContainer();
$appSettings = $container->get('settings')['appSettings'];

$app->add(new \App\Middleware\Cors($container));
