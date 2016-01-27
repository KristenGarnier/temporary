<?php

namespace Finortho\Fritage\EchangeBundle\Tests\Services;

use Finortho\Fritage\EchangeBundle\Entity\User;
use Finortho\Fritage\EchangeBundle\Services\Email;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmailTest extends WebTestCase
{

    private $from;
    private $to;
    private $host;
    private $email;
    private $user;

    public function setUp()
    {
        $this->from = 'from@test.fr';
        $this->to = 'garnier.kristen@icloud.com';
        $this->host = 'server';

        $mailjet = $this
            ->getMockBuilder('\Headoo\HeadooMailjetBundle\Wrapper\MailjetWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        $mailjet->expects($this->once())
            ->method('__call')
            ->will($this->returnCallback(function ($_, $args) {
                return $args;
            }));

        $this->email = new Email($mailjet, $this->from, $this->to, $this->host);

        $user = new User();
        $user->setUsername('testman');
        $user->setEmail('testman@hello.fr');
        $this->user = $user;
    }

    public function testShouldSendEmailMessageWithGoodTemplate()
    {
        $message = 'message de test';

        $expectMessage = vsprintf("
            <html>L'utilisateur : %s a envoyé un message sur la plateforme d'aide
            <br>
            <br>
            Message :
            <br>
            %s
            <br>
            <br>
            Email de l'utilisateur : %s
            <br>
            <a href='http://%s/admin'>Plateforme d'administration</a>
            </html>",
            [$this->user->getUsername(), $message, $this->user->getEmail(), $this->host]
        );

        $email = $this->email;
        $result = $email->sendAdminNotificationMessage($this->user, $message);

        $this->assertEquals($expectMessage, $result[0]['html']);
    }

    public function testShouldSendEmailWithGoodTemplate()
    {
        $commande = 14;

        $expectCommande = vsprintf("<html>L'utilisateur : %s a déposé des fichiers sur la plateforme de stockage.
            <br>
            <br>
            Email de l'utilisateur : %s
            <br>
            <a href='http://%s/admin/commande/%s'> Consulter les fichiers </a>
            </html>",
            [$this->user->getUsername(), $this->user->getEmail(), $this->host, $commande]
        );

        $email = $this->email;
        $result = $email->sendAdminNotification($this->user, $commande);

        $this->assertEquals($expectCommande, $result[0]['html']);

    }
}
