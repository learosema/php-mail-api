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
  $this->renderer->render($response, 'mail.phtml', $args);
});

$app->post('/mail', function (Request $request, Response $response, array $args) {
  $this->logger->info("'POST /mail' route");
  $appSettings = $this->get('settings')['appSettings'];
  $secret = $appSettings['recaptcha_secret'];
  
  $params = $request->getParsedBody();
  if ($params == null) {
    $params = array();
  }
  $requiredParams = array('subject', 'message', 'email', 'g-recaptcha-response');
  
  foreach ($requiredParams as $reqParam) {
    if (! array_key_exists($reqParam, $params) || strlen(trim($params[$reqParam])) < 2 ) {
      return $response->withStatus(422)->write("Parameter $reqParam missing");
    }
  }
  $recaptcha = new ReCaptcha\ReCaptcha($secret);
  $gRecaptchaResponse = $params["g-recaptcha-response"];
  $resp = $recaptcha
    ->setExpectedHostname('localhost')
    ->verify($gRecaptchaResponse, $remoteIp);
  if ($resp->isSuccess()) {
    // Verified!
    // mail($appSettings['mailto'], $params['subject'], $params['message']);
    return $response->write("It works!");
  } else {
    $errors = $resp->getErrorCodes();
  }
})->add(new \App\Middleware\RateLimit($container, 3));

$app->options('/mail', function ($request, $response, $args) {
    return $response;
});
