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
     * @var $languageIcon
     */
    private $languageIcon;
    /**
     * @var $viewImage
     */
    private $viewImage;
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
     * @return mixed
     */
    public function getLanguageIcon()
    {
        return $this->languageIcon;
    }
    /**
     * @param mixed $languageIcon
     * @return Language
     */
    public function setLanguageIcon($languageIcon) : Language
    {
        $this->languageIcon = $languageIcon;

        return $this;
    }
    /**
     * @return Image
     */
    public function getViewImage()
    {
        return $this->viewImage;
    }
    /**
     * @param Image $viewImage
     */
    public function setViewImage($viewImage = null)
    {
        $this->viewImage = $viewImage;
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