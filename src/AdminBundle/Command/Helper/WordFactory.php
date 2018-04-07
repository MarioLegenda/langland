<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Language;
use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\Word;

class WordFactory
{
    use FakerTrait;
    /**
     * @var Word[] $words
     */
    private $words = [];
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
     * @param bool $save
     * @param array $levels
     * @return array
     */
    public function create(
        CategoryFactory $categoryFactory,
        WordTranslationFactory $wordTranslationFactory,
        Language $language,
        int $numberOfEntries,
        bool $save = false,
        array $levels = [1, 2, 3, 4, 5]
    ) : array {
        $wordsArray = array();

        foreach ($levels as $level) {
            for ($i = 0; $i < $numberOfEntries; $i++) {
                $word = new Word();

                $word->setName($this->getFaker()->word);
                $word->setLanguage($language);
                $word->setLevel($level);
                $word->setPluralForm($this->getFaker()->word);

                $word->setCategories($categoryFactory->createCollection(2));
                $word->setDescription($this->getFaker()->sentence(60));
                $word->setType($this->getFaker()->company);

                $wordTranslationFactory->create($word, 5);

                $this->em->persist($word);

                $wordsArray[] = $word;
            }

            $this->em->flush();
        }

        if ($save) {
            $this->words = array_merge($this->words, $wordsArray);
        }

        return $wordsArray;
    }
    /**
     * @return Word[]
     */
    public function getSavedWords(): array
    {
        return $this->words;
    }
    /**
     * @void
     */
    public function clear()
    {
        $this->words = [];
    }
}