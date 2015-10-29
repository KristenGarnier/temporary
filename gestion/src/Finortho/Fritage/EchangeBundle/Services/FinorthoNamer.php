<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Carbon\Carbon;
use Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\AclBundle\Entity\Car;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class FinorthoNamer
{
    protected $context;

    public function __construct(TokenStorage $context, Session $session)
    {
        $this->context = $context;
        $this->session = $session;
    }

    public function name(UploadedFile $file)
    {
        $user = $this->context->getToken()->getUser();

        $date = $this->weekDate($this->session->get('date'));
        $name = $this->session->get('filename');
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