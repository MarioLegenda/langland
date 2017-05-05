<?php

namespace AppBundle\Controller;

use AdminBundle\Controller\RepositoryController;

class DashboardController extends RepositoryController
{
    public function dashboardAction()
    {
        $learningUserRepository = $this->getRepository('AppBundle:LearningUser');
        $learningUser = $learningUserRepository->findLearningUserByLoggedInUser($this->getUser());

        if (empty($learningUser)) {
            return $this->redirectToRoute('app_setup');
        }

        return $this->render('::App/Dashboard/dashboard.html.twig');
    }
}
