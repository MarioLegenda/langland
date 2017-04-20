<?php

namespace AdminBundle\BlueDotCallable;

use BlueDot\Common\AbstractCallable;
use BlueDot\Exception\BlueDotRuntimeException;

class WordListCallable extends AbstractCallable
{
    public function run()
    {
        try {
            $entity = $this->blueDot->execute('scenario.find_words_complex', array(
                'find_working_language' => array(
                    'user_id' => $this->parameters['user_id'],
                ),
            ))->getResult();

            $result = $entity->normalizeJoinedResult(array(
                'linking_column' => 'id',
                'columns' => array('category', 'translation'),
            ), 'select_all_words');

            return $result;
        } catch (BlueDotRuntimeException $e) {
            return array();
        }
    }
}