<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Finortho\Fritage\EchangeBundle\Entity\User;
/**
 * Class Email
 *
 * Service permettant d'envoyer un mail à l'administrateur
 *
 * @package Finortho\Fritage\EchangeBundle\Services
 */
class Email
{
    private $mailjet;
    private $from;
    private $to; //frittage@finortho.com

    public function __construct($mailjet, $from, $to)
    {
        $this->mailjet = $mailjet;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Envoi d'une notification mail à l'administrateur
     *
     * @param User $user
     * @return {*}
     */
    public function sendAdminNotification($user){

        $template = vsprintf("<html>L'utilisateur : %s a déposé des fichiers sur la plateforme de stockage.
            <br>
            <br>
            Email de l'utilisateur : %s
            <br>
            <a href='http://localhost:8000/admin'> Consulter les fichiers </a>
            </html>",
            [$user->getUsername(), $user->getEmail()]
        );

        $params = array(
            "method" => "POST",
            "from" => $this->from,
            "to" => $this->to,
            "subject" => "Nouveaux fichiers importés sur le serveur",
            "html" => $template
        );

        return $this->mailjet->sendEmail($params);
    }

    public function sendAdminNotificationMessage($user, $message){

        $template = vsprintf("
            <html>L'utilisateur : %s à envoyé un message sur la plateforme d'aide
            <br>
            <br>
            Message :
            <br>
            %s
            <br>
            <br>
            Email de l'utilisateur : %s
            <br>
            <a href='http://localhost:8000/admin'>Plateforme d'administration</a>
            </html>",
            [$user->getUsername(), $message, $user->getEmail()]
        );

        $params = array(
            "method" => "POST",
            "from" => $this->from,
            "to" => $this->to,
            "subject" => "Nouveau message d'aide de l'utilisateur",
            "html" => $template
        );

        return $this->mailjet->sendEmail($params);
    }
}