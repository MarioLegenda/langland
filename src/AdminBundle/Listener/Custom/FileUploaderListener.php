<?php

namespace AdminBundle\Listener\Custom;

use AdminBundle\Entity\Image;
use AdminBundle\Entity\Language;
use AdminBundle\Entity\Sound;
use AdminBundle\Entity\Word;
use Library\Event\FileUploadEvent;
use Doctrine\ORM\EntityManager;
use Library\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderListener
{
    /**
     * @var FileUploader $fileUploader
     */
    private $fileUploader;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * FileUploaderListener constructor.
     * @param FileUploader $fileUploader
     * @param EntityManager $em
     */
    public function __construct(FileUploader $fileUploader, EntityManager $em)
    {
        $this->fileUploader = $fileUploader;
        $this->em = $em;
    }
    /**
     * @param FileUploadEvent $event
     */
    public function onFileUpload(FileUploadEvent $event)
    {
        if ($event->getEntity() instanceof Language) {
            $this->uploadLanguageIcon($event->getEntity());
        }

        if ($event->getEntity() instanceof Word) {
            $this->uploadWordImage($event->getEntity());
        }

        if ($event->getEntity() instanceof Sound) {
            $this->uploadSound($event->getEntity());
        }
    }

    private function uploadSound($sound)
    {
        foreach ($sound->getSoundFile() as $soundFile) {
            $this->fileUploader->uploadSound($soundFile, array(
                'repository' => 'AdminBundle:Sound',
                'field' => 'name',
            ));

            $fileData = $this->fileUploader->getData();

            $newSound = new Sound();

            $newSound
                ->setName($fileData['fileName'])
                ->setOriginalName($fileData['originalName'])
                ->setTargetDir($fileData['targetDir'])
                ->setFullPath($fileData['fullPath']);

            dump($newSound);

            $this->em->persist($newSound);
        }

        $this->em->flush();
    }

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
            $this->fileUploader->uploadImage($word->getImage()->getImageFile(), array(
                'repository' => 'AdminBundle:Image',
                'field' => 'name',
                'resize' => array(
                    'width' => 250,
                    'height' => 250,
                ),
            ));

            $data = $this->fileUploader->getData();

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

    private function uploadLanguageIcon(Language $language)
    {
        $existingImage = null;
        if ($language->getImage()->getImageFile() instanceof UploadedFile) {
            $dbImage = $this->em->getRepository('AdminBundle:Image')->findBy(array(
                'language' => $language,
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

        if ($language->getImage()->getImageFile() instanceof UploadedFile) {
            $this->fileUploader->uploadImage($language->getImage()->getImageFile(), array(
                'repository' => 'AdminBundle:Image',
                'field' => 'name',
            ));

            $data = $this->fileUploader->getData();

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

            $language->getImage()
                ->setName($data['fileName'])
                ->setOriginalName($data['originalName'])
                ->setRelativePath($data['relativePath'])
                ->setTargetDir($data['targetDir'])
                ->setFullPath($data['fullPath']);

            $language->getImage()->setLanguage($language);
            $this->em->persist($language->getImage());
        }
    }
}