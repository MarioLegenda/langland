<?php

namespace Armor\Infrastructure\Provider;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\LanguageSession;
use ArmorBundle\Entity\User;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LanguageSessionProvider
{
    /**
     * @var TokenStorage $tokenStorage
     */
    private $tokenStorage;
    /**
     * @var LanguageSession $languageSession
     */
    private $languageSession;
    /**
     * LanguageSessionProvider constructor.
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        TokenStorage $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @return LanguageSession
     */
    public function getLanguageSession(): LanguageSession
    {
        return $this->getSession();
    }
    /**
     * @return LearningUser
     */
    public function getLearningUser(): LearningUser
    {
        return $this->getSession()->getLearningUser();
    }
    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->getSession()->getLearningUser()->getLanguage();
    }
    /**
     * @return LanguageSession
     */
    private function getSession(): LanguageSession
    {
        if (!$this->languageSession instanceof LanguageSession) {
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();

            $this->languageSession = $user->getCurrentLanguageSession();
        }

        return $this->languageSession;
    }
}