<?php

namespace PublicApiBundle\Entity;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

class LearningUser
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var UserInterface $user
     */
    private $user;
    /**
     * @var Language $language
     */
    private $language;
    /**
     * @var bool $isLanguageInfoLooked
     */
    private $isLanguageInfoLooked;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * LearningUser constructor.
     */
    public function __construct()
    {
        $this->isLanguageInfoLooked = false;
    }
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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @param UserInterface $user
     * @return LearningUser
     */
    public function setUser($user) : LearningUser
    {
        $this->user = $user;

        return $this;
    }
    /**
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     * @param mixed $language
     * @return LearningUser
     */
    public function setLanguage($language) : LearningUser
    {
        $this->language = $language;

        return $this;
    }
    /**
     * @return bool
     */
    public function getIsLanguageInfoLooked(): bool
    {
        return $this->isLanguageInfoLooked;
    }
    /**
     * @param bool $isLanguageInfoLooked
     */
    public function setIsLanguageInfoLooked(bool $isLanguageInfoLooked): void
    {
        $this->isLanguageInfoLooked = $isLanguageInfoLooked;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LearningUser
     */
    public function setCreatedAt(\DateTime $createdAt) : LearningUser
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
     * @return LearningUser
     */
    public function setUpdatedAt(\DateTime $updatedAt) : LearningUser
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

