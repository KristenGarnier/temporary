<?php

namespace Finortho\Fritage\EchangeBundle\Services;


use Oneup\UploaderBundle\Event\PreUploadEvent;
use Symfony\Component\HttpFoundation\Session\Session;

class PreUploadUser
{
    public function __construct($doctrine, Session $session)
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
    }

    public function onUpload(PreUploadEvent $event)
    {
        $req = $event->getRequest();
        $this->session->set('filename',$req->get('filename') );
        $this->session->set('quantite',$req->get('quantite') );
        $this->session->set('date',$req->get('day') );
        $this->session->set('method',$req->get('method') );
    }
}