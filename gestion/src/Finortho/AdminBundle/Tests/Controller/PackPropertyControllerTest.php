<?php

namespace Finortho\Fritage\EchangeBundle\Tests\Controller;

use Finortho\AdminBundle\Tests\Extended_WebTestCase;

class PackPropertyControllerTest extends Extended_WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        $this->adminConnect($client);

        // Create a new entry in the database
        $crawler = $client->request('GET', 'http://localhost:8000/admin/property/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /packproperty/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'finortho_fritage_echangebundle_packproperty[name]'  => 'Test',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'finortho_fritage_echangebundle_packproperty[name]'  => 'Foo',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());

        $client->request('GET', 'http://localhost:8000/admin/property/0/show');
        $this->assertEquals($client->getResponse()->getStatusCode(), 404);

        $client->request('POST', 'http://localhost:8000/admin/property/0/update');
        $this->assertEquals($client->getResponse()->getStatusCode(), 404);

        $client->request('GET', 'http://localhost:8000/admin/property/0/edit');
        $this->assertEquals($client->getResponse()->getStatusCode(), 404);
    }
}
