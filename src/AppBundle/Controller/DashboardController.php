<?php

namespace AppBundle\Controller;

use AdminBundle\Controller\RepositoryController;
use AppBundle\Entity\LearningUser;

class DashboardController extends RepositoryController
{
    public function dashboardAction()
    {
        return $this->render('::App/Dashboard/dashboard.html.twig');
    }
}
