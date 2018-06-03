<?php

namespace PublicApi\Infrastructure\Communication;

use AdminBundle\Entity\Language as MetadataLanguage;
use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Word;
use Armor\Infrastructure\Provider\LanguageSessionProvider;
use ArmorBundle\Entity\LanguageSession;
use ArmorBundle\Entity\User;
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
    /**
     * @param User $user
     * @return array
     */
    public function getSortedLanguages(User $user): array
    {
        $learningMetadataLanguages = $this->languageRepository->findBy([
            'showOnPage' => true,
        ]);

        $alreadyLearningLanguages = $user->getLanguageSessionLanguages();
        $notLearningLanguages = array_filter($learningMetadataLanguages, function(Language $language) use ($alreadyLearningLanguages) {
            /** @var Language $alreadyLearningLanguage */
            foreach ($alreadyLearningLanguages as $alreadyLearningLanguage) {
                if ($alreadyLearningLanguage->getId() !== $language->getId()) {
                    return true;
                }
            }
        }, ARRAY_FILTER_USE_BOTH);

        $returnData = [
            'alreadyLearning' => $alreadyLearningLanguages,
            'notLearning' => $notLearningLanguages,
        ];

        return $returnData;

        $languageSessions = $user->getLanguageSessions();

        foreach ($languageSessions as $languageSession) {
            $sessionLanguage = $languageSession
                ->getLearningUser()
                ->getLanguage();

            $returnData['alreadyLearning'][] = $sessionLanguage;
        }

        return $languages;

        /** @var \AdminBundle\Entity\Language $language */
        foreach ($languages as $language) {
            $temp = [];
            $temp['id'] = $language->getId();
            $temp['name'] = $language->getName();
            $temp['desc'] = $language->getListDescription();
            $temp['images'] = $this->parseImages($language->getImages());
            $temp['alreadyLearning'] = false;
            $temp['urls'] = [
                'backend_url' => null,
                'frontend_url' => sprintf('language/%s/%d', $language->getName(), $language->getId())
            ];

            foreach ($learningUsers as $learningUser) {
                $learningUserLanguage = $learningUser->getLanguage();

                if ($language->getId() === $learningUserLanguage->getId()) {
                    $temp['alreadyLearning'] = true;
                }
            }

            $viewable[] = $temp;
        }

        $languageIds = [];

        /** @var MetadataLanguage $language */
        foreach ($languages as $language) {
            $languageIds[] = $language->getId();
        }

        $qb = $this->learningUserRepository->createQueryBuilderFromClass('lu');

        $learningUsers = $qb
            ->andwhere('lu.language IN (:languageIds)')
            ->andWhere('lu.user = :userId')
            ->setParameter(':languageIds', $languageIds)
            ->setParameter(':userId', $user->getId())
            ->getQuery()
            ->getResult();

        $viewable = [];
        foreach ($languages as $language) {

        }

        return $viewable;
    }
    /**
     * @param array $images
     * @return array
     */
    private function parseImages(array $images): array
    {
        $parsed = [];
        $parsed['cover'] = sprintf(
            '%s/%s',
            $images['cover_image']['relativePath'],
            $images['cover_image']['originalName']
        );

        $parsed['icon'] = sprintf(
            '%s/%s',
            $images['icon']['relativePath'],
            $images['icon']['originalName']
        );

        return $parsed;
    }
}