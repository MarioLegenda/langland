<?php

namespace AdminBundle\Controller;

use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;

class TheoryController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction()
    {
        return $this->forward('SharedDataBundle:Theory:create');
    }

    public function renameAction()
    {
        return $this->forward('SharedDataBundle:Theory:rename');
    }

    public function createTheoryDeckAction()
    {
        return $this->forward('SharedDataBundle:Theory:createTheoryDeck');
    }

    public function findAllDecksByTheoryAction()
    {
        return $this->forward('SharedDataBundle:Theory:findAllDecksByTheory');
    }

    public function findDeckAction()
    {
        return $this->forward('SharedDataBundle:Theory:findDeck');
    }

    public function findDeckByInternalNameAction()
    {
        return $this->forward('SharedDataBundle:Theory:findDeckByInternalName');
    }

    public function findAllByLessonAction()
    {
        return $this->forward('SharedDataBundle:Theory:findAllByLesson');
    }

    public function findDeckSoundsAction()
    {
        return $this->forward('SharedDataBundle:Theory:findDeckSounds');
    }
}
