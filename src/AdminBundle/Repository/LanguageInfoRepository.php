<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\Language;
use Doctrine\ORM\EntityRepository;

class LanguageInfoRepository extends EntityRepository
{
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