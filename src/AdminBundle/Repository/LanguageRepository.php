<?php

namespace AdminBundle\Repository;

use AppBundle\Entity\LearningUser;
use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository
{
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