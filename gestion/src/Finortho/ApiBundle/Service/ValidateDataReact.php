<?php

namespace Finortho\ApiBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ValidateDataReact
 * @package Finortho\ApiBundle\Service
 */
class ValidateDataReact
{
    /**
     * Reference to the doctrine API
     * @var ObjectManager
     */
    private $orm;

    public function __construct(ObjectManager $orm)
    {
        $this->orm = $orm;
    }

    /**
     * Check if the user exists
     * @param array $data
     * @return bool
     */
    public function check($data)
    {
        foreach ($data as $item) {
            $product = $this->orm->getRepository('FinorthoFritageEchangeBundle:Stl')->find($item['id']);
            $pack = $this->orm->getRepository('FinorthoFritageEchangeBundle:PackItem')->find($item['packId']);
            if (empty($product) || empty($pack)) {
                throw new NotFoundHttpException('The pack or product does not exist');
            }
        }
    }
}