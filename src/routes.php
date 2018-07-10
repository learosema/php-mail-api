<?php

use Slim\Http\Request;
use Slim\Http\Response;
$container = $app->getContainer();

// Routes

$app->get('/', function(Request $request, Response $response) {
    return $response->withStatus(301)->withHeader('Location', '/docs/');
});

$app->get('/mail', function (Request $request, Response $response, array $args) {
    $this->logger->info("'/mail' route");
    $appSettings = $this->get('settings')['appSettings'];
    $secret = $appSettings['recaptcha_secret'];
    $recaptcha = new ReCaptcha\ReCaptcha($secret);

    return $response->write("It works!");
});

$app->options('/mail', function ($request, $response, $args) {
    return $response;
});
