<?php

namespace Finortho\ApiBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebCaseApi extends  WebTestCase
{

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