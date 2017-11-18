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
    public function persistAndFlush(Language $language): Language
    {
        $this->getEntityManager()->persist($language);
        $this->getEntityManager()->flush($language);

        return $language;
    }
    /**
     * @return array|null
     */
    public function findViewableLanguages()
    {
        $languages = $this->findBy(array(
            'showOnPage' => true,
        ));

        if (empty($languages)) {
            return null;
        }

        return $languages;
    }
}