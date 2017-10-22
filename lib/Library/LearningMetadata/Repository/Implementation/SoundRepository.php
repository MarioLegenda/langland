<?php

namespace Library\LearningMetadata\Repository\Implementation;

use AdminBundle\Entity\Sound;
use Doctrine\ORM\EntityRepository;

class SoundRepository extends EntityRepository
{
    /**
     * @param Sound $sound
     */
    public function persistAndFlush(Sound $sound)
    {
        $this->getEntityManager()->persist($sound);
        $this->getEntityManager()->flush($sound);
    }
}