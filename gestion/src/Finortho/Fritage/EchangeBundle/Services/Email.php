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
    private $host;

    public function __construct($mailjet, $from, $to, $server)
    {
        $this->mailjet = $mailjet;
        $this->from = $from;
        $this->to = $to;
        $this->host = $server;
    }

    /**
     * Envoi d'une notification mail à l'administrateur
     *
     * @param User $user
     * @param int $commande id of the command
     * @return {*}
     */
    public function sendAdminNotification($user, $commande){

        $template = $this->getTemplate(
            $user->getUsername(),
            $user->getEmail(),
            $this->host,
            $commande
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

        $template = $this->getTemplate(
            $user->getUsername(),
            $user->getEmail(),
            $this->host,
            null,
            $message
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

    private function getTemplate($username, $email, $host, $commande, $message = null){
        if($message){
            return vsprintf("
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
                [$username, $message, $email, $host]
            );
        }

        return vsprintf("<html>L'utilisateur : %s a déposé des fichiers sur la plateforme de stockage.
            <br>
            <br>
            Email de l'utilisateur : %s
            <br>
            <a href='http://%s/admin/commande/%s'> Consulter les fichiers </a>
            </html>",
            [$username, $email, $host, $commande]
        );
    }
}