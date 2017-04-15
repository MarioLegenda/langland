<?php

namespace AdminBundle\BlueDotCallable;

use BlueDot\Common\AbstractCallable;

class LastWordsCallable extends AbstractCallable
{
    /**
     * @return mixed
     */
    public function run()
    {
        $languageId = $this->parameters['language_id'];
        $offset = $this->parameters['offset'];

        $words = $this->blueDot->execute('simple.select.find_last_words', array(
            'language_id' => $languageId,
            'offset' => (int) $offset,
        ))->getResult();

        if (is_null($words)) {
            return null;
        }

        $wordsArray = $words->toArray();

        $wordIds = $words->extract('id');

        $translations = $this->blueDot->createStatementBuilder()
            ->addSql(sprintf('SELECT word_id, translation FROM translations WHERE word_id IN (%s)', implode(', ', $wordIds['id'])))
            ->execute()
            ->getResult();

        $wordCategories = $this->blueDot
            ->createStatementBuilder()
            ->addSql(sprintf('SELECT wc.word_id, c.category FROM word_category AS wc INNER JOIN categories AS c INNER JOIN words AS w ON wc.category_id = c.id AND wc.word_id = w.id WHERE wc.word_id IN (%s)', implode(', ', $wordIds['id'])))
            ->execute()
            ->getResult();

        $words = array();
        foreach ($wordsArray as $key => $word) {
            $wordId = $word['id'];

            $word['translations'] = $translations->extract('translation', function($translation) use ($wordId) {
                return $translation['word_id'] === $wordId;
            })['translation'];

            $category = $wordCategories->extract('category', function($categories) use ($wordId) {
                return $categories['word_id'] === $wordId;
            });

            if (!is_null($category)) {
                $word['category'] = $category['category'][0];
            }

            $words[] = $word;
        }

        return $this->createWordBlocks($words, count($wordsArray));
    }

    private function createWordBlocks(array $words, int $wordCount)
    {
        $wordBlocks = array();
        $temp = array();
        foreach ($words as $index => $word) {
            if ($index === 0) {
                $temp[] = $word;

                continue;
            }

            if (($index % 4) === 0) {
                $wordBlocks[] = $temp;
                $temp = array();

                $temp[] = $word;

                continue;
            }

            $temp[] = $word;
        }

        if (!empty($temp)) {
            $wordBlocks[] = $temp;
        }

        return array(
            'word_blocks' => $wordBlocks,
            'word_count' => $wordCount,
        );
    }
}