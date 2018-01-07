<?php

namespace LearningMetadata\Repository\Implementation;

use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Entity\Language;
use Doctrine\ORM\EntityRepository;

class LanguageInfoRepository extends EntityRepository
{
    /**
     * @param LanguageInfo $languageInfo
     * @return LanguageInfo
     */
    public function persistAndFlush(LanguageInfo $languageInfo)
    {
        $this->getEntityManager()->persist($languageInfo);
        $this->getEntityManager()->flush();

        return $languageInfo;
    }
    /**
     * @param LanguageInfo $languageInfo
     */
    public function removeAndFlush(LanguageInfo $languageInfo)
    {
        $this->getEntityManager()->remove($languageInfo);
        $this->getEntityManager()->flush();
    }
    /**
     * @param Language $language
     * @return null
     */
    public function findByLanguage(Language $language)
    {
        $languageInfo = $this->findBy(array(
            'language' => $language
        ));

        if (empty($languageInfo)) {
            return null;
        }

        return $languageInfo[0];
    }
}