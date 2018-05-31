<?php

namespace PublicApi\Infrastructure\Communication;

use AdminBundle\Entity\Language as MetadataLanguage;
use ArmorBundle\Entity\User;
use LearningMetadata\Repository\Implementation\LessonRepository;
use Library\Infrastructure\Helper\SerializerWrapper;
use PublicApi\Infrastructure\Model\Word\InitialCreationWord;
use PublicApi\Infrastructure\Repository\WordRepository;
use PublicApi\Language\Repository\LanguageRepository;
use PublicApi\LearningSystem\Repository\LearningLessonRepository;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use PublicApi\Infrastructure\Model\Language;
use PublicApi\Infrastructure\Model\Lesson;
use PublicApiBundle\Entity\LearningUser;

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
     * @var LearningUserProvider $learningUserProvider
     */
    private $learningUserProvider;
    /**
     * RepositoryCommunicator constructor.
     * @param LearningUserProvider $learningUserProvider
     * @param LanguageRepository $languageRepository
     * @param LearningUserRepository $learningUserRepository
     * @param LessonRepository $lessonRepository
     * @param LearningLessonRepository $learningLessonRepository
     * @param WordRepository $wordRepository
     * @param SerializerWrapper $serializerWrapper
     */
    public function __construct(
        LearningUserProvider $learningUserProvider,
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
        $this->learningUserProvider = $learningUserProvider;
    }
    /**
     * @param Language $language
     * @return Lesson[]
     */
    public function getLessonsByLanguage(Language $language): array
    {
        $qb = $this->lessonRepository->createQueryBuilder('l');

        $metadataLessons = $qb
            ->andWhere('l.language = :language_id')
            ->setParameter(':language_id', $language->getId())
            ->getQuery()
            ->getResult();

        return $this->createModelsFromMetadata(
            $metadataLessons,
            Lesson::class,
            ['internal_model']
        );
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
     * @return array
     */
    public function getWordsByLevelAndLesson(
        Language $language,
        Lesson $lesson,
        int $level
    ) {
        $qb = $this->wordRepository->createQueryBuilderFromClass('w');

        $words = $qb
            ->where('w.language = :language_id')
            ->andWhere('w.level = :level')
            ->andWhere('w.lesson = :lesson_id')
            ->setParameters([
                'language_id' => $language->getId(),
                'lesson_id' => $lesson->getId(),
                'level' => $level,
            ])
            ->getQuery()
            ->getResult();

        return $this->createModelsFromMetadata(
            $words,
            InitialCreationWord::class,
            ['initial_creation_word']
        );
    }
    /**
     * @param Language $language
     * @return MetadataLanguage
     */
    public function getMetadataLanguageByLanguageModel(Language $language): MetadataLanguage
    {
        return $this->languageRepository->find($language->getId());
    }
    /**
     * @param Language $language
     * @param int $wordLevel
     * @param array $wordIds
     * @return array
     */
    public function getWordsByLevelAndLessonByExactIds(
        Language $language,
        int $wordLevel,
        array $wordIds
    ) {
        $qb = $this->wordRepository->createQueryBuilderFromClass('w');

        $words = $qb
            ->where('w.language = :language_id')
            ->andWhere('w.level = :level')
            ->andWhere('w.id IN (:word_ids)')
            ->setParameters([
                'language_id' => $language->getId(),
                'level' => $wordLevel,
                'word_ids' => $wordIds
            ])
            ->getQuery()
            ->getResult();

        return $this->createModelsFromMetadata(
            $words,
            InitialCreationWord::class,
            ['initial_creation_word']
        );
    }
    /**
     * @param User $user
     * @return array
     */
    public function getAllAlreadyLearningLanguages(User $user): array
    {
        /** @var LearningUser[] $learningUsers */
        $learningUsers = $this->learningUserRepository->findBy([
            'user' => $user,
        ]);

        $languages = [];
        /** @var LearningUser $learningUser */
        foreach ($learningUsers as $learningUser) {
            $languages[] = $learningUser->getLanguage();
        }

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
     * @param Language $language1
     * @param Language $language2
     * @return bool
     */
    private function equalsLanguage(Language $language1, Language $language2): bool
    {
        return (int) $language1->getId() === (int) $language2->getId();
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
    /**
     * @param array $metadata
     * @param string $class
     * @param array $groups
     * @return array
     */
    private function createModelsFromMetadata(
        array $metadata,
        string $class,
        array $groups
    ) : array {
        $models = [];

        foreach ($metadata as $m) {

            $serialized = $this->serializerWrapper->serialize($m, $groups);

            $model = $this->serializerWrapper->getDeserializer()->create(
                $serialized,
                $class
            );

            $this->serializerWrapper->getModelValidator()->validate($model);

            $models[] = $model;
        }

        return $models;
    }
}