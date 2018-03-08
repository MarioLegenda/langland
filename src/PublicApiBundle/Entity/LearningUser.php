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
     * @var bool $areQuestionsLooked
     */
    private $areQuestionsLooked;
    /**
     * @var array $answeredQuestions
     */
    private $answeredQuestions;
    /**
     * @var LearningUserLesson $learningUserLesson
     */
    private $learningUserLesson;
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
        $this->areQuestionsLooked = false;
    }
    /**
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
     * @return bool
     */
    public function getAreQuestionsLooked(): bool
    {
        return $this->areQuestionsLooked;
    }

    /**
     * @param bool $areQuestionsLooked
     * @return LearningUser
     */
    public function setAreQuestionsLooked(bool $areQuestionsLooked): LearningUser
    {
        $this->areQuestionsLooked = $areQuestionsLooked;

        return $this;
    }
    /**
     * @return array
     */
    public function getAnsweredQuestions(): array
    {
        return $this->answeredQuestions;
    }
    /**
     * @param array $answeredQuestions
     */
    public function setAnsweredQuestions(array $answeredQuestions): void
    {
        $this->answeredQuestions = $answeredQuestions;
    }
    /**
     * @return LearningUserLesson
     */
    public function getLearningUserLesson(): LearningUserLesson
    {
        return $this->learningUserLesson;
    }
    /**
     * @param LearningUserLesson $learningUserLesson
     * @return LearningUser
     */
    public function setLearningUserLesson(LearningUserLesson $learningUserLesson): LearningUser
    {
        $this->learningUserLesson = $learningUserLesson;

        return $this;
    }
    /**
     * @param \DateTime $createdAt
     * @return LearningUser
     */
    public function setCreatedAt(\DateTime $createdAt) : LearningUser
    {
        $this->createdAt = $createdAt;

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
     * @param \DateTime $updatedAt
     * @return LearningUser
     */
    public function setUpdatedAt(\DateTime $updatedAt) : LearningUser
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
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

