<?php
namespace App\Middleware;

class Cors {
  
  private $allowed_origins;
  
  public function __construct($container) {
    $appSettings = $container->get('settings')['appSettings'];
    $origins = $appSettings['allowed_origins'];
    $this->allowed_origins = $origins;
  }

  public function __invoke($request, $response, $next) {
    $response = $next($request, $response);
    $origin = implode('', $request->getHeader('Origin'));
    if (! in_array($origin, $this->allowed_origins)) {
      return $response;
    }
    return $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
  }
}