<?php

namespace AppBundle\Controller;

use Library\ResponseController;

class DashboardController extends ResponseController
{
    public function dashboardAction()
    {
        return $this->render('::App/Dashboard/dashboard.html.twig');
    }
}
