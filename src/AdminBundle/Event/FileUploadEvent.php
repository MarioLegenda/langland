<?php

namespace AdminBundle\Event;

use Doctrine\ORM\EntityManager;
use Library\FileUploader;
use Symfony\Component\EventDispatcher\Event;

class FileUploadEvent extends Event
{
    const NAME = 'admin.upload_file_event';

    /**
     * @var object $entity
     */
    private $entity;

    public function __construct($entity = null)
    {
        $this->entity = $entity;
    }
    /**
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }
}