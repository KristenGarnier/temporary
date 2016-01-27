<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Finortho\Fritage\EchangeBundle\Entity\User;
use Headoo\HeadooMailjetBundle\Wrapper\MailjetWrapper;

/**
 * Class Email
 *
 * Service permettant d'envoyer un mail à l'administrateur
 *
 * @package Finortho\Fritage\EchangeBundle\Services
 */
class Email
{
    /**
     * @var MailjetWrapper $mailjet Injection du mailjet pour pouvoir envoyer des mails
     */
    private $mailjet;
    /**
     * @var string $from Adresse email d'ou envoyer les emails
     */
    private $from;
    /**
     * @var string $to Adresse a qui envoyer le mail
     */
    private $to; //frittage@finortho.com
    /**
     * @var string $host adresse du site web pour la redirectin vers le site dans le mail
     */
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
     * @param User $user Utilisateur qui a déclanché la commande
     * @param int  $commande id of the command
     * @return mixed
     */
    public function sendAdminNotification(User $user, $commande)
    {

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

    /**
     * Envoyer à l'administrateur le message qui a été posté sur la plateforme
     *
     * @param User $user Utilisateur qui a envoyé le message
     * @param string $message Message de l'utilisateur
     * @return mixed
     */
    public function sendAdminNotificationMessage(User $user, $message)
    {

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

    /**
     * Génération des templates de mail selon les paramètres envoyés
     *
     * Tout dépend du paramètre message, si il est envoyé, alors on renvoie le
     * template message. Sinon on renvoi le paramètre de commande
     *
     * @param string      $username nom de l'utilisateur
     * @param string      $email email de l'utilisateur
     * @param string      $host adresse du site web
     * @param int         $commande id de la commande utilisateur
     * @param null|string $message Message de l'utilisateur
     * @return string Template du message
     */
    private function getTemplate($username, $email, $host, $commande, $message = null)
    {
        if ($message) {
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
