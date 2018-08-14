<?php
// TODO's:
// Consider to do Unit Tests instead?
//
// mock the mail function, maybe via
// https://github.com/php-mock/php-mock 
//


namespace Tests\Functional;

class HomepageTest extends BaseTestCase
{
  /**
   * Test that the / route redirects to the documentation in /docs/
   */
  public function testGetIndex() {
    $response = $this->runApp('GET', '/');
    $location = implode("", $response->getHeader('Location'));
    $this->assertEquals('/docs/', $location);
    $this->assertEquals(301, $response->getStatusCode());
  }

  /**
   * Test that the Options Preflight sends the appropiate CORS headers
   */
  public function testOptionsPreflight() {
    $response = $this->runApp('OPTIONS', '/mail', null, true);
    $headers = $response->getHeaders();
    $this->assertArrayHasKey('Access-Control-Allow-Origin', $headers);
    $this->assertArrayHasKey('Access-Control-Allow-Headers', $headers);
    $this->assertArrayHasKey('Access-Control-Allow-Methods', $headers);
    $this->assertEquals(200, $response->getStatusCode());
  }

  /**
   * The /mail route without parameters should return 422
   */
  public function testPostMail() {
    $response = $this->runApp('POST', '/mail', null, false);
    $this->assertEquals(422, $response->getStatusCode());
  }

  /**
   * Test that the index route won't accept a post request
   */
  public function testPostHomepageNotAllowed()
  {
      $response = $this->runApp('POST', '/', ['test']);

      $this->assertEquals(405, $response->getStatusCode());
      $this->assertContains('Method not allowed', (string)$response->getBody());
  }
}