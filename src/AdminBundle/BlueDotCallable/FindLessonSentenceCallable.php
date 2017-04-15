<?php

namespace AdminBundle\BlueDotCallable;

use BlueDot\Common\AbstractCallable;
use BlueDot\Entity\PromiseInterface;

class FindLessonSentenceCallable extends AbstractCallable
{
    public function run()
    {
        $internalName = $this->parameters['internal_name'];

        $internalNamePromise = $this->blueDot->execute('simple.select.find_sentence_by_internal_name', array(
            'internal_name' => $internalName,
            'lesson_id' => $this->parameters['lesson_id'],
        ));

        if ($internalNamePromise->isFailure()) {
            return null;
        }

        $lessonSentenceId = $internalNamePromise->getResult()->find('internal_name', $internalName)->get('id');

        $lessonSentencePromise = $this->blueDot->execute('simple.select.find_lesson_sentence_by_id', array(
            'id' => $lessonSentenceId,
        ));

        if ($lessonSentencePromise->isFailure()) {
            return null;
        }

        if ($lessonSentencePromise->isSuccess()) {
            return $this->findSentence($lessonSentencePromise, $lessonSentenceId);
        }
    }

    private function findSentence(PromiseInterface $lessonSentencePromise, $lessonSentenceId)
    {
        $lessonSentence = $lessonSentencePromise->getResult()->normalizeIfOneExists();

        $sentence = $this->blueDot->execute('simple.select.find_sentence_by_id', array(
            'id' => $lessonSentence->get('sentence_id'),
        ))->getResult()->normalizeIfOneExists();

        $translations = $this->blueDot->execute('simple.select.find_lesson_sentence_translations', array(
            'lesson_sentence_id' => $lessonSentenceId,
        ))->getResult();

        return array(
            'lesson_sentence_id' => $lessonSentence->get('id'),
            'internal_name' => $lessonSentence->get('internal_name'),
            'sentence' => $sentence->get('sentence'),
            'translations' => $translations->toArray(),
        );
    }
}