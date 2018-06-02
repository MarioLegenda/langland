<?php

namespace ArmorBundle\Entity;

use Library\Util\Util;
use PublicApiBundle\Entity\LearningUser;

class LanguageSession
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var User $user
     */
    private $user;
    /**
     * @var LearningUser[]|iterable $learningUsers
     */
    private $learningUser;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * LanguageSession constructor.
     * @param User
     * @param LearningUser $learningUser
     */
    public function __construct(
        User $user,
        LearningUser $learningUser
    ) {
        $this->user = $user;
        $this->learningUser = $learningUser;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    /**
     * @return LearningUser
     */
    public function getLearningUser(): LearningUser
    {
        return $this->learningUser;
    }
    /**
     * @param LearningUser $learningUser
     */
    public function setLearningUser(LearningUser $learningUser): void
    {
        $this->learningUser = $learningUser;
    }
    /**
     * @param \DateTime|string $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = Util::toDateTime($createdAt);
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime|string $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = Util::toDateTime($updatedAt);
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
    /**
     * @param LearningUser $learningUser
     * @param User $user
     * @return LanguageSession
     */
    public static function create(LearningUser $learningUser, User $user): LanguageSession
    {
        return new LanguageSession(
            $user,
            $learningUser
        );
    }
}