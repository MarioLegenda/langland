<?php

namespace LearningMetadata\Business\Listener;

use Doctrine\ORM\EntityManagerInterface;
use AdminBundle\Event\ImageUploadEvent;
use AdminBundle\Entity\Language;
use AdminBundle\Entity\Image;
use Library\Infrastructure\FileUpload\FileUploadInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AdminBundle\Entity\Word;

class ImageUploadListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var FileUploadInterface $imageUpload
     */
    private $imageUpload;
    /**
     * ImageUploadListener constructor.
     * @param EntityManagerInterface $entityManager
     * @param FileUploadInterface $imageUpload
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FileUploadInterface $imageUpload
    ) {
        $this->em = $entityManager;
        $this->imageUpload = $imageUpload;
    }
    /**
     * @param ImageUploadEvent $event
     */
    public function onUpload(ImageUploadEvent $event)
    {
        if ($event->getEntity() instanceof Word) {
            $this->uploadWordImage($event->getEntity());
        }
    }
    /**
     * @param Word $word
     */
    private function uploadWordImage(Word $word)
    {
        $existingImage = null;
        if ($word->getImage()->getImageFile() instanceof UploadedFile) {
            $dbImage = $this->em->getRepository('AdminBundle:Image')->findBy(array(
                'word' => $word,
            ));

            if (!empty($dbImage)) {
                $image = $dbImage[0];

                $file = $image->getTargetDir().'/'.$image->getName();

                if (file_exists($file)) {
                    unlink($image->getTargetDir().'/'.$image->getName());
                }

                $existingImage = $image;
            }
        }

        if ($word->getImage()->getImageFile() instanceof UploadedFile) {
            $this->imageUpload->upload($word->getImage()->getImageFile(), array(
                'repository' => 'AdminBundle:Image',
                'field' => 'name',
                'resize' => array(
                    'width' => 250,
                    'height' => 250,
                ),
            ));

            $data = $this->imageUpload->getData();

            if ($existingImage instanceof Image) {
                $existingImage
                    ->setName($data['fileName'])
                    ->setOriginalName($data['originalName'])
                    ->setRelativePath($data['relativePath'])
                    ->setTargetDir($data['targetDir'])
                    ->setFullPath($data['fullPath']);

                $this->em->persist($existingImage);
                $this->em->flush();

                return;
            }

            $word->getImage()
                ->setName($data['fileName'])
                ->setOriginalName($data['originalName'])
                ->setRelativePath($data['relativePath'])
                ->setTargetDir($data['targetDir'])
                ->setFullPath($data['fullPath']);

            $word->getImage()->setWord($word);
            $this->em->persist($word->getImage());
        }
    }
}