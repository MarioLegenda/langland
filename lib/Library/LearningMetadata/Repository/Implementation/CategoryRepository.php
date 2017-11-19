<?php

namespace Library\LearningMetadata\Repository\Implementation;

use AdminBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    /**
     * @param Category $category
     */
    public function persistAndFlush(Category $category)
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
    }
}