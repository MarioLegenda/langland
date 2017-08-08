<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Category;
use AdminBundle\Form\Type\CategoryType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Resource\ResourceActions;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Component\Resource\Exception\UpdateHandlingException;

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
