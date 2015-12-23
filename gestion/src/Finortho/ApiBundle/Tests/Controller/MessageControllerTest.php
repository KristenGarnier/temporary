<?php

namespace Finortho\ApiBundle\Tests\Controller;

use Finortho\ApiBundle\Tests\WebCaseApi;

class MessageControllerTest extends WebCaseApi
{
    public function testShouldSendMessageUserJsonArray()
    {
        $client = static::createClient();

        $client->request('GET', '/api/messages', array(), array(), array(
            'HTTP_user' => 1
        ));
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 200);
        $this->assertTrue(is_array(json_decode($response->getContent())));
    }

    public function testShouldReturnAnErrorMessageUser()
    {
        $client = static::createClient();

        $client->request('GET', '/api/messages');
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 403);
        $this->assertContains('error', $response->getContent());
    }

    public function testShouldSayNotFound(){
        $client = static::createClient();

        $client->request('GET', '/api/messages', array(), array(), array(
            'HTTP_user' => "als"
        ));
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 404);
        $this->assertContains('User not found', $response->getContent());
    }

    public function testShouldAddaMessage(){
        $client = static::createClient();

        $client->request('POST', '/api/messages', ["text" => "test", "date" => '12/12/12', 'time' => '12:12'], [], [
            'HTTP_user' => "1"
        ]);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testShouldFailAddingaMessage(){
        $client = static::createClient();

        $client->request('POST', '/api/messages', ["text" => "test"], [], [
            'HTTP_user' => "1"
        ]);
        $response = $client->getResponse();
        $this->assertEquals(409, $response->getStatusCode());
        $this->assertContains('error', $response->getContent());

        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $message = $em->getRepository('FinorthoFritageEchangeBundle:Message')->findOneByText('test');
        $em->remove($message);
        $em->flush();
    }

    public function testPOSTShouldReturnAnErrorMessageUser()
    {
        $client = static::createClient();

        $client->request('POST', '/api/messages');
        $response = $client->getResponse();

        $this->assertJsonResponse($response, 403);
        $this->assertContains('error', $response->getContent());
    }


}