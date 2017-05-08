<?php

namespace AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository
{
    /**
     * @return array|null
     */
    public function findLearnableLanguages()
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