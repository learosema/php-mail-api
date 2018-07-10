<?php

namespace App\Middleware;



class RateLimit
{

  const REQUESTS_PER_MINUTE = 2;

  private $db;
  private $logger;

  public function __construct($db, $logger) {
    $this->db = $db;
    $this->logger = $logger;
    $this->initDatabase();
  }

  public function __invoke($request, $response, $next) {    
    $ip = $_SERVER['REMOTE_ADDR'];
    try {
      $query = $this->db->prepare("SELECT COUNT(*) AS requests FROM `xrequests` WHERE `ip` = '$ip' AND `ts` >= datetime('now', '-1 minute')");
      $query->execute();
      $result = $query->fetch();
      if ($result) {
        if ($result['requests'] > self::REQUESTS_PER_MINUTE) {
          return $this->tooManyRequests($response);
        }
      }
      $this->db->exec("INSERT INTO `xrequests` (ip) VALUES ('$ip')");
    } catch (PDOException $ex) {
      $log->error($ex->getMessage());
    }
    $response = $next($request, $response);
    return $response;
  }

  protected function tooManyRequests($response) {
    $response->getBody()->write('Too many requests.');
    return $response->withStatus(429)->withHeader('RateLimit-Limit', self::REQUESTS_PER_MINUTE);
  }

  protected function initDatabase() {
    try {
      $this->db->exec("CREATE TABLE IF NOT EXISTS `xrequests` (
        `id` INTEGER PRIMARY KEY,
        `ip` varchar(45) NOT NULL DEFAULT '',
        `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
      )");
    } catch (PDOException $ex) {
      $this->logger->error($ex->getMessage());
    }
  }

}