<?php

namespace AppBundle\Controller\Api;

use AdminBundle\Entity\Language;
use AppBundle\Entity\LearningUser;
use AppBundle\Event\LearningUserCreateEvent;
use Symfony\Component\HttpFoundation\Request;

class UserController extends CommonOperationController
{
    public function findLoggedInUserAction()
    {
        return $this->createSuccessJsonResponse(
            $this->serialize($this->getUser(), array('exposed_user'))
        );
    }

    public function findLearningUserAction(Request $request)
    {
        $responseCreator = $this->get('app_response_creator');

        if ($request->getMethod() !== 'GET') {
            $responseCreator->createMethodNotAllowedResponse();
        }

        $learningUser = $this->getLearningUser();

        if (!$learningUser instanceof LearningUser) {
            return $responseCreator->createNoContentResponse();
        }

        return $responseCreator->createSerializedResponse($learningUser, array('learning_user'));
    }

    public function createLearningUserAction(Request $request)
    {
        $responseCreator = $this->get('app_response_creator');

        if ($request->getMethod() !== 'POST') {
            return $responseCreator->createMethodNotAllowedResponse();
        }

        if (!$request->request->has('languageId')) {
            return $responseCreator->createBadRequestResponse();
        }

        $languageId = $request->request->get('languageId');

        $language = $this->getRepository('AdminBundle:Language')->find($languageId);

        if (!$language instanceof Language) {
            return $responseCreator->createNoContentResponse();
        }

        $eventDispatcher = $this->get('event_dispatcher');
        $event = new LearningUserCreateEvent(
            $this->getManager(),
            $language,
            $this->getUser()
        );

        $eventDispatcher->dispatch(LearningUserCreateEvent::NAME, $event);

        return $responseCreator->createContentAvailableResponse(null);
    }
}