<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Language;
use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Entity\LanguageInfoText;

class LanguageInfoFactory
{
    use FakerTrait;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * LanguageInfoFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param Language $language
     */
    public function create(Language $language)
    {
        $languageInfo = new LanguageInfo();
        $languageInfo->setLanguage($language);
        $languageInfo->setName($this->getFaker()->word);

        for ($s = 0; $s < 5; $s++) {
            $text = new LanguageInfoText();
            $text->setName($language->getName().' language info '.$s);
            $text->setText($this->getFaker()->sentence(30));

            $text->setLanguageInfo($languageInfo);

            $languageInfo->addLanguageInfoText($text);
        }

        $this->em->flush();

        $this->em->persist($languageInfo);
    }
}