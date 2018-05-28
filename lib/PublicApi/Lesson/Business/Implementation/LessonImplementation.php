<?php

namespace PublicApi\Lesson\Business\Implementation;

use AdminBundle\Entity\Lesson;
use BlueDot\Entity\PromiseInterface;
use Library\Infrastructure\Helper\SerializerWrapper;
use Library\Infrastructure\Repository\RepositoryInterface;
use PublicApi\Lesson\Repository\LessonRepository;

class LessonImplementation
{
    /**
     * @var RepositoryInterface $lessonRepository
     */
    private $lessonRepository;
    /**
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
    /**
     * LessonImplementation constructor.
     * @param LessonRepository $lessonRepository
     * @param SerializerWrapper $serializerWrapper
     */
    public function __construct(
        LessonRepository $lessonRepository,
        SerializerWrapper $serializerWrapper
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->serializerWrapper = $serializerWrapper;
    }
    /**
     * @param int $id
     * @return Lesson
     */
    public function find(int $id): Lesson
    {
        /** @var PromiseInterface $promise */
        $lesson = $this->lessonRepository->find($id);

        if (!$lesson instanceof Lesson) {
            throw new \RuntimeException('Lesson not found');
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
        $lesson = $this->lessonRepository->find($id);

        return $lesson;
    }
    /**
     * @param Lesson|int $lesson
     * @param array $groups
     * @param string $type
     * @throws \RuntimeException
     * @return string
     */
    public function findAndSerialize($lesson, array $groups, string $type): string
    {
        if ($lesson instanceof Lesson) {
            return $this->serialize($lesson, $groups, $type);
        }

        if (is_int($lesson)) {
            return $this->serialize($this->find($lesson), $groups, $type);
        }

        throw new \RuntimeException('Lesson not found');
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

        return $this->serializerWrapper->serialize($lesson, $groups, $type);
    }
    /**
     * @param Lesson $lesson
     * @param array $groups
     * @param string $type
     * @return string
     */
    private function serialize(Lesson $lesson, array $groups, string $type): string
    {
        return $this->serializerWrapper->serialize($lesson, $groups, $type);
    }
}