<?php

namespace AdminBundle\Controller;

use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;

class CourseController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction()
    {
        return $this->forward('SharedDataBundle:Course:create');
    }

    public function getAllAction()
    {
        return $this->forward('SharedDataBundle:Course:getAll');
    }

    public function getInitialCourseInfoAction()
    {
        return $this->forward('SharedDataBundle:Course:getInitialCourseInfo');
    }
}
