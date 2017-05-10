<?php

namespace AdminBundle\Entity;

/**
 * LanguageInfoText
 */
class LanguageInfoText
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $languageInfoText
     */
    private $name;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var LanguageInfo $languageInfo
     */
    private $languageInfo;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set languageInfoText
     *
     * @param string $name
     *
     * @return LanguageInfoText
     */
    public function setName($name) : LanguageInfoText
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get languageInfoText
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @return mixed
     */
    public function getLanguageInfo()
    {
        return $this->languageInfo;
    }
    /**
     * @param mixed $languageInfo
     */
    public function setLanguageInfo($languageInfo)
    {
        $this->languageInfo = $languageInfo;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LanguageInfoText
     */
    public function setCreatedAt($createdAt) : LanguageInfoText
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return LanguageInfoText
     */
    public function setUpdatedAt($updatedAt) : LanguageInfoText
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

