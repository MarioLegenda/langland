<?php

namespace PublicApi\Lesson\Business\Implementation;

use AdminBundle\Entity\Lesson;
use BlueDot\Entity\PromiseInterface;
use Library\Infrastructure\Helper\CommonSerializer;
use Library\Infrastructure\Repository\RepositoryInterface;
use PublicApi\Lesson\Repository\LessonRepository;
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

        return $this->commonSerializer->serialize($lesson, $groups, $type);
    }

    private function serialize(Lesson $lesson, array $groups, string $type): string
    {
        return $this->commonSerializer->serialize($lesson, $groups, $type);
    }
}