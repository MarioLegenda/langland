<?php

namespace TestLibrary\DataProvider;

use Armor\Repository\LanguageSessionRepository;
use ArmorBundle\Entity\LanguageSession;
use ArmorBundle\Entity\User;
use Faker\Generator;
use PublicApiBundle\Entity\LearningUser;
use Tests\TestLibrary\DataProvider\DefaultDataProviderInterface;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;

class LanguageSessionDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var LearningUserDataProvider $learningUserDataProvider
     */
    private $learningUserDataProvider;
    /**
     * @var LanguageSessionRepository $languageSessionRepository
     */
    private $languageSessionRepository;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    private $languageDataProvider;
    /**
     * LanguageSessionDataProvider constructor.
     * @param LearningUserDataProvider $learningUserDataProvider
     * @param LanguageDataProvider $languageDataProvider
     * @param LanguageSessionRepository $languageSessionRepository
     */
    public function __construct(
        LearningUserDataProvider $learningUserDataProvider,
        LanguageDataProvider $languageDataProvider,
        LanguageSessionRepository $languageSessionRepository
    ) {
        $this->learningUserDataProvider = $learningUserDataProvider;
        $this->languageDataProvider = $languageDataProvider;
        $this->languageSessionRepository = $languageSessionRepository;
    }
    /**
     * @inheritdoc
     */
    public function createDefault(
        Generator $faker,
        User $user = null,
        LearningUser $learningUser = null
    ) {
        if (!$user instanceof User) {
            $message = sprintf(
                'User object has to be provided for %s',
                LanguageSessionDataProvider::class
            );

            throw new \RuntimeException($message);
        }

        return $this->createLanguageSession($user, $learningUser);
    }
    /**
     * @param Generator $faker
     * @param User|null $user
     * @param LearningUser|null $learningUser
     * @return \ArmorBundle\Entity\LanguageSession
     */
    public function createDefaultDb(
        Generator $faker,
        User $user = null,
        LearningUser $learningUser = null
    ) {
        $languageSession = $this->createDefault($faker, $user, $learningUser);

        return $this->languageSessionRepository->persistAndFlush($languageSession);
    }
    /**
     * @param Generator $faker
     * @param User|null $user
     * @param LearningUser|null $learningUser
     * @return LanguageSession
     */
    public function createLanguageSession(
        User $user,
        LearningUser $learningUser = null
    ): LanguageSession {
        if (!$learningUser instanceof LearningUser) {
            $learningUser = $this->learningUserDataProvider->createDefaultDb($learningUser);
        }

        return new LanguageSession(
            $user,
            $learningUser
        );
    }
    /**
     * @return LanguageSessionRepository
     */
    public function getRepository(): LanguageSessionRepository
    {
        return $this->languageSessionRepository;
    }
}