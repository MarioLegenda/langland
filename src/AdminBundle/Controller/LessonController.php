<?php

namespace AdminBundle\Controller;

use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;

class LessonController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction()
    {
        return $this->forward('SharedDataBundle:Lesson:create');
    }

    public function renameLessonAction()
    {
        return $this->forward('SharedDataBundle:Lesson:renameLesson');
    }

    public function findLessonsByClassAction()
    {
        return $this->forward('SharedDataBundle:Lesson:findLessonsByClass');
    }
}
