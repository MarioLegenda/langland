<?php

namespace PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PublicController extends Controller
{
    public function homeAction()
    {
        return $this->render('::Public/Home/home.html.twig');
    }
}