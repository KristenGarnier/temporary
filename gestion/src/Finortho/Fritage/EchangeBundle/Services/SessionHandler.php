<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Finortho\Fritage\EchangeBundle\Entity\Stl;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class SessionHandler
 *
 * Ensemble d'helpers spour la session
 *
 * @package Finortho\Fritage\EchangeBundle\Services
 */
class SessionHandler
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param Session       $session
     * @param EntityManager $em
     */
    public function __construct(Session $session, ObjectManager $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    /**
     * Method permettant d'ajouter un fichier au tableau des fichiers de session
     *
     * @param Stl $stl_file
     */
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

    /**
     * Methode permettant d'avoir tous les uploads de la session
     *
     * @param int $id id de l'utilisateur
     * @return array
     */
    public function getUploads($id)
    {
        return $this->em->getRepository('FinorthoFritageEchangeBundle:Stl')->findBy(array('utilisateur' => $id, 'pack' => null));
    }

    /**
     * Methode permettant de suprimmer un élement dans les uploads dans la session
     *
     * @param int $id id de la pièce
     */
    public function unsetUpload($id)
    {
        if (array_key_exists('uploads', $this->session->all())) {
            $uploads = $this->session->get('uploads');
            $key = array_search(intval($id), $uploads);
            if (is_numeric($key)) {
                unset($uploads[$key]);
                if (empty($uploads)) {
                    $this->session->remove('uploads');
                } else {
                    $this->session->set('uploads', $uploads);
                }
            }
        }
    }
}
