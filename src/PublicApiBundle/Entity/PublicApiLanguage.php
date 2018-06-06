<?php

namespace PublicApiBundle\Entity;

use Library\Util\Util;

class PublicApiLanguage
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
     * @var bool $showOnPage
     */
    private $showOnPage;
    /**
     * @var bool $alreadyLearning
     */
    private $alreadyLearning;
    /**
     * @var string $listDescription
     */
    private $listDescription;
    /**
     * @var array $images
     */
    private $images;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    public function __construct()
    {
        $this->showOnPage = false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    /**
     * @return bool
     */
    public function getShowOnPage()
    {
        return $this->showOnPage;
    }
    /**
     * @param bool $showOnPage
     */
    public function setShowOnPage(bool $showOnPage): void
    {
        $this->showOnPage = $showOnPage;
    }
    /**
     * @return bool
     */
    public function isAlreadyLearning(): bool
    {
        return $this->alreadyLearning;
    }
    /**
     * @param bool $alreadyLearning
     */
    public function setAlreadyLearning(bool $alreadyLearning): void
    {
        $this->alreadyLearning = $alreadyLearning;
    }
    /**
     * @return mixed
     */
    public function getListDescription()
    {
        return $this->listDescription;
    }
    /**
     * @param string $listDescription
     */
    public function setListDescription($listDescription): void
    {
        $this->listDescription = $listDescription;
    }
    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }
    /**
     * @param array $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = Util::toDateTime($createdAt);
    }
    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = Util::toDateTime($updatedAt);
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}