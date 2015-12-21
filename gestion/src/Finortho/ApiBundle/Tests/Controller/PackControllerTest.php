<?php

namespace Finortho\ApiBundle\Tests\Controller;

use Finortho\ApiBundle\Tests\WebCaseApi;
use Doctrine\ORM\EntityManager;

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

    public function testPOSTShouldReturnAnErrorPackUser()
    {
        $client = static::createClient();

        $client->request('POST', '/api/packs');
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 403);
        $this->assertContains('error', $response->getContent());
    }

    public function testShouldAddaMessage(){
        $client = static::createClient();

        $client->request('POST', '/api/packs',
        [
            "pack" => "clinique",
            "name" => "test",
            "property" => [
                "hola",
                "quetal"
            ]
        ],
            [],
            [
            'HTTP_user' => "1"
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());

        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $pack = $em->getRepository('FinorthoFritageEchangeBundle:PackItem')->findOneByName('test');
        $hola = $em->getRepository('FinorthoFritageEchangeBundle:PackProperty')->findOneByName('hola');
        $quetal = $em->getRepository('FinorthoFritageEchangeBundle:PackProperty')->findOneByName('quetal');
        $em->remove($pack);
        $em->remove($hola);
        $em->remove($quetal);
        $em->flush();


    }


}
