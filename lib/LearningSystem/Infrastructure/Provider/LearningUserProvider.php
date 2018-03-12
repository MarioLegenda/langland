<?php

namespace LearningSystem\Infrastructure\Provider;

use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LearningUserProvider
{
    /**
     * @var TokenStorage $tokenStorage
     */
    private $tokenStorage;
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
        return $this->tokenStorage->getToken()->getUser()->getCurrentLearningUser();
    }
}