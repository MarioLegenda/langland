<?php

namespace Library\Lesson\Business\Implementation;

use AdminBundle\Entity\Lesson;
use Library\Infrastructure\Helper\CommonSerializer;
use Library\Infrastructure\Repository\RepositoryInterface;
use Library\Lesson\Repository\LessonRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LessonImplementation
{
    /**
     * @var RepositoryInterface $lessonRepository
     */
    private $lessonRepository;
    /**
     * @var CommonSerializer $commonSerializer
     */
    private $commonSerializer;
    /**
     * LessonImplementation constructor.
     * @param LessonRepository $lessonRepository
     * @param CommonSerializer $serializer
     */
    public function __construct(
        LessonRepository $lessonRepository,
        CommonSerializer $serializer
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->commonSerializer = $serializer;
    }
    /**
     * @param int $id
     * @return Lesson
     */
    public function find(int $id): Lesson
    {
        /** @var Lesson $lesson */
        $lesson = $this->lessonRepository->find($id, 'simple.select.find_lesson_by_id');

        if (!$lesson instanceof Lesson) {
            throw new NotFoundHttpException();
        }

        return $lesson;
    }
    /**
     * @param int $id
     * @return Lesson|null
     */
    public function tryFind(int $id): ?Lesson
    {
        /** @var Lesson $lesson */
        $lesson = $this->lessonRepository->find($id, 'simple.select.find_lesson_by_id');

        return $lesson;
    }
    /**
     * @param int $id
     * @param array $groups
     * @param string $type
     * @return string
     */
    public function findAndSerialize(int $id, array $groups, string $type): string
    {
        $lesson = $this->find($id);

        return $this->commonSerializer->serialize($lesson, $groups, $type);
    }
    /**
     * @param int $id
     * @param array $groups
     * @param string $type
     * @return string
     */
    public function tryFindAndSerialize(int $id, array $groups, string $type): string
    {
        $lesson = $this->tryFind($id);

        return $this->commonSerializer->serialize($lesson, $groups, $type);
    }
}