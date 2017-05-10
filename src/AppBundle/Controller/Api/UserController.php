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

        $existingLearningUser = $learningUserRepo->findLearningUserByLoggedInUser($this->getUser());

        if (!empty($existingLearningUser)) {
            if ($existingLearningUser->hasLanguage($language)) {
                return $this->createFailedJsonResponse();
            }

            $existingLearningUser->addLanguage($language);
            $existingLearningUser->setCurrentLanguage($language);

            $em->persist($existingLearningUser);
            $em->flush();

            return $this->createSuccessJsonResponse();
        }

        $em->persist(LearningUser::create($this->getUser(), $language));
        $em->flush();

        return $this->createSuccessJsonResponse();
    }
}