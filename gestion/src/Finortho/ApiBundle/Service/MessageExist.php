<?php

namespace Finortho\ApiBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class MessageExist
 * @package Finortho\ApiBundle\Service
 */
class MessageExist
{
    /**
     * Doctrine reference
     * @var ObjectManager
     */
    private $orm;

    public function __construct(ObjectManager $orm)
    {
        $this->orm = $orm;
    }

    /**
     * Check if the user already send the message ( so there is no spam )
     * @param $content
     * @param $id
     * @return bool
     */
    public function check($content, $id)
    {
        return empty(
            $this->orm->getRepository('FinorthoFritageEchangeBundle:Message')->findBy(['content' => $content, 'user' => $id])
        );
    }
}