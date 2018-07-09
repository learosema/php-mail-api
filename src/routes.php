<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
/*
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
}); */

$app->post('/mail', function (Request $request, Response $response, array $args) {
    $this->logger->info("'/mail' route");
    $recaptcha = new ReCaptcha\ReCaptcha("todo_where_to_put_my_secret_key");
    return $response->write("It works!");
});