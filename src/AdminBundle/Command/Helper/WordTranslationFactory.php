<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Word;
use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\Translation;

class WordTranslationFactory
{
    use FakerTrait;
    /**
     * @param Word $word
     * @param int $numberOfEntries
     */
    public function create(Word $word, int $numberOfEntries)
    {
        for ($i = 0; $i < $numberOfEntries; $i++) {
            $translation = new Translation();
            $translation->setWord($word);
            $translation->setName($this->getFaker()->word);

            $word->addTranslation($translation);
        }
    }
}