<?php

namespace Finortho\Fritage\EchangeBundle\Tests\Services;

use Finortho\Fritage\EchangeBundle\Services\PreUploadUser;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class PreUploadUserTest extends \PHPUnit_Framework_TestCase
{

    public function testShloudExtractDataFromRequest(){
        $session = new Session(new MockArraySessionStorage());

        $req = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $req->expects($this->exactly(4))->method('get');

        $preUploadEvent = $this
            ->getMockBuilder('Oneup\UploaderBundle\Event\PreUploadEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $preUploadEvent->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($req));

        $preup = new PreUploadUser($session);
        $preup->onUpload($preUploadEvent);

    }

}
