<?php

namespace Library;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommonController extends Controller
{
    /**
     * @param string $repository
     * @return EntityRepository
     */
    protected function getRepository(string $repository) : EntityRepository
    {
        return $this->get('doctrine')->getRepository($repository);
    }
    /**
     * @return EntityManager
     */
    protected function getManager() : EntityManager
    {
        return $this->get('doctrine')->getManager();
    }
}