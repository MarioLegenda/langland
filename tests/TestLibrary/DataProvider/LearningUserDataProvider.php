<?php

namespace TestLibrary\DataProvider;

use AdminBundle\Entity\Language;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use Faker\Generator;
use PublicApiBundle\Entity\LearningUser;
use Tests\TestLibrary\DataProvider\DefaultDataProviderInterface;

class LearningUserDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var LearningUserRepository $learningUserRepository
     */
    private $learningUserRepository;
    /**
     * LearningUserDataProvider constructor.
     * @param LearningUserRepository $learningUserRepository
     */
    public function __construct(
        LearningUserRepository $learningUserRepository
    ) {
        $this->learningUserRepository = $learningUserRepository;
    }
    /**
     * @param Generator $faker
     * @param Language $language
     * @return LearningUser
     */
    public function createDefault(Generator $faker, Language $language = null): LearningUser
    {
        return $this->createLearningUser($faker, $language);
    }
    /**
     * @param Generator $faker
     * @param Language $language
     * @return LearningUser
     */
    public function createDefaultDb(Generator $faker, Language $language = null): LearningUser
    {
        return $this->learningUserRepository->persistAndFlush($this->createDefault($faker, $language));
    }
    /**
     * @param Generator $faker
     * @param Language $language
     * @return LearningUser
     */
    private function createLearningUser(Generator $faker, Language $language): LearningUser
    {
        $learningUser = new LearningUser();
        $learningUser->setIsLanguageInfoLooked(false);
        $learningUser->setLanguage($language);

        return $learningUser;
    }
}