<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function dashboardAction()
    {
        return array(
            'template' => '::Admin/dashboard.html.twig',
            'data' => array(),
        );
    }
}
