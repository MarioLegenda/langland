<?php

namespace AdminBundle\BlueDotCallable;

use AdminBundle\Helper\BlockCreator;
use BlueDot\Common\AbstractCallable;
use BlueDot\Entity\Entity;

class SearchCallable extends AbstractCallable
{
    /**
     * @return array|\BlueDot\Common\StorageInterface
     */
    public function run()
    {
        $languageId = $this->parameters['language_id'];
        $searchWord = $this->parameters['word'];
        $offset = $this->parameters['offset'];

        $exactWords = $this->blueDot->execute('simple.select.find_single_word_translation', array(
            'language_id' => $languageId,
            'search_word' => $searchWord,
            'offset' => (int) $offset,
        ))->getResult();

        if ($exactWords instanceof Entity) {
            $newWords = array();

            $ids = implode(',', $exactWords->extract('id')['id']);

            $translationsEntity = $this->blueDot
                ->createStatementBuilder()
                ->addSql(sprintf('SELECT * FROM translations WHERE word_id IN (%s)', $ids))
                ->execute()
                ->getResult();

            foreach ($exactWords as $word) {
                $wordId = $word['id'];

                $translations = $translationsEntity->extract('translation', function($translations) use ($wordId) {
                    return $translations['word_id'] === $wordId;
                })['translation'];

                $word['translations'] = $translations;

                $newWords[] = $word;
            }

            return BlockCreator::getBlocks($newWords);
        }

        $foundWords = $this->blueDot->execute('simple.select.find_word_by_pattern', array(
            'offset' => (int) $offset,
            'language_id' => $languageId,
            'search_word' => '%'.$searchWord.'%',
        ))->getResult();

        if (!$foundWords instanceof Entity) {
            return null;
        }

        $foundWordsArray = $foundWords->toArray();

        $ids = implode(',', $foundWords->extract('id')['id']);

        $translationsEntity = $this->blueDot
            ->createStatementBuilder()
            ->addSql(sprintf('SELECT * FROM translations WHERE word_id IN (%s)', $ids))
            ->execute()
            ->getResult();

        $newWords = array();
        foreach ($foundWordsArray as $word) {
            $trans = $translationsEntity->find('word_id', $word['id'])->toArray();

            $word['translations'][] = $trans['translation'];

            $newWords[] = $word;
        }

        return BlockCreator::getBlocks($newWords);
    }
}