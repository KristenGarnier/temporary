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
     * @param $text
     * @param $id
     * @return bool
     */
    public function check($text, $id)
    {
        return empty(
            $this->orm->getRepository('FinorthoFritageEchangeBundle:Message')->findBy(['text' => $text, 'user' => $id])
        );
    }
}