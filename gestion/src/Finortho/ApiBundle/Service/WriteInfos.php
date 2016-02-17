<?php

namespace Finortho\ApiBundle\Service;

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

            if(!file_exists($file)){
                touch($file);
                $this->writeInfos($file, $stl);
            } else {
                $this->writeInfos($file, $stl);
            }
        }
    }

    /**
     * @param $file
     * @param $stl
     */
    private function writeInfos($file, $stl)
    {
        $f = fopen($file, 'a');
        fwrite($f, "\r\n");
        fwrite($f, "Fichier : \r\n");
        fwrite($f, "\t");
        fwrite($f, $stl->getName() . '.stl' . "\r\n");
        fwrite($f, "\r\n\r\n");
        fwrite($f, "Axe vertical de la pièce : " . $stl->getAxis());
        fwrite($f, "\r\n\r\n");
        fwrite($f, "Commentaire : \r\n");
        fwrite($f, "\t");
        fwrite($f, $stl->getCommentaire() . "\r\n");
        fwrite($f, "\r\n");
        fwrite($f, "Quantité : \r\n");
        fwrite($f, "\t");
        if($stl->getQuantite() <= 1){
            fwrite($f, $stl->getQuantite() . " pièce \r\n");
        } else {
            fwrite($f, $stl->getQuantite() . " pièces \r\n");
        }
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