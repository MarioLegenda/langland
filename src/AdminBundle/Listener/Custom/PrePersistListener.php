<?php

namespace AdminBundle\Listener\Custom;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Game\QuestionGame;
use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Sentence;
use AdminBundle\Entity\Word;
use AdminBundle\Event\MultipleEntityEvent;

class PrePersistListener extends AbstractEntityManagerBaseListener
{
    /**
     * @param MultipleEntityEvent $event
     */
    public function onPrePersist(MultipleEntityEvent $event)
    {
        if (empty($event->getEntities())) {
            return;
        }

        if ($event->hasEntity('languageInfo')) {
            $this->handleLanguageInfoJob($event->getEntity('languageInfo'));
        }

        if ($event->hasEntity('lesson')) {
            $this->handleLessonJob(
                $event->getEntity('lesson'),
                $event->getEntity('course')
            );
        }

        if ($event->hasEntity('questionGame')) {
            $this->handleQuestionGameJob($event->getEntity('questionGame'));
        }

        if ($event->hasEntity('sentence')) {
            $this->handleSentenceJob(
                $event->getEntity('sentence'),
                $event->getEntity('course')
            );
        }

        if ($event->hasEntity('word')) {
            $this->handleWordJob($event->getEntity('word'));
        }
    }

    private function handleWordJob(Word $word)
    {
        foreach ($word->getTranslations() as $translation) {
            if (is_null($translation->getName())) {
                $word->removeTranslation($translation);
            }
        }
    }

    private function handleLanguageInfoJob(LanguageInfo $languageInfo)
    {
        foreach ($languageInfo->getLanguageInfoTexts() as $languageInfoText) {
            if (empty($languageInfoText->getName())) {
                $languageInfo->removeLanguageInfoText($languageInfoText);
            }
        }
    }

    private function handleSentenceJob(Sentence $sentence, Course $course)
    {
        $sentence->setCourse($course);

        foreach ($sentence->getSentenceTranslations() as $translation) {
            if (empty($translation->getName())) {
                $sentence->removeSentenceTranslation($translation);
            }
        }
    }

    private function handleQuestionGameJob(QuestionGame $game)
    {
        foreach ($game->getAnswers() as $answer) {
            if (empty($answer->getName())) {
                $game->removeAnswer($answer);
            }
        }
    }

    private function handleLessonJob(Lesson $lesson, Course $course)
    {
        $lesson->setCourse($course);

        foreach ($lesson->getLessonTexts() as $lessonText) {
            if (empty($lessonText->getName())) {
                $lesson->removeLessonText($lessonText);
            }
        }
    }
}