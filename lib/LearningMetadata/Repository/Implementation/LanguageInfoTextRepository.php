<?php

namespace LearningMetadata\Repository\Implementation;

use AdminBundle\Entity\LanguageInfoText;
use Doctrine\ORM\EntityRepository;

class LanguageInfoTextRepository extends EntityRepository
{
    /**
     * @param LanguageInfoText $infoText
     */
    public function removeAndFlush(LanguageInfoText $infoText)
    {
        $this->getEntityManager()->remove($infoText);
        $this->getEntityManager()->flush($infoText);
    }
}