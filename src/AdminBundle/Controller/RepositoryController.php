<?php

namespace AdminBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RepositoryController extends Controller
{
    /**
     * @param string $repository
     * @return EntityRepository
     */
    public function getRepository(string $repository) : EntityRepository
    {
        return $this->get('doctrine')->getRepository($repository);
    }
}