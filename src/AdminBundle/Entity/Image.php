<?php

namespace AdminBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image implements \JsonSerializable
{
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $originalName
     */
    private $originalName;
    /**
     * @var string $targetDir
     */
    private $targetDir;
    /**
     * @var string $fullPath
     */
    private $fullPath;
    /**
     * @var string $relativePath
     */
    private $relativePath;
    /**
     * @var UploadedFile $imageFile
     */
    private $imageFile;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var Word $word
     */
    private $word;
    /**
     * @var Language $language
     */
    private $language;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     * @return Image
     */
    public function setName($name) : Image
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return null|UploadedFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }
    /**
     * @param mixed $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }
    /**
     * @return mixed
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }
    /**
     * @param mixed $originalName
     * @return Image
     */
    public function setOriginalName($originalName) : Image
    {
        $this->originalName = $originalName;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
    /**
     * @param string $targetDir
     * @return Image
     */
    public function setTargetDir($targetDir) : Image
    {
        $this->targetDir = $targetDir;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }
    /**
     * @param mixed $fullPath
     * @return Image
     */
    public function setFullPath($fullPath) : Image
    {
        $this->fullPath = $fullPath;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getRelativePath() : string
    {
        return $this->relativePath;
    }
    /**
     * @param mixed $relativePath
     * @return Image
     */
    public function setRelativePath(string $relativePath) : Image
    {
        $this->relativePath = $relativePath;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $createdAt
     * @return Image
     */
    public function setCreatedAt($createdAt) : Image
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }
    /**
     * @param mixed $word
     * @return Image
     */
    public function setWord($word) : Image
    {
        $this->word = $word;

        return $this;
    }
    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }
    /**
     * @param Language $language
     * @return Image
     */
    public function setLanguage(Language $language) : Image
    {
        $this->language = $language;

        return $this;
    }
    /**
     * @param \DateTime $updatedAt
     * @return Image
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Image
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @void
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'originalName' => $this->getOriginalName(),
            'fullPath' => $this->getFullPath(),
            'relativePath' => $this->getRelativePath(),
        ];
    }
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}