<?php

namespace Finortho\ApiBundle\Tests\Controller;

use Finortho\ApiBundle\Tests\WebCaseApi;

class ProduitControllerTest extends WebCaseApi
{
    public function testShouldSendJsonArray()
    {
        $client = static::createClient();

        $client->request('GET', '/api/produits', array(), array(), array(
            'HTTP_user' => 1
        ));
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
        $this->assertTrue(is_array(json_decode($response->getContent())));
    }

    public function testShouldReturnAnError()
    {
        $client = static::createClient();

        $client->request('GET', '/api/produits');
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 403);
        $this->assertContains('error', $response->getContent());
    }

    public function testShouldSayNotFound(){
        $client = static::createClient();

        $client->request('GET', '/api/produits', array(), array(), array(
            'HTTP_user' => "als"
        ));
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 404);
        $this->assertContains('User not found', $response->getContent());
    }

}
