<?php

namespace PublicApi\LearningUser\Business\Implementation;

use PublicApi\Infrastructure\Type\CourseType;
use PublicApi\LearningUser\Repository\LearningMetadataRepository;
use PublicApi\Lesson\Repository\BlueDot\LessonRepository;
use PublicApiBundle\Entity\LearningUser;

class LearningMetadataImplementation
{
    /**
     * @var LearningMetadataRepository $learningMetadataRepository
     */
    private $learningMetadataRepository;
    /**
     * @var LessonRepository $lessonRepository
     */
    private $lessonRepository;
    /**
     * LearningMetadataImplementation constructor.
     * @param LearningMetadataRepository $learningMetadataRepository
     * @param LessonRepository $lessonRepository
     */
    public function __construct(
        LearningMetadataRepository $learningMetadataRepository,
        LessonRepository $lessonRepository
    ) {
        $this->learningMetadataRepository = $learningMetadataRepository;
        $this->lessonRepository = $lessonRepository;
    }
    /**
     * @param LearningUser $learningUser
     */
    public function createFirstLearningMetadata(LearningUser $learningUser)
    {
        $this->learningMetadataRepository->createLearningMetadata([
            'course_type' => (string) CourseType::fromValue('Beginner'),
            'learning_order' => 1,
            'language_id' => $learningUser->getLanguage()->getId(),
            'lesson_id' => 1,
            'learning_user_id' => $learningUser->getId(),
        ]);
    }
}