<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Finortho\Fritage\EchangeBundle\Entity\User;
use Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\AclBundle\Entity\Car;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class FinorthoNamer
{
    protected $context;

    public function __construct(TokenStorage $context, Session $session, \Swift_Mailer $mailer, EntityManager $em)
    {
        $this->context = $context;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->em = $em;
    }

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
     * @param $date
     * @return mixed
     */
    private function dateChecker(Carbon $date)
    {
        return $date->isWeekend();
    }
}