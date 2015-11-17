<?php

namespace Finortho\Fritage\EchangeBundle\Tests\Services;

use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Finortho\Fritage\EchangeBundle\Services\FinorthoNamer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Finortho\Fritage\EchangeBundle\Entity\User;

class FinorthoNamerTest extends WebTestCase
{

    public function testShouldRetieveGoodName(){

        $session = new Session(new MockArraySessionStorage());
        $session->set('filename', 'hellojeanjank');
        $session->set('quantite', '2');
        $session->set('method', 'EXP');
        $session->set('date', '0');
        $date = new \DateTime();
        $date = $date->format('ymd');

        $user = new User();
        $user->setUsername('kristen');

        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken(
            new UsernamePasswordToken($user, 'test', 'fritage', array('ROLE_USER'))
        );

        $file = new UploadedFile('src/Finortho/Fritage/EchangeBundle/Tests/Services/FinorthoNamerTest.php', 'helo');

        $namer = new FinorthoNamer($tokenStorage, $session);
        $name = $name = str_replace(' ', '_', $session->get('filename'));

        $this->assertEquals($namer->name($file), sprintf('%s/%s/%s-%s-X%s-%s-%s', $user->getUsername(), $date, $date, $session->get('method'), $session->get('quantite'), $user->getUsername(), $name));

    }

    public function testShouldReturnDateInGoodFormat(){
        $session = new Session(new MockArraySessionStorage());
        $user = new User();
        $user->setUsername('kristen');

        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken(
            new UsernamePasswordToken($user, 'test', 'fritage', array('ROLE_USER'))
        );

        $namer = new FinorthoNamer($tokenStorage, $session);

        $date = $namer->weekDate(5);

        $carbon = Carbon::createFromFormat('ymd', $date);
        $this->assertFalse($carbon->isWeekend());
    }

}