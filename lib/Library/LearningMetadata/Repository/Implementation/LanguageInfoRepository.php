<?php

namespace Library\LearningMetadata\Repository\Implementation;

use AdminBundle\Entity\LanguageInfo;
use Doctrine\ORM\EntityRepository;

class LanguageInfoRepository extends EntityRepository
{
    /**
     * @param LanguageInfo $languageInfo
     */
    public function persistAndFlush(LanguageInfo $languageInfo)
    {
        $this->getEntityManager()->persist($languageInfo);
        $this->getEntityManager()->flush($languageInfo);
    }
    /**
     * @param LanguageInfo $languageInfo
     */
    public function removeAndFlush(LanguageInfo $languageInfo)
    {
        $this->getEntityManager()->remove($languageInfo);
        $this->getEntityManager()->flush();
    }
}