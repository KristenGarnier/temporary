<?php

namespace Finortho\Fritage\EchangeBundle\Tests\Services;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Finortho\Fritage\EchangeBundle\Services\SessionHandler;


class SessionHandlerTest extends WebTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    public function testShouldAddUploadsToSession(){
        $session = new Session(new MockArraySessionStorage());

        //mocking the entity
        $stl = $this->getMock('Finortho\Fritage\EchangeBundle\Entity\Stl');
        $stl->expects($this->any())->method('getId')->will($this->returnValue(1));

        $sessionHandler = new SessionHandler($session, $this->em);
        $sessionHandler->setUploads($stl);

        $this->assertNotFalse($session->get('uploads'));
        $this->assertEquals($session->get('uploads')[0], 1);
    }

    public function testShouldAddUploadToUploadsInSessinon(){
        $session = new Session(new MockArraySessionStorage());

        //mocking the entity
        $stl = $this->getMock('Finortho\Fritage\EchangeBundle\Entity\Stl');
        $stl->expects($this->any())->method('getId')->will($this->returnValue(1));

        $sessionHandler = new SessionHandler($session, $this->em);
        $sessionHandler->setUploads($stl);
        $sessionHandler->setUploads($stl);

        $this->assertNotFalse($session->get('uploads'));
        $this->assertEquals($session->get('uploads')[1], 1);

    }

    public function testShouldGetAllUploadInSession(){

        $session = new Session(new MockArraySessionStorage());

        //mocking the entity
        $stl = $this->getMock('Finortho\Fritage\EchangeBundle\Entity\Stl');
        $stl->expects($this->exactly(2))->method('getId')->will($this->returnValue(1));

        //mocking the repository
        $stlRepository = $this
            ->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $stlRepository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($stl));

        // Last, mock the EntityManager to return the mock of the repository
        $entityManager = $this
            ->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($stlRepository));

        $sessionHandler = new SessionHandler($session, $entityManager);
        $sessionHandler->setUploads($stl);

        $return = $sessionHandler->getUploads();
        $this->assertEquals($return[0]->getId(), 1);
    }

    public function testThisShouldUnsetUploadWhenMultiple(){
        $session = new Session(new MockArraySessionStorage());

        //mocking the entity
        $stl = $this->getMock('Finortho\Fritage\EchangeBundle\Entity\Stl');
        $stl->expects($this->any())->method('getId')->will($this->returnValue(1));
        $stlBis = $this->getMock('Finortho\Fritage\EchangeBundle\Entity\Stl');
        $stlBis->expects($this->any())->method('getId')->will($this->returnValue(2));

        $sessionHandler = new SessionHandler($session, $this->em);
        $sessionHandler->setUploads($stl);
        $sessionHandler->setUploads($stlBis);
        $sessionHandler->unsetUpload(1);
        $this->assertEquals(count($session->all()),1);
    }

    public function testThisShouldDestroyUploadsWhenEmpty(){
        $session = new Session(new MockArraySessionStorage());

        //mocking the entity
        $stl = $this->getMock('Finortho\Fritage\EchangeBundle\Entity\Stl');
        $stl->expects($this->any())->method('getId')->will($this->returnValue(1));
        $stlBis = $this->getMock('Finortho\Fritage\EchangeBundle\Entity\Stl');
        $stlBis->expects($this->any())->method('getId')->will($this->returnValue(2));

        $sessionHandler = new SessionHandler($session, $this->em);
        $sessionHandler->setUploads($stl);
        $sessionHandler->unsetUpload(1);
        $this->assertNull($session->get('uploads'));
    }
}
