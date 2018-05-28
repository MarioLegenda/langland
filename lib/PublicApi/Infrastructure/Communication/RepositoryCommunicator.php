<?php

namespace PublicApi\Infrastructure\Communication;

use AdminBundle\Entity\Course as MetadataCourse;
use AdminBundle\Entity\Language as MetadataLanguage;
use AdminBundle\Entity\Word;
use ArmorBundle\Entity\User;
use Library\Infrastructure\Helper\SerializerWrapper;
use PublicApi\Infrastructure\Model\Word\InitialCreationWord;
use PublicApi\Infrastructure\Repository\WordRepository;
use PublicApi\Language\Repository\LanguageRepository;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use PublicApi\Lesson\Repository\LessonRepository;
use AdminBundle\Entity\Lesson as MetadataLesson;
use PublicApi\Infrastructure\Model\Language;
use PublicApi\Infrastructure\Model\Lesson;

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
     * @var WordRepository $wordRepository
     */
    private $wordRepository;
    /**
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
    /**
     * RepositoryCommunicator constructor.
     * @param LanguageRepository $languageRepository
     * @param LearningUserRepository $learningUserRepository
     * @param LessonRepository $lessonRepository
     * @param WordRepository $wordRepository
     * @param SerializerWrapper $serializerWrapper
     */
    public function __construct(
        LanguageRepository $languageRepository,
        LearningUserRepository $learningUserRepository,
        LessonRepository $lessonRepository,
        WordRepository $wordRepository,
        SerializerWrapper $serializerWrapper
    ) {
        $this->languageRepository = $languageRepository;
        $this->learningUserRepository = $learningUserRepository;
        $this->lessonRepository = $lessonRepository;
        $this->wordRepository = $wordRepository;
        $this->serializerWrapper = $serializerWrapper;
    }
    /**
     * @param Language $language
     * @return Lesson[]
     */
    public function getLessonsByLanguage(Language $language): array
    {
        $qb = $this->lessonRepository->createQueryBuilderFromClass('l');

        $metadataLessons = $qb
            ->innerJoin('l.course', 'c')
            ->where('c.id = l.course')
            ->andWhere('c.language = :language_id')
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
        $languages = $this->languageRepository->findAll();

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
                if ($this->equalsLanguage($language, $learningUser->getLanguage())) {
                    $temp['alreadyLearning'] = true;
                }
            }

            $viewable[] = $temp;
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