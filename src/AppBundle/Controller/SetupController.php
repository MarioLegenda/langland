<?php

namespace AppBundle\Controller;

use AdminBundle\Controller\RepositoryController;

class SetupController extends RepositoryController
{
    public function setupAction()
    {
        $existingLearningUser = $this->getRepository('AppBundle:LearningUser')->findBy(array(
            'user' => $this->getUser(),
        ));

        if (!empty($existingLearningUser)) {
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('::App/Setup/setupDashboard.html.twig');
    }
}