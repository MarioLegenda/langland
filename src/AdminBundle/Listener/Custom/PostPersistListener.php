<?php

namespace AdminBundle\Listener\Custom;

use AdminBundle\Entity\Sentence;
use AdminBundle\Event\PostPersistEvent;

class PostPersistListener extends AbstractEntityManagerBaseListener
{
    /**
     * @param PostPersistEvent $event
     */
    public function onPostPersist(PostPersistEvent $event)
    {
        if (array_key_exists('sentence', $event->getEntities())) {
            $this->handleSentenceJob($event->getEntities()['sentence']);
        }
    }

    private function handleSentenceJob(Sentence $sentence)
    {

    }
}