<?php

namespace PublicApi\LearningUser\Infrastructure\Provider;

use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LearningUserProvider
{
    /**
     * @var TokenStorage $tokenStorage
     */
    private $tokenStorage;
    /**
     * @var LearningUser $learningUser
     */
    private $learningUser;
    /**
     * LearningUserProvider constructor.
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        TokenStorage $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @return LearningUser
     */
    public function getLearningUser(): LearningUser
    {
        return $this->saveAndGetLearningUser();
    }
    /**
     * @return LearningUser
     */
    public function saveAndGetLearningUser(): LearningUser
    {
        if ($this->learningUser instanceof LearningUser) {
            return $this->learningUser;
        }

        $this->learningUser =  $this->tokenStorage->getToken()->getUser()->getCurrentLearningUser();

        return $this->learningUser;
    }
}