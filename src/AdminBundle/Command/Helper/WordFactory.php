<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Language;
use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\Word;

class WordFactory
{
    use FakerTrait;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * WordFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param CategoryFactory $categoryFactory
     * @param WordTranslationFactory $wordTranslationFactory
     * @param Language $language
     * @param int $numberOfEntries
     * @return array
     */
    public function create(CategoryFactory $categoryFactory, WordTranslationFactory $wordTranslationFactory, Language $language, int $numberOfEntries) : array
    {
        $wordsArray = array();

        for ($i = 0; $i < $numberOfEntries; $i++) {
            $word = new Word();
            $word->setName($this->getFaker()->word);
            $word->setLanguage($language);
            $word->setPluralForm($this->getFaker()->word);

            $word->setCategories($categoryFactory->createCollection(2));
            $word->setDescription($this->getFaker()->sentence(60));
            $word->setType($this->getFaker()->company);

            $wordTranslationFactory->create($word, 5);

            $this->em->persist($word);

            $wordsArray[] = $word;
        }

        $this->em->flush();

        return $wordsArray;
    }
}