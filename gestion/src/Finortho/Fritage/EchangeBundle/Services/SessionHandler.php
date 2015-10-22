<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Doctrine\ORM\EntityManager;
use Finortho\Fritage\EchangeBundle\Entity\Stl;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionHandler
{
    private $session;
    private $em;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function setUploads(Stl $stl_file)
    {

        if (array_key_exists('uploads', $this->session->all())) {
            $up = $this->session->get('uploads');
            array_push($up, $stl_file->getId());
            $this->session->set('uploads', $up);
        } else {
            $this->session->set('uploads', array($stl_file->getId()));
        }

    }

    public function getUploads()
    {
        $uploads = array();
        if (array_key_exists('uploads', $this->session->all())) {
            foreach ($this->session->get('uploads') as $upload) {
                array_push($uploads, $this->em->getRepository('FinorthoFritageEchangeBundle:Stl')->find($upload));
            }
        }

        return $uploads;
    }

    public function unsetUpload($id)
    {
        if (array_key_exists('uploads', $this->session->all())) {
            $uploads = $this->session->get('uploads');
            $key = array_search(intval($id), $uploads);
            if (is_numeric($key)) {
                unset($uploads[$key]);
                $this->session->set('uploads', $uploads);
                var_dump($uploads);
            }
        }
    }
}