<?php
namespace Finortho\AdminBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Extended_WebTestCase extends WebTestCase
{
    /**
     * @param $client
     * @return mixed
     */
    protected function Createuser($client)
    {
        list($crawler, $form) = $this->adminConnect($client);

        $crawler = $client->request('GET', 'http://localhost:8000/admin/users/new');

        $form = $crawler->selectButton('submit')->form();

        $form['username'] = 'test';
        $form['email'] = 'test@test.fr';
        $form['password'] = 'test';

        $client->submit($form);
        return $crawler;
    }

    /**
     * @param $client
     * @return array
     */
    protected function adminConnect($client)
    {
        $crawler = $client->request('GET', 'http://localhost:8000/login');
        $form = $crawler->selectButton('_submit')->form();

        $form['_username'] = 'admin';
        $form['_password'] = 'admin';

        $client->submit($form);
        return array($crawler, $form);
    }

    /**
     * @param $crawler
     * @param $client
     */
    protected function userConnect($crawler, $client)
    {
        $form = $crawler->selectButton('_submit')->form();

        $form['_username'] = 'user';
        $form['_password'] = 'user';

        $client->submit($form);
    }

    /**
     * @param $crawler
     * @param $client
     * @return mixed
     */
    protected function eraseUser($crawler, $client)
    {
        $erase = $crawler->filter('.container a')->last()->link();
        $crawler = $client->click($erase);
        return $crawler;
    }

}