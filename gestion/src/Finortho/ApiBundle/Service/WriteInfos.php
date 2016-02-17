<?php

namespace Finortho\ApiBundle\Service;


use Carbon\Carbon;
use Finortho\Fritage\EchangeBundle\Entity\Commande;

class WriteInfos
{

    private $rootdir;

    public function __construct($root)
    {
        $this->rootdir = $root;
    }

    public function onCommand(Commande $commande)
    {
        foreach($commande->getStls() as $stl){
            $dir = $this->rootdir . '/../web/' . $stl->getUploadDir();
            $file = ($dir . '/infos.txt');
            setlocale(LC_TIME, 'fr_FR');
            $date = new Carbon($stl->getDate()->format('d-m-Y'));
            if(!file_exists($file)){
                touch($file);
                $this->writeInfos($file, $stl, $date);
            } else {
                $this->writeInfos($file, $stl, $date);
            }
        }
    }

    /**
     * @param $file
     * @param $stl
     * @param $date
     */
    private function writeInfos($file, $stl, $date)
    {
        $f = fopen($file, 'a');
        fwrite($f, "\r\n");
        fwrite($f, "Fichier : \r\n");
        fwrite($f, "\t");
        fwrite($f, $stl->getName() . '.stl' . "\r\n");
        fwrite($f, "\r\n");
        fwrite($f, "Date d'expédition : " . $date->formatLocalized('%A %d %B %Y'));
        fwrite($f, "\r\n\r\n");
        fwrite($f, "Axe vertical de la pièce  : " . $stl->getAxis());
        fwrite($f, "\r\n\r\n");
        fwrite($f, "Commentaire : \r\n");
        fwrite($f, "\t");
        fwrite($f, $stl->getCommentaire() . "\r\n");
        fwrite($f, "\r\n");
        fwrite($f, "Quantité : \r\n");
        fwrite($f, "\t");
        fwrite($f, $stl->getQuantite() . " pièces \r\n");
        fwrite($f, "\r\n");
        fwrite($f, " Traitements : \r\n");
        foreach ($stl->getPack()->getProperty() as $prop) {
            fwrite($f, "\t");
            fwrite($f, $prop->getName() . "\r\n");
        }
        fwrite($f, "\r\n");
        fwrite($f, "/////////////////////////////////////");
        fwrite($f, "\r\n");
        fclose($f);
    }

}