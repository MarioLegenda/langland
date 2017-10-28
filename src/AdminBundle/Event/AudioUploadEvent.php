<?php

namespace AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class AudioUploadEvent extends Event
{
    const NAME = 'admin.audio_upload_event';
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