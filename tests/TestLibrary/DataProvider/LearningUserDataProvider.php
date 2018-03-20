<?php

namespace TestLibrary\DataProvider;

use AdminBundle\Entity\Language;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use Faker\Generator;
use PublicApiBundle\Entity\LearningUser;
use Tests\TestLibrary\DataProvider\DefaultDataProviderInterface;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;

class LearningUserDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var LearningUserRepository $learningUserRepository
     */
    private $learningUserRepository;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    private $languageDataProvider;
    /**
     * LearningUserDataProvider constructor.
     * @param LearningUserRepository $learningUserRepository
     * @param LanguageDataProvider $languageDataProvider
     */
    public function __construct(
        LearningUserRepository $learningUserRepository,
        LanguageDataProvider $languageDataProvider
    ) {
        $this->learningUserRepository = $learningUserRepository;
        $this->languageDataProvider = $languageDataProvider;
    }
    /**
     * @param Generator $faker
     * @param Language $language
     * @param $questionAnswers
     * @return LearningUser
     */
    public function createDefault(
        Generator $faker,
        Language $language = null,
        array $questionAnswers = null
    ): LearningUser {
        return $this->createLearningUser(
            $faker,
            $this->createLanguageIfNotExists($faker, $language),
            $questionAnswers
        );
    }
    /**
     * @param Generator $faker
     * @param Language $language
     * @param array $questionAnswers
     * @return LearningUser
     */
    public function createDefaultDb(
        Generator $faker,
        Language $language = null,
        array $questionAnswers = null
    ): LearningUser {
        return $this->learningUserRepository->persistAndFlush(
            $this->createDefault($faker, $language, $questionAnswers)
        );
    }
    /**
     * @return LearningUserRepository
     */
    public function getRepository(): LearningUserRepository
    {
        return $this->learningUserRepository;
    }
    /**
     * @param Generator $faker
     * @param Language $language
     * @param array $questionAnswers
     * @return LearningUser
     */
    private function createLearningUser(
        Generator $faker,
        Language $language,
        array $questionAnswers = null
    ): LearningUser {
        $learningUser = new LearningUser();
        $learningUser->setIsLanguageInfoLooked(false);
        $learningUser->setLanguage($language);

        if (is_array($questionAnswers)) {
            $learningUser->setAnsweredQuestions($questionAnswers);
        } else {
            $learningUser->setAnsweredQuestions($this->createQuestionAnswers()->getAnswers());
        }

        return $learningUser;
    }
    /**
     * @param Generator $faker
     * @param Language|null $language
     * @return Language
     */
    private function createLanguageIfNotExists(Generator $faker, Language $language = null): Language
    {
        if (!$language instanceof Language) {
            $language = $this->languageDataProvider->createDefaultDb($faker);
        }

        return $language;
    }
    /**
     * @return QuestionAnswers
     */
    private function createQuestionAnswers(): QuestionAnswers
    {
        $answers = [
            'speaking_languages' => 2,
            'profession' => 'arts_and_entertainment',
            'person_type' => 'risk_taker',
            'learning_time' => 'morning',
            'free_time' => '30_minutes',
            'memory' => 'short_term',
            'challenges' => 'likes_challenges',
            'stressful_job' => 'stressful_job',
        ];

        return new QuestionAnswers($answers);
    }
}