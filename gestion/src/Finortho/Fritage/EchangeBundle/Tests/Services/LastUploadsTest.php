<?php

namespace Finortho\Fritage\EchangeBundle\Tests\Services;

use Finortho\Fritage\EchangeBundle\Services\LastUploads;
use Finortho\Fritage\EchangeBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


class LastUploadsTest extends \PHPUnit_Framework_TestCase
{

    public function testShloudGiveBackAnArray(){
        $user = new User();
        $user->setUsername('kristen');

        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken(
            new UsernamePasswordToken($user, 'test', 'fritage', array('ROLE_USER'))
        );

        $repository = $this
            ->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $repository->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue(array(
                array(
                    'id' => 1,
                    'url' => 'test',
                    'name' => 'ect..'
                )
            )));

        // Last, mock the EntityManager to return the mock of the repository
        $entityManager = $this
            ->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);

        $last = new LastUploads($entityManager, $tokenStorage);
        $this->assertTrue(is_array($last->get()));

    }

}
