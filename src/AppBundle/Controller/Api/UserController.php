<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUser;
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
        $em = $this->get('doctrine')->getManager();
        $languageId = $request->request->get('languageId');

        $language = $this->getRepository('AdminBundle:Language')->find($languageId);
        $learningUserRepo = $this->getRepository('AppBundle:LearningUser');

        $existingLearningUser = $learningUserRepo->findLearningUserByLanguage($language);

        if (!empty($existingLearningUser)) {
            return $this->createSuccessJsonResponse();
        }

        $em->persist(LearningUser::create($this->getUser(), $language));
        $em->flush();

        return $this->createSuccessJsonResponse();
    }
}