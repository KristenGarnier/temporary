<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class FinorthoNamer
{
    protected $context;

    public function __construct(TokenStorage $context)
    {
        $this->context = $context;
    }
    public function name(UploadedFile $file)
    {
        $user = $this->context->getToken()->getUser();
        $date = new \DateTime();
        $date->modify('+1 week');
        $formated = $date->format('ymd');


        return sprintf('%s/%s/%s-%s-%s-%s', $user->getUsername(), $formated ,$formated, $user->getUsername() , uniqid(), $file->getClientOriginalName());
    }
}