<?php

namespace Finortho\ApiBundle\Tests\Controller;

use Finortho\ApiBundle\Tests\WebCaseApi;

class PackControllerTest extends WebCaseApi
{
    public function testShouldSendPackUserJsonArray()
    {
        $client = static::createClient();

        $client->request('GET', '/api/packs/user', array(), array(), array(
            'HTTP_user' => 1
        ));
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
        $this->assertTrue(is_array(json_decode($response->getContent())));
    }

    public function testShouldReturnAnErrorPackUser()
    {
        $client = static::createClient();

        $client->request('GET', '/api/packs/user');
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 403);
        $this->assertContains('error', $response->getContent());
    }

    public function testShouldSayNotFound(){
        $client = static::createClient();

        $client->request('GET', '/api/packs/user', array(), array(), array(
            'HTTP_user' => "als"
        ));
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 404);
        $this->assertContains('User not found', $response->getContent());
    }

    public function testShouldSendPackJsonArray()
    {
        $client = static::createClient();

        $client->request('GET', '/api/packs', array(), array(), array(
            'HTTP_user' => 1
        ));
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
        $this->assertTrue(is_array(json_decode($response->getContent())));
        var_dump($response->getContent());
    }

    public function testShouldReturnAnErrorPack()
    {
        $client = static::createClient();

        $client->request('GET', '/api/packs');
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 403);
        $this->assertContains('error', $response->getContent());
    }

    public function testShouldSayNotFoundPack(){
        $client = static::createClient();

        $client->request('GET', '/api/packs', array(), array(), array(
            'HTTP_user' => "als"
        ));
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 404);
        $this->assertContains('User not found', $response->getContent());
    }


}
