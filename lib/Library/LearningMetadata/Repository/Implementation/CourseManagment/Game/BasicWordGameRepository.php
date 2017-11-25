<?php

namespace Library\LearningMetadata\Repository\Implementation\CourseManagment\Game;

use AdminBundle\Entity\BasicWordGame;
use Doctrine\ORM\EntityRepository;

class BasicWordGameRepository extends EntityRepository
{
    /**
     * @param BasicWordGame $basicWordGame
     * @return BasicWordGame
     */
    public function persistAndFlush(BasicWordGame $basicWordGame): BasicWordGame
    {
        $this->getEntityManager()->persist($basicWordGame);
        $this->getEntityManager()->flush($basicWordGame);

        return $basicWordGame;
    }
}