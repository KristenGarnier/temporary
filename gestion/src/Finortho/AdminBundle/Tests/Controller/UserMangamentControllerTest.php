<?php

namespace Finortho\AdminBundle\Tests\Controller;

use Finortho\AdminBundle\Tests\Extended_WebTestCase as WebTestCase;
class UserMangamentControllerTest extends WebTestCase
{

    public function testShouldEraseUser(){
        $client = static::createClient();
        $this->adminConnect($client);

        $crawler = $client->request('GET', 'http://localhost:8000/admin/users');

        $crawler = $this->eraseUser($crawler, $client);

        $this->assertEquals(
            0,
            $crawler->filter('html:contains("test@test.fr")')->count()
        );
    }

    public function testShouldCreateUserPost(){
        $client = static::createClient();
        $this->adminConnect($client);

        $crawler = $client->request(
            'POST',
            'http://localhost:8000/admin/users/new',
            array(
                'username' => 'test',
                'email' => 'test@test.fr',
                'password' => 'test'
            )
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("test@test.fr")')->count()
        );

        //$this->eraseUser($crawler, $client);
    }

    public function testShouldFailCauseUsernameAlreadyTaken(){
        $client = static::createClient();


        $this->adminConnect($client);

        $client->request(
            'POST',
            'http://localhost:8000/admin/users/new',
            array(
                'username' => 'test',
                'email' => 'test@testdouble.fr',
                'password' => 'test'
            )
        );
        $this->assertEquals(
            500, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );


    }

    public function testShouldFailCauseEmailAlreadyTaken(){
        $client = static::createClient();

        $this->adminConnect($client);

        $client->request(
            'POST',
            'http://localhost:8000/admin/users/new',
            array(
                'username' => 'tester',
                'email' => 'test@test.fr',
                'password' => 'test'
            )
        );
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

        $this->assertEquals(
            0,
            $crawler->filter('html:contains("test@test.fr")')->count()
        );
    }

}
