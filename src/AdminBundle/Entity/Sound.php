<?php

namespace AdminBundle\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Sound implements ResourceInterface
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $targetDir
     */
    private $targetDir;
    /**
     * @var string $originalName
     */
    private $originalName;
    /**
     * @var string $fullPath
     */
    private $fullPath;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var UploadedFile $soundFile
     */
    private $soundFile;
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
     * @param string $name
     * @return Sound
     */
    public function setName($name) : Sound
    {
        $this->name = $name;

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
     * @return Sound
     */
    public function setTargetDir($targetDir) : Sound
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
     * @return Sound
     */
    public function setCreatedAt($createdAt) : Sound
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }
    /**
     * @param string $originalName
     * @return Sound
     */
    public function setOriginalName($originalName) : Sound
    {
        $this->originalName = $originalName;

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
     * @return Sound
     */
    public function setFullPath($fullPath) : Sound
    {
        $this->fullPath = $fullPath;

        return $this;
    }
    /**
     * @return UploadedFile
     */
    public function getSoundFile()
    {
        return $this->soundFile;
    }
    /**
     * @param UploadedFile $soundFile
     * @return Sound
     */
    public function setSoundFile($soundFile) : Sound
    {
        $this->soundFile = $soundFile;

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
     * @return Sound
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Sound
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