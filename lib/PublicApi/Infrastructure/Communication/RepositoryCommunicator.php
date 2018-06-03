<?php

namespace PublicApi\Infrastructure\Communication;

use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Word;
use Armor\Infrastructure\Provider\LanguageSessionProvider;
use LearningMetadata\Repository\Implementation\LessonRepository;
use Library\Infrastructure\Helper\SerializerWrapper;
use PublicApi\Infrastructure\Repository\WordRepository;
use PublicApi\Language\Repository\LanguageRepository;
use PublicApi\LearningSystem\Repository\LearningLessonRepository;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use PublicApiBundle\Entity\LearningUser;
use AdminBundle\Entity\Language;

class RepositoryCommunicator
{
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var LearningUserRepository $learningUserRepository
     */
    private $learningUserRepository;
    /**
     * @var LessonRepository $lessonRepository
     */
    private $lessonRepository;
    /**
     * @var LearningLessonRepository $learningLessonRepository
     */
    private $learningLessonRepository;
    /**
     * @var WordRepository $wordRepository
     */
    private $wordRepository;
    /**
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
    /**
     * @var LanguageSessionProvider $languageSessionProvider
     */
    private $languageSessionProvider;
    /**
     * RepositoryCommunicator constructor.
     * @param LanguageSessionProvider $languageSessionProvider
     * @param LanguageRepository $languageRepository
     * @param LearningUserRepository $learningUserRepository
     * @param LessonRepository $lessonRepository
     * @param LearningLessonRepository $learningLessonRepository
     * @param WordRepository $wordRepository
     * @param SerializerWrapper $serializerWrapper
     */
    public function __construct(
        LanguageSessionProvider $languageSessionProvider,
        LanguageRepository $languageRepository,
        LearningUserRepository $learningUserRepository,
        LessonRepository $lessonRepository,
        LearningLessonRepository $learningLessonRepository,
        WordRepository $wordRepository,
        SerializerWrapper $serializerWrapper
    ) {
        $this->languageRepository = $languageRepository;
        $this->learningUserRepository = $learningUserRepository;
        $this->lessonRepository = $lessonRepository;
        $this->wordRepository = $wordRepository;
        $this->serializerWrapper = $serializerWrapper;
        $this->learningLessonRepository = $learningLessonRepository;
        $this->languageSessionProvider = $languageSessionProvider;
    }
    /**
     * @param LearningUser $learningUser
     * @return array
     */
    public function getAllLearningLessonsByLearningUser(
        LearningUser $learningUser
    ): array {
        $qb = $this->learningLessonRepository->createQueryBuilderFromClass('ll');

        $learningLessons = $qb
            ->andWhere('ll.learningUser = :learningUser')
            ->setParameters([
                ':learningUser' => $learningUser,
            ])
            ->getQuery()
            ->getResult();

        return $learningLessons;
    }
    /**
     * @param Language $language
     * @param Lesson $lesson
     * @param int $level
     * @return Word[]
     */
    public function getWordsByLevelAndLesson(
        Language $language,
        Lesson $lesson,
        int $level
    ) {
        $qb = $this->wordRepository->createQueryBuilderFromClass('w');

        $words = $qb
            ->where('w.language = :language')
            ->andWhere('w.level = :level')
            ->andWhere('w.lesson = :lesson')
            ->setParameters([
                'language' => $language,
                'lesson' => $lesson,
                'level' => $level,
            ])
            ->getQuery()
            ->getResult();

        return $words;
    }
    /**
     * @param Language $language
     * @param int $wordLevel
     * @param array $wordIds
     * @return Word[]
     */
    public function getWordsByLevelAndLessonByExactIds(
        Language $language,
        int $wordLevel,
        array $wordIds
    ) {
        $qb = $this->wordRepository->createQueryBuilderFromClass('w');

        $words = $qb
            ->where('w.language = :language')
            ->andWhere('w.level = :level')
            ->andWhere('w.id IN (:word_ids)')
            ->setParameters([
                'language' => $language,
                'level' => $wordLevel,
                'word_ids' => $wordIds
            ])
            ->getQuery()
            ->getResult();

        return $words;
    }
}