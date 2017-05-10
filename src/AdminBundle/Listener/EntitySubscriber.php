<?php

namespace AdminBundle\Listener;

use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Entity\LanguageInfoText;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EntitySubscriber implements EventSubscriber
{
    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
        );
    }
    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if ($entity instanceof LanguageInfo) {
            foreach ($entity->getLanguageInfoTexts() as $text) {
                if (empty($text->getName())) {
                    $entity->removeLanguageInfoText($text);
                }
            }
        }
    }
}