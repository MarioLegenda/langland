<?php

namespace PublicApi\Lesson\Business\Controller;

use AdminBundle\Entity\Lesson;
use PublicApi\Lesson\Business\Implementation\LessonImplementation;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class LessonController
{
    /**
     * @var LessonImplementation $lessonImplementation
     */
    private $lessonImplementation;
    /**
     * LessonController constructor.
     * @param LessonImplementation $lessonImplementation
     */
    public function __construct(
        LessonImplementation $lessonImplementation
    ) {
        $this->lessonImplementation = $lessonImplementation;
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     * @param Lesson $lesson
     * @return Response
     */
    public function getLessonById(Lesson $lesson)
    {
        /** @var Lesson $lesson */
        return new Response($this->lessonImplementation->findAndSerialize(
            $lesson,
            ['public_api'],
            'json'
        ), 200);
    }
}