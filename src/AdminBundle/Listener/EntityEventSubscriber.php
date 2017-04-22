<?php

namespace AdminBundle\Listener;

use AdminBundle\Entity\Word;
use AdminBundle\Entity\WordImage;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EntityEventSubscriber implements EventSubscriber
{
    /**
     * @return array
     */
    public function getSubscribedEvents() : array
    {
        return array(
            'prePersist',
            'postUpdate',
        );
    }
    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof Word) {
            $wordImage = $entity->getWordImage();

            if (!$wordImage->getImageFile() instanceof UploadedFile) {
                return;
            }

            $wordImage->setWord($entity);

            $event->getEntityManager()->persist($wordImage);
        }
    }
    /**
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof WordImage) {
            return;
        }

        if ($entity instanceof Word) {
            $em = $event->getEntityManager();
            $wordImage = $entity->getWordImage();
            $imagesRepo = $em->getRepository('AdminBundle:WordImage');

            if ($wordImage->getImageFile() instanceof UploadedFile) {
                $image = $imagesRepo->findBy(array(
                    'word' => $entity,
                ));

                if (empty($image)) {
                    $wordImage->setWord($entity);

                    $em->persist($wordImage);

                    $em->flush();
                } else if ($image[0] instanceof WordImage) {
                    $dbImage = $image[0];
                    $imageFile = realpath($dbImage->getTargetDir().'/'.$dbImage->getName());

                    if (file_exists($imageFile)) {
                        unlink($imageFile);
                    }

                    $dbImage->setName($wordImage->getName());
                    $dbImage->setOriginalName($wordImage->getOriginalName());
                    $dbImage->setTargetDir($wordImage->getTargetDir());

                    $em->persist($dbImage);

                    $em->flush();
                }
            }
        }
    }

}