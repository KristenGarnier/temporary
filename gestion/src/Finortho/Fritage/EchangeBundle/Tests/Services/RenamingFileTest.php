<?php

namespace Finortho\Fritage\EchangeBundle\Tests\Services;

use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Finortho\Fritage\EchangeBundle\Services\RenamingFile;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Finortho\Fritage\EchangeBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;

class RenamingFileTest extends WebTestCase
{

    public function testShouldRetieveGoodName(){

        $user = new User();
        $user->setUsername('kristen');

        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken(
            new UsernamePasswordToken($user, 'test', 'fritage', array('ROLE_USER'))
        );

        $nom = 'test.stl';
        $username = $tokenStorage->getToken()->getUser();
        $qt = 12;
        $date = new \DateTime();
        $date = $date->format('ymd');

        $namer = new RenamingFile();
        $result = $namer->rename($nom, $username, $qt, false, 0);
        $this->assertEquals($result, sprintf('%s-%s-X%s-%s-%s', $date, 'EXP', $qt, $username->getUsername(), $nom));

    }

    public function testShouldReturnDateInGoodFormat(){
        $session = new Session(new MockArraySessionStorage());
        $user = new User();
        $user->setUsername('kristen');

        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken(
            new UsernamePasswordToken($user, 'test', 'fritage', array('ROLE_USER'))
        );

        $namer = new RenamingFile();

        $date = $namer->weekDate(5);

        $carbon = Carbon::createFromFormat('ymd', $date);
        $this->assertFalse($carbon->isWeekend());
    }

}