<?php

namespace Armor\Repository;

use Library\Infrastructure\Repository\CommonRepository;
use PublicApiBundle\Entity\PublicApiLanguage;

class PublicApiLanguageRepository extends CommonRepository
{
    /**
     * @param PublicApiLanguage $publicApiLanguage
     * @return PublicApiLanguage
     */
    public function persistAndFlush(PublicApiLanguage $publicApiLanguage)
    {
        $this->persist($publicApiLanguage)->flush();

        return $publicApiLanguage;
    }
}