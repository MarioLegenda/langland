<?php

namespace AppBundle\Controller\Api;

use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseController extends Controller
{
    public function findSignedCoursesAction()
    {
        $repo = $this->get('doctrine')->getRepository('AppBundle:LearningUser');

        $learningUser = $repo->findLearningUserByLoggedInUser($this->getUser());

        if (!$learningUser instanceof LearningUser) {
            return new JsonResponse(array(
                'status' => 'failure',
                'data' => array(),
            ));
        }

        $context = SerializationContext::create();
        $context->setGroups('course_data');

        $serialized = $this->get('jms_serializer')->serialize($learningUser, 'json', $context);

        return new JsonResponse(array(
            'status' => 'success',
            'data' => json_decode($serialized, true),
        ));
    }
}