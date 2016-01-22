<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
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

    public function __construct($mailjet)
    {
        $this->mailjet = $mailjet;
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
            "from" => "finortho@gmail.com",
            //"to" => "frittage@finortho.com",
            "to" => "garnier.kristen@icloud.com",
            "subject" => "Nouveaux fichiers importés sur le serveur",
            "html" => $template
        );

        return $this->mailjet->sendEmail($params);
    }
}