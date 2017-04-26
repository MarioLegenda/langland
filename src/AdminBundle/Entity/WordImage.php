<?php

namespace AdminBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class WordImage
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
     * @var UploadedFile $imageFile
     */
    private $imageFile;
    /**
     * @var Word $word
     */
    private $word;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     * @return WordImage
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return WordImage
     */
    public function setName($name)
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
     * @return WordImage
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
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
     * @return WordImage
     */
    public function setWord($word) : WordImage
    {
        $this->word = $word;

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
     * @return WordImage
     */
    public function setTargetDir($targetDir) : WordImage
    {
        $this->targetDir = $targetDir;

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
     * @return WordImage
     */
    public function setCreatedAt($createdAt) : WordImage
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
     * @param \DateTime $updatedAt
     * @return WordImage
     */
    public function setUpdatedAt(\DateTime $updatedAt) : WordImage
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}