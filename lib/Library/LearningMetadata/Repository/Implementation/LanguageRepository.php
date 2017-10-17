<?php

namespace Library\LearningMetadata\Repository\Implementation;

use AdminBundle\Entity\Language;
use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository
{
    /**
     * @param Language $language
     * @return Language
     */
    public function persistAndFlush(Language $language)
    {
        $this->getEntityManager()->persist($language);
        $this->getEntityManager()->flush($language);

        return $language;
    }
}