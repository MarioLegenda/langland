<?php

namespace AdminBundle\Controller;

use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;

class SearchController extends MasterSecurityController implements AdminAuthInterface
{
    public function searchAction()
    {
        return $this->forward('SharedDataBundle:Search:search');
    }

    public function findLastWordsAction()
    {
        return $this->forward('SharedDataBundle:Search:findLastWords');
    }
}
