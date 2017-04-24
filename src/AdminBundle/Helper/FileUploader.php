<?php

namespace AdminBundle\Helper;

use Library\App\FileNamer;
use Library\App\ImageResize;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var string $imageDir
     */
    private $imageDir;
    /**
     * @var string $soundDir
     */
    private $soundDir;
    /**
     * @var ImageResize $imageResize
     */
    private $imageResize;
    /**
     * @var FileNamer $fileNamer
     */
    private $fileNamer;
    /**
     * @var string $fileName
     */
    private $fileName;
    /**
     * @var string $originalName
     */
    private $originalName;
    /**
     * FileUploader constructor.
     * @param array $uploadDirs
     * @param ImageResize $imageResize
     * @param FileNamer $fileNamer
     */
    public function __construct(array $uploadDirs, ImageResize $imageResize, FileNamer $fileNamer)
    {
        $this->imageDir = $uploadDirs['image_upload_dir'];
        $this->soundDir = realpath($uploadDirs['sound_upload_dir']);
        $this->imageResize = $imageResize;
        $this->fileNamer = $fileNamer;
    }
    /**
     * @param UploadedFile $file
     * @param array $options
     */
    public function uploadImage(UploadedFile $file, array $options = array())
    {
        $this->fileName = $this->fileNamer->createName($options).'.'.$file->guessExtension();
        $this->originalName = $file->getClientOriginalName();

        $path = $this->imageDir.'/'.$this->fileName;

        $this->imageResize
            ->setWidth(250)
            ->setHeight(250)
            ->resizeAndSave($file, $path);
    }

    public function uploadSound(UploadedFile $file, array $options)
    {
        $this->fileName = $this->fileNamer->createName($options).'.mp3';
        $this->originalName = $file->getClientOriginalName();

        $file->move($this->soundDir.'/temp', $this->fileName);

        $src = $this->soundDir.'/temp/'.$this->fileName;
        $dest = $this->soundDir.'/'.$this->fileName;

        exec(sprintf('/usr/bin/sox -t %s %s %s', 'mp3', $src, $dest), $output);

        if (file_exists($src)) {
            unlink($src);
        }
    }
    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
    }
    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }
    /**
     * @param string $originalName
     */
    public function setOriginalName(string $originalName)
    {
        $this->originalName = $originalName;
    }
    /**
     * @return string
     */
    public function getImageDir(): string
    {
        return $this->imageDir;
    }
    /**
     * @param string $imageDir
     */
    public function setImageDir(string $imageDir)
    {
        $this->imageDir = $imageDir;
    }
    /**
     * @return string
     */
    public function getSoundDir(): string
    {
        return $this->soundDir;
    }
    /**
     * @param string $soundDir
     */
    public function setSoundDir(string $soundDir)
    {
        $this->soundDir = $soundDir;
    }
}