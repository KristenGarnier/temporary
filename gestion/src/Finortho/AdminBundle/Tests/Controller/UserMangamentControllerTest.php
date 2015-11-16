<?php

namespace Finortho\AdminBundle\Tests\Controller;

use Finortho\AdminBundle\Tests\Extended_WebTestCase as WebTestCase;
class UserMangamentControllerTest extends WebTestCase
{

    public function testShouldCreateUserByForm(){
        $client = static::createClient();

        $crawler = $this->Createuser($client);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("test@test.fr")')->count()
        );
    }

    public function testShouldEraseUser(){
        $client = static::createClient();
        $this->adminConnect($client);

        $crawler = $client->request('GET', 'http://localhost:8000/admin/users');

        $crawler = $this->eraseUser($crawler, $client);
        $client->followRedirect();

        $this->assertEquals(
            0,
            $crawler->filter('html:contains("test@test.fr")')->count()
        );
    }

    public function testShouldCreateUserPost(){
        $client = static::createClient();
        $this->adminConnect($client);

        $client->request(
            'POST',
            'http://localhost:8000/admin/users/new',
            array(
                'username' => 'test',
                'email' => 'test@test.fr',
                'password' => 'test'
            )
        );

        $crawler = $client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("test@test.fr")')->count()
        );

        //$this->eraseUser($crawler, $client);
    }

    public function testShouldFailCauseUsernameAlreadyTaken(){
        $client = static::createClient();


        $this->Createuser($client);
        $this->assertEquals(
            500, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );


    }

    public function testShouldEraseUser2(){
        $client = static::createClient();
        $this->adminConnect($client);

        $crawler = $client->request('GET', 'http://localhost:8000/admin/users');

        $crawler = $this->eraseUser($crawler, $client);
        $client->followRedirect();

        $this->assertEquals(
            0,
            $crawler->filter('html:contains("test@test.fr")')->count()
        );
    }

}
