<?php

namespace AppBundle\Controller\Ajax;

use AdminBundle\Controller\RepositoryController;
use AppBundle\Entity\LearningUser;
use AppBundle\Entity\LearningUserPreference;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SetupController extends RepositoryController
{
    public function createLearningUserAction(Request $request)
    {
        if (!$request->request->has('duration') or !$request->request->has('language')) {
            throw $this->createNotFoundException();
        }

        $duration = $request->request->get('duration');
        $languageId = $request->request->get('language');

        $language = $this->getRepository('AdminBundle:Language')->find($languageId);

        $preferences = new LearningUserPreference();
        $preferences->setInvestingTime($duration);

        $learningUser = new LearningUser();
        $learningUser->addLanguage($language);
        $learningUser->setUserPreference($preferences);
        $learningUser->setUser($this->getUser());

        $em = $this->get('doctrine')->getManager();

        $em->persist($learningUser);
        $em->flush();

        return new JsonResponse(array(
            'status' => 'redirect',
            'redirect_url' => 'http://'.$this->getParameter('host').$this->generateUrl('app_dashboard')
        ));
    }
}