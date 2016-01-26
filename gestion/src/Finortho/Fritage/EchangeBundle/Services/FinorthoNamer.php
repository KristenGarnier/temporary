<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Carbon\Carbon;
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
     */
    public function __construct(TokenStorage $context, Session $session)
    {
        $this->context = $context;
        $this->session = $session;
    }

    /**
     * Méthode permettant de changer le nom d'un fichier envoyé
     *
     * @return string
     */
    public function name()
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
    public function weekDate($day)
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
    public function dateChecker(Carbon $date)
    {
        return $date->isWeekend();
    }
}