<?php

namespace Library\LearningMetadata\Repository\Implementation;

use AdminBundle\Entity\Word;
use Doctrine\ORM\EntityRepository;

class WordRepository extends EntityRepository
{
    /**
     * @param Word $word
     */
    public function persistAndFlush(Word $word)
    {
        $this->getEntityManager()->persist($word);
        $this->getEntityManager()->flush($word);
    }
}