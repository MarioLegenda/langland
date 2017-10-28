<?php

namespace Library\LearningMetadata\Business\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Library\Event\ImageUploadEvent;
use Library\Infrastructure\FileUpload\FileUploaderInterface;

class AudioUploadListener
{
    /**
     * @var FileUploaderInterface $fileUploader
     */
    private $fileUploader;
    /**
     * @var EntityManagerInterface $em
     */
    private $em;
    /**
     * AudioUploadListener constructor.
     * @param FileUploaderInterface $fileUploader
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        FileUploaderInterface $fileUploader,
        EntityManagerInterface $entityManager
    ) {
        $this->em = $entityManager;
        $this->fileUploader = $fileUploader;
    }
    /**
     * @param ImageUploadEvent $event
     */
    public function onUpload(ImageUploadEvent $event)
    {

    }
}