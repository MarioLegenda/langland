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
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var UserInterface $user
     */
    private $user;
    /**
     * @var ArrayCollection $languages
     */
    private $languages;
    /**
     * @var Language $currentLanguage
     */
    private $currentLanguage;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
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
     * @param Language $language
     * @return bool
     */
    public function hasLanguage(Language $language) : bool
    {
        return $this->languages->contains($language);
    }
    /**
     * @param Language $language
     * @return LearningUser
     */
    public function addLanguage(Language $language) : LearningUser
    {
        if (!$this->hasLanguage($language)) {
            $this->languages->add($language);
        }

        return $this;
    }
    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->languages;
    }
    /**
     * @return mixed
     */
    public function getCurrentLanguage()
    {
        return $this->currentLanguage;
    }
    /**
     * @param mixed $currentLanguage
     * @return LearningUser
     */
    public function setCurrentLanguage($currentLanguage) : LearningUser
    {
        $this->currentLanguage = $currentLanguage;

        return $this;
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

