<?php

namespace Finortho\Fritage\EchangeBundle\Tests\Controller;

use Finortho\AdminBundle\Tests\Extended_WebTestCase;

class PackageInterfaceTest extends Extended_WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        $this->adminConnect($client);

        // Create a new entry in the database
        $crawler = $client->request('GET', 'http://localhost:8000/admin/exigences');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /exigences");
        //$this->assertContains( $crawler, 'Gestion des exigences');
    }
}
