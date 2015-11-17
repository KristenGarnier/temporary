<?php

namespace Finortho\Fritage\EchangeBundle\Services;


use Oneup\UploaderBundle\Event\PreUploadEvent;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class PreUploadUser
 *
 * Méthode permettant d'intercepter l'upload d'un fichier via le multiupload pour récupérer certains paramètres
 *
 * @package Finortho\Fritage\EchangeBundle\Services
 */
class PreUploadUser
{
    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Methode petmettant de mettre en session les paramètres envoyés
     *
     * @param PreUploadEvent $event
     */
    public function onUpload($event)
    {
        $req = $event->getRequest();
        $this->session->set('filename',$req->get('filename') );
        $this->session->set('quantite',$req->get('quantite') );
        $this->session->set('date',$req->get('day') );
        $this->session->set('method',$req->get('method') );
    }
}