<?php

namespace AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ImageUploadEvent extends Event
{
    const NAME = 'admin.image_upload_event';
    /**
     * @var object $entity
     */
    private $entity;
    /**
     * ImageUploadEvent constructor.
     * @param null $entity
     */
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