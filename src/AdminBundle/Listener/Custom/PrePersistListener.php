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

        if (array_key_exists('languageInfo', $event->getEntities())) {
            $this->handleLanguageInfoJob($event->getEntities()['languageInfo']);
        }

        if (array_key_exists('lesson', $event->getEntities())) {
            $this->handleLessonJob(
                $event->getEntities()['lesson'],
                $event->getEntities()['course']
            );
        }

        if (array_key_exists('questionGame', $event->getEntities())) {
            $this->handleQuestionGameJob($event->getEntities()['questionGame']);
        }

        if (array_key_exists('sentence', $event->getEntities())) {
            $this->handleSentenceJob(
                $event->getEntities()['sentence'],
                $event->getEntities()['course']
            );
        }

        if (array_key_exists('word', $event->getEntities())) {
            $this->handleWordJob($event->getEntities()['word']);
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