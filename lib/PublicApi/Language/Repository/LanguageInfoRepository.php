<?php

namespace PublicApi\Language\Repository;

use AdminBundle\Entity\LanguageInfo;
use Library\Infrastructure\Repository\CommonRepository;

class LanguageInfoRepository extends CommonRepository
{
    /**
     * @param LanguageInfo $languageInfo
     * @return LanguageInfo
     */
    public function persistAndFlush(LanguageInfo $languageInfo): LanguageInfo
    {
        $this->em->persist($languageInfo);
        $this->em->flush();

        return $languageInfo;
    }
}