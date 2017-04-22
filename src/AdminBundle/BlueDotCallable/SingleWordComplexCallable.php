<?php

namespace AdminBundle\BlueDotCallable;

use BlueDot\Common\AbstractCallable;

class SingleWordComplexCallable extends AbstractCallable
{
    public function run()
    {
        $entity = $this->blueDot->execute('scenario.find_single_word_complex', array(
            'find_working_language' => array('user_id' => $this->parameters['user_id']),
            'select_word' => array('word_id' => $this->parameters['word_id']),
        ))->getResult();

        $result =  $entity->normalizeJoinedResult(array(
            'linking_column' => 'id',
            'columns' => array('translation', 'category'),
        ), 'select_word');

        if (!empty($result)) {
            unset($result[0]['id']);

            $result = $result[0];

            $result['db_image'] = array(
                'relative_full_path' => $result['relative_full_path'],
                'original_name' => $result['original_name'],
            );

            unset($result['relative_full_path']);
            unset($result['original_name']);

            $translations = $result['translation'];
            $realTranslations = array();
            foreach ($translations as $translation) {
                $realTranslations[] = array(
                    'translation' => $translation
                );
            }

            $result['translations'] = $realTranslations;

            return $result;
        }

        return $result;
    }
}