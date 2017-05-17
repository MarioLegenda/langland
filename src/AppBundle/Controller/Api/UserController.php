<?php

namespace AppBundle\Controller\Api;

use AppBundle\Event\LearningUserCreateEvent;
use Symfony\Component\HttpFoundation\Request;

class UserController extends ResponseController
{
    public function findLoggedInUserAction()
    {
        return $this->createSuccessJsonResponse(
            $this->serialize($this->getUser(), array('exposed_user'))
        );
    }

    public function createLearningUserAction(Request $request)
    {
        $languageId = $request->request->get('languageId');

        $language = $this->getRepository('AdminBundle:Language')->find($languageId);

        $eventDispatcher = $this->get('event_dispatcher');
        $event = new LearningUserCreateEvent(
            $this->getManager(),
            $language,
            $this->getUser()
        );

        $eventDispatcher->dispatch(LearningUserCreateEvent::NAME, $event);

        return $this->createSuccessJsonResponse();
    }
}