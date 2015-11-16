<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Finortho\Fritage\EchangeBundle\Entity\User;
use Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\AclBundle\Entity\Car;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class FinorthoNamer
 *
 * Classe permettant de changer le d'une pièce arrivant par le multiupload
 *
 * @package Finortho\Fritage\EchangeBundle\Services
 */
class FinorthoNamer
{
    /**
     * @var TokenStorage
     */
    protected $context;

    /**
     * @param TokenStorage  $context
     * @param Session       $session
     * @param \Swift_Mailer $mailer
     * @param EntityManager $em
     */
    public function __construct(TokenStorage $context, Session $session, \Swift_Mailer $mailer, EntityManager $em)
    {
        $this->context = $context;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->em = $em;
    }

    /**
     * Méthode permettant de changer le nom d'un fichier envoyé
     *
     * @param UploadedFile $file
     * @return string
     */
    public function name(UploadedFile $file)
    {
        $user = $this->context->getToken()->getUser();

        $date = $this->weekDate($this->session->get('date'));
        $name = $this->session->get('filename');
        $name = str_replace(' ', '_', $name);
        $quantite = $this->session->get('quantite');
        $method = $this->session->get('method');

        return sprintf('%s/%s/%s-%s-X%s-%s-%s', $user->getUsername(), $date, $date, $method, $quantite, $user->getUsername(), $name);
    }


    /**
     * Méthode permettant de définir le jour de livraison, en excluant les weekends
     *
     * @param $day
     * @return string
     */
    private function weekDate($day)
    {
        $date = Carbon::now(new \DateTimeZone('Europe/Paris'));
        $weekend = false;
        for ($i = 0; $i < $day; $i++) {
            $date->addDay();
            if ($this->dateChecker($date)) {
                $weekend = true;
            }
        }

        if ($weekend) {
            $date->addDays(2);
        }
        return $date->format('ymd');
    }

    /**
     * Méthode permettant de savoir si la date est un week end ou non
     *
     * @param $date
     * @return mixed
     */
    private function dateChecker(Carbon $date)
    {
        return $date->isWeekend();
    }
}