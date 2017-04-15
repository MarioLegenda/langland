<?php

namespace AdminBundle\BlueDotCallable;

use BlueDot\Common\AbstractCallable;

class UpdateLessonSentenceCallable extends AbstractCallable
{
    public function run()
    {
        $data = $this->parameters['data'];

        $sentence = $data['sentence'];
        $lessonSentenceId = $data['lesson_sentence_id'];
        $internalName = $data['internal_name'];

        $foundSentence = $this->blueDot->execute('simple.select.find_lesson_sentence_by_id', array(
            'id' => $lessonSentenceId,
        ))->getResult()->find('id', $lessonSentenceId);

        $this->blueDot->execute('simple.update.update_sentence', array(
            'sentence' => $sentence,
            'sentence_id' => $foundSentence->get('sentence_id'),
        ));

        $this->blueDot->execute('simple.update.update_lesson_sentence', array(
            'internal_name' => $internalName,
            'id' => $lessonSentenceId,
        ));

        return array(
            'sentence' => $sentence,
            'lesson_sentence_id' => $lessonSentenceId,
            'internal_name' => $internalName,
        );
    }
}