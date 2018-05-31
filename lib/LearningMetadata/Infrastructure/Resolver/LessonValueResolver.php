<?php

namespace LearningMetadata\Infrastructure\Resolver;

use AdminBundle\Entity\Lesson;
use LearningMetadata\Repository\Implementation\LessonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class LessonValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var LessonRepository $lessonRepository
     */
    private $lessonRepository;
    /**
     * @var Lesson $lesson
     */
    private $lesson;
    /**
     * LessonValueResolver constructor.
     * @param LessonRepository $lessonRepository
     */
    public function __construct(
        LessonRepository $lessonRepository
    ) {
        $this->lessonRepository = $lessonRepository;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (Lesson::class !== $argument->getType()) {
            return false;
        }

        $lessonId = $request->request->get('lessonId');

        if (is_null($lessonId)) {
            $lessonId = $request->query->get('lessonId');

            if (is_null($lessonId)) {
                $lessonId = $request->get('lessonId');

                if (is_null($lessonId)) {
                    return false;
                }
            }
        }

        $lesson = $this->lessonRepository->find((int) $lessonId);

        if (!$lesson instanceof Lesson) {
            return false;
        }

        $this->lesson = $lesson;

        return true;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return \Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        yield $this->lesson;
    }
}