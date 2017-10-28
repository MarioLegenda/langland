<?php

namespace Library\LearningMetadata\Business\Listener;

use AdminBundle\Event\AudioUploadEvent;
use Doctrine\ORM\EntityManagerInterface;
use Library\Infrastructure\FileUpload\FileUploadInterface;
use AdminBundle\Entity\Sound;

class AudioUploadListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var FileUploadInterface $imageUpload
     */
    private $uploader;
    /**
     * ImageUploadListener constructor.
     * @param EntityManagerInterface $entityManager
     * @param FileUploadInterface $uploader
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FileUploadInterface $uploader
    ) {
        $this->em = $entityManager;
        $this->uploader = $uploader;
    }
    /**
     * @param AudioUploadEvent $event
     */
    public function onUpload(AudioUploadEvent $event)
    {
        if ($event->getEntity() instanceof Sound) {
            $this->uploadAudio($event->getEntity());
        }
    }
    /**
     * @param Sound $sound
     */
    private function uploadAudio(Sound $sound)
    {
        foreach ($sound->getSoundFile() as $soundFile) {
            $this->uploader->upload($soundFile, array(
                'repository' => 'AdminBundle:Sound',
                'field' => 'name',
            ));

            $fileData = $this->uploader->getData();

            $newSound = new Sound();

            $newSound
                ->setName($fileData['fileName'])
                ->setOriginalName($fileData['originalName'])
                ->setTargetDir($fileData['targetDir'])
                ->setFullPath($fileData['fullPath']);

            $this->em->persist($newSound);
        }

        $this->em->flush();
    }
}