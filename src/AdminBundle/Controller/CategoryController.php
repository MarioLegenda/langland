<?php

namespace AdminBundle\Controller;

class CategoryController extends GenericResourceController implements GenericControllerInterface
{
    /**
     * @return string
     */
    public function getListingTitle() : string
    {
        return 'Categories';
    }
    /**
     * @return string
     */
    public function getName() : string
    {
        return 'Category';
    }
}
