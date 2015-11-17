<?php

namespace Finortho\AdminBundle\Tests\Controller;

use Finortho\AdminBundle\Tests\Extended_WebTestCase as WebTestCase;

class SecureAdminAreaTest extends WebTestCase
{
    public function testShouldRedirectToLogin()
    {
        $client = static::createClient();

        $client->request('GET', 'http://localhost:8000/admin');

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost:8000/login'));
    }

    public function testShouldObtainAnForbiddenError(){
        $client = static::createClient();

        $crawler = $client->request('GET', 'http://localhost:8000/login');


        $this->userConnect($crawler, $client);

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost:8000/'));

        $client->request('GET', 'http://localhost:8000/admin');
        $this->assertTrue($client->getResponse()->isForbidden());

    }

    public function testShouldGiveAccessToAdmin(){
        $client = static::createClient();

        $crawler = $client->request('GET', 'http://localhost:8000/login');

        $this->adminConnect($client);

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost:8000/'));

        $client->request('GET', 'http://localhost:8000/admin');
        $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());

    }


}
