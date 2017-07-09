<?php

namespace AppBundle\Controller\Api;

use AdminBundle\Entity\Language;
use AppBundle\Entity\LearningUser;
use AppBundle\Event\LearningUserCreateEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends CommonOperationController
{
    public function findLoggedInUserAction(Request $request)
    {
        $responseCreator = $this->get('app_response_creator');

        if ($request->getMethod() !== 'GET') {
            return $responseCreator->createMethodNotAllowedResponse();
        }

        return $responseCreator->createSerializedResponse($this->getUser(), array('exposed_user'));
    }

    public function createLearningUserAction(Request $request)
    {
        return $this->doCreateLearningUser($request);
    }

    public function getLearningUserAction()
    {
        return $this->doGetLearningUser();
    }

    public function doGetLearningUser()
    {
        $responseCreator = $this->get('app_response_creator');

        $learningUser = $this->getLearningUser();

        if (!$learningUser instanceof LearningUser) {
            return $responseCreator->createNoResourceResponse();
        }

        return $responseCreator->createSerializedResponse($learningUser, array('learning_user'));
    }

    private function doCreateLearningUser(Request $request)
    {
        $responseCreator = $this->get('app_response_creator');

        if (!$request->request->has('languageId')) {
            return $responseCreator->createBadRequestResponse();
        }

        $languageId = $request->request->get('languageId');

        $language = $this->getRepository('AdminBundle:Language')->find($languageId);

        if (!$language instanceof Language) {
            return $responseCreator->createNoResourceResponse();
        }

        $eventDispatcher = $this->get('event_dispatcher');
        $event = new LearningUserCreateEvent(
            $this->getManager(),
            $language,
            $this->getUser()
        );

        $eventDispatcher->dispatch(LearningUserCreateEvent::NAME, $event);

        $location = $this->get('router')->generate('app_page_course_dashboard', array(
            'languageName' => $language->getName(),
            'languageId' => $language->getId(),
        ));

        $response = $responseCreator->createResourceCreatedResponse();

        $response->headers->set('Location', $location);

        return $response;
    }
}