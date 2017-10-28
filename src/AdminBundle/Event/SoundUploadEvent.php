<?php

namespace AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class SoundUploadEvent extends Event
{
    const NAME = 'admin.sound_upload_event';
    /**
     * @var object $entity
     */
    private $entity;
    /**
     * SoundUploadEvent constructor.
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