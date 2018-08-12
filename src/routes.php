<?php

use Slim\Http\Request;
use Slim\Http\Response;
$container = $app->getContainer();

// Routes

$app->get('/', function(Request $request, Response $response) {
    return $response->withStatus(301)->withHeader('Location', '/docs/');
});

$app->post('/mail', function (Request $request, Response $response, array $args) {
    $this->logger->info("'/mail' route");
    $appSettings = $this->get('settings')['appSettings'];
    $secret = $appSettings['recaptcha_secret'];
    $recaptcha = new ReCaptcha\ReCaptcha($secret);
    $params = $request->getParsedBody();
    $requiredParams = array('subject', 'message', 'email');
    foreach ($requiredParams as $reqParam) {
        if (! array_key_exists($reqParam, $params) || strlen(trim($params[$reqParam])) < 2 ) {
            return $response->withStatus(400)->write("Parameter $reqParam missing");
        }
    }
    // $name = "$appSettings['name'] < $appSettings['email'] >";
    mail($appSettings['mailto'], $params['subject'], $params['message']);
    return $response->write("It works!");
})->add(new \App\Middleware\RateLimit($container, 3));

$app->options('/mail', function ($request, $response, $args) {
    return $response;
});

