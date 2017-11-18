<?php

namespace Library\Lesson\Business\Controller;

use AdminBundle\Entity\Lesson;
use Library\Lesson\Business\Implementation\LessonImplementation;
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
     * @param int $id
     * @return Response
     */
    public function getLessonById(int $id)
    {
        /** @var Lesson $lesson */
        return new Response($this->lessonImplementation->findAndSerialize(
            $id,
            ['public_api'],
            'json'
        ), 200);
    }
}