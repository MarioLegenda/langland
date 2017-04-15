<?php

namespace AdminBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Sound
{
    /**
     * @var UploadedFile $soundFile
     */
    private $soundFile;
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $relativePath
     */
    private $relativePath;
    /**
     * @var string $absolutePath
     */
    private $absolutePath;
    /**
     * @var string $fileName
     */
    private $fileName;
    /**
     * @var string $absoluteFullPath
     */
    private $absoluteFullPath;
    /**
     * @var string $relativeFullPath
     */
    private $relativeFullPath;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }
    /**
     * @param mixed $relativePath
     * @return Sound
     */
    public function setRelativePath($relativePath) : Sound
    {
        $this->relativePath = $relativePath;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }
    /**
     * @param mixed $absolutePath
     * @return Sound
     */
    public function setAbsolutePath($absolutePath) : Sound
    {
        $this->absolutePath = $absolutePath;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    /**
     * @param mixed $fileName
     * @return Sound
     */
    public function setFileName($fileName) : Sound
    {
        $this->fileName = $fileName;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getAbsoluteFullPath()
    {
        return $this->absoluteFullPath;
    }
    /**
     * @param mixed $absoluteFullPath
     * @return Sound
     */
    public function setAbsoluteFullPath($absoluteFullPath) : Sound
    {
        $this->absoluteFullPath = $absoluteFullPath;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getRelativeFullPath()
    {
        return $this->relativeFullPath;
    }
    /**
     * @param mixed $relativeFullPath
     * @return Sound
     */
    public function setRelativeFullPath($relativeFullPath) : Sound
    {
        $this->relativeFullPath = $relativeFullPath;

        return $this;
    }
    /**
     * @return bool
     */
    public function hasSoundFile() : bool
    {
        return $this->soundFile instanceof UploadedFile;
    }
    /**
     * @return UploadedFile
     */
    public function getSoundFile(): UploadedFile
    {
        return $this->soundFile;
    }
    /**
     * @param UploadedFile $soundFile
     * @return Sound
     */
    public function setSoundFile(UploadedFile $soundFile) : Sound
    {
        $this->soundFile = $soundFile;

        return $this;
    }
}