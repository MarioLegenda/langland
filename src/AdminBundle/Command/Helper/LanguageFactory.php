<?php

namespace AdminBundle\Command\Helper;

use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\Language;

class LanguageFactory
{
    use FakerTrait;
    /**
     * @var array $languageObjects
     */
    private $languageObjects;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * LanguageFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param array $languages
     * @param bool $save
     * @return array
     */
    public function create(array $languages, bool $save = false) : array
    {
        $languageObjects = array();
        foreach ($languages as $lang) {
            $language = new Language();
            $language->setName($lang);
            $language->setShowOnPage(true);
            $language->setListDescription($this->getFaker()->sentence(60));

            $this->em->persist($language);

            $languageObjects[] = $language;
        }

        if ($save) {
            $this->languageObjects = $languageObjects;
        }

        $this->em->flush();

        return $languageObjects;
    }
}