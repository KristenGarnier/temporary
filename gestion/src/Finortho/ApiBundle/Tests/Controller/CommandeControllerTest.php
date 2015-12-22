<?php

namespace Finortho\ApiBundle\Tests\Controller;

use Finortho\ApiBundle\Tests\WebCaseApi;

class CommandeControllerTest extends WebCaseApi
{
    public function testShouldAttachPack(){
        $client = static::createClient();

        $client->request('POST', '/api/commandes', [0 => ['id' => 3, 'packId' => 1]], [], [
            'HTTP_user' => "1"
        ]);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testShouldFailAttachPackWrongPackId(){
        $client = static::createClient();

        $client->request('POST', '/api/commandes', [0 => ['id' => 3, 'packId' => 0]], [], [
            'HTTP_user' => "1"
        ]);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertContains('error', $response->getContent());
    }

    public function testShouldFailAttachPackWrongId(){
        $client = static::createClient();

        $client->request('POST', '/api/commandes', [0 => ['id' => 0, 'packId' => 18]], [], [
            'HTTP_user' => "1"
        ]);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertContains('error', $response->getContent());
    }

    public function testPOSTShouldReturnAnErrorMessageUser()
    {
        $client = static::createClient();

        $client->request('POST', '/api/commandes');
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 403);
        $this->assertContains('error', $response->getContent());
    }


}