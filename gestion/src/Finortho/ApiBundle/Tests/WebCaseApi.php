<?php

namespace Finortho\ApiBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebCaseApi extends  WebTestCase
{

    protected function setUp()
    {
        parent::setUp();
        global $_SERVER;
        $_SERVER['HTTP_HOST'] = 'localhost:8000';
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

}