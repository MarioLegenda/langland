<?php

namespace AdminBundle\Entity;

class Language
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
     * @return Language
     */
    public function setName(string $name) : Language
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getShowOnPage()
    {
        return $this->showOnPage;
    }
    /**
     * @param mixed $showOnPage
     * @return Language
     */
    public function setShowOnPage($showOnPage) : Language
    {
        $this->showOnPage = $showOnPage;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getListDescription()
    {
        return $this->listDescription;
    }
    /**
     * @param mixed $listDescription
     * @return Language
     */
    public function setListDescription($listDescription) : Language
    {
        $this->listDescription = $listDescription;

        return $this;
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
     * @return Language
     */
    public function setImages(array $images): Language
    {
        $this->images = $images;

        return $this;
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
     * @return Language
     */
    public function setCreatedAt(\DateTime $createdAt) : Language
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
     * @return Language
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Language
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