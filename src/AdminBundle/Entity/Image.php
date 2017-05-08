<?php

namespace AdminBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image
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
    private $imageHolder;
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
     * @return Image
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
     * @return Image
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
     * @return Image
     */
    public function setOriginalName($originalName)
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
    public function getImageHolder()
    {
        return $this->imageHolder;
    }
    /**
     * @param mixed $imageHolder
     */
    public function setImageHolder(ImageHolder $imageHolder)
    {
        $this->imageHolder = $imageHolder;
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
     * @param \DateTime $updatedAt
     * @return Image
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Image
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