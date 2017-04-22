<?php

namespace AdminBundle\Helper;

use Library\App\FileNamer;
use Library\App\ImageResize;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var string $targetDir
     */
    private $targetDir;
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
     * @param string $targetDir
     * @param ImageResize $imageResize
     * @param FileNamer $fileNamer
     */
    public function __construct(string $targetDir, ImageResize $imageResize, FileNamer $fileNamer)
    {
        $this->targetDir = $targetDir;
        $this->imageResize = $imageResize;
        $this->fileNamer = $fileNamer;
    }
    /**
     * @param UploadedFile $file
     * @param array $options
     */
    public function upload(UploadedFile $file, array $options = array())
    {
        $this->fileName = $this->fileNamer->createName($options).'.'.$file->guessExtension();
        $this->originalName = $file->getClientOriginalName();

        $path = $this->targetDir.'/'.$this->fileName;

        $this->imageResize
            ->setWidth(250)
            ->setHeight(250)
            ->resizeAndSave($file, $path);
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
    public function getTargetDir(): string
    {
        return $this->targetDir;
    }
    /**
     * @param string $targetDir
     * @return FileUploader
     */
    public function setTargetDir(string $targetDir) : FileUploader
    {
        $this->targetDir = $targetDir;

        return $this;
    }
}