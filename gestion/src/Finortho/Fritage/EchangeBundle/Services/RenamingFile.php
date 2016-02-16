<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Carbon\Carbon;
use Finortho\Fritage\EchangeBundle\Entity\User;


/* Class RenamingFile
 *
 * Classe permettant de changer le d'une pièce arrivant par l'upload
 *
 * @package Finortho\Fritage\EchangeBundle\Services
 */

class RenamingFile
{

    /**
     * @param         $name
     * @param         $user
     * @param         $quantite
     * @param boolean $filter
     * @param int     $day
     * @param string  $method
     * @return string
     */
    public function rename($name, User $user, $quantite, $filter = false, $day = 5, $method = 'EXP')
    {

        $date = $this->weekDate($day);
        if ($filter) {
            $name = $this->extractName($name);
        }

        return sprintf('%s-%s-X%s-%s-%s', $date, $method, $quantite, $user->getUsername(), $name);
    }

    /**
     * @param int     $quantite
     * @param int     $day
     * @param string  $method
     * @return string
     */
    public function name($quantite, $day = 5, $method = 'EXP')
    {

        $date = $this->weekDate($day);

        return sprintf('%s-%s-X%s', $date, $method, $quantite);
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
    private function dateChecker(Carbon $date)
    {
        return $date->isWeekend();
    }

    /**
     * Extracting name of the full name provided
     *
     * @param $name
     *
     * @return string
     */
    private function extractName($name)
    {
        $exploded = explode('-', $name);
        return end($exploded);
    }

}
