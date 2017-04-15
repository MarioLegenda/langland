<?php

namespace AdminBundle\Controller;

use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;

class ClassController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction()
    {
        return $this->forward('SharedDataBundle:Class:create');
    }

    public function updateAction()
    {
        return $this->forward('SharedDataBundle:Class:update');
    }

    public function findClassesByCourseAction()
    {
        return $this->forward('SharedDataBundle:Class:findClassesByCourse');
    }
}
