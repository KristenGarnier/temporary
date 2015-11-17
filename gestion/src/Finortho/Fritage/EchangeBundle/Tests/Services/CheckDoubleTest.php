<?php

namespace Finortho\Fritage\EchangeBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Finortho\Fritage\EchangeBundle\Services\CheckDouble;

class CheckDoubleTest extends WebTestCase
{

    public function testShouldNotRaiseAnException(){

        $stlRepository = $this
            ->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $stlRepository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(null));

        // Last, mock the EntityManager to return the mock of the repository
        $entityManager = $this
            ->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($stlRepository));

        $double = new CheckDouble($entityManager);
        $this->assertTrue($double->check('kristen', 'finortho'));
    }

    public function testShouldRaiseAnException(){
        //mocking the entity
        $stl = $this->getMock('Finortho\Fritage\EchangeBundle\Entity\Stl');
        $stl->expects($this->any())->method('getId')->will($this->returnValue(1));

        //mocking the repository
        $stlRepository = $this
            ->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $stlRepository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($stl));

        // Last, mock the EntityManager to return the mock of the repository
        $entityManager = $this
            ->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($stlRepository));

        $double = new CheckDouble($entityManager);
        $this->setExpectedException('\Exception');
        $double->check('kristen', 'finortho');
    }
}
