<?php
/**
 * Created by PhpStorm.
 * User: PBW_12
 * Date: 03/06/2015
 * Time: 14:28
 */

namespace Finortho\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Finortho\ApiBundle\Service\ValidateDataReact;
use Finortho\Fritage\EchangeBundle\Entity\Commande;
use Carbon\Carbon;


/**
 * Class for handling database call and logic form controller
 *
 * Class CommandeHandler
 * @package Finortho\ApiBundle\Handler
 */
class CommandeHandler
{

    /**
     * Doctrine entity manager
     * @var ObjectManager
     */
    private $om;
    /**
     * Name of the entity
     * @var $entityClass String
     */
    private $entityClass;
    /**
     * Rempository of the current entity
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;


    /**
     * Service pour valider les données envoyées de l'app react
     * @var $validateReactData array
     */
    private $validateReactData;


    public function __construct(ObjectManager $om, $entityClass, ValidateDataReact $dataReact)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->validateReactData = $dataReact;

    }

    /**
     * Attach a pack to a product
     *
     * @param $data
     * @param $id
     */
    public function attachProduct($data, $id)
    {
        $commande = new Commande();
        $commande->setDate(Carbon::now()->toDateString());
        $commande->setUser($this->om->getRepository('FinorthoFritageEchangeBundle:User')->find($id));
        foreach($data as $item){
            $this->validateReactData->check($data);
            $stl = $this->repository->find($item['id']);
            $stl->setPack($this->om->getRepository('FinorthoFritageEchangeBundle:PackItem')->find($item['packId']));
            $stl->setCommande($commande);
            $commande->addStl($stl);
        }
        $this->om->persist($commande);
        $this->om->flush();

        return $commande->getId();
    }

}
