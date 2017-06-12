<?php

namespace AdminBundle\Listener\Custom;

use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Entity\Sentence;
use AdminBundle\Entity\Word;
use Library\Event\PreUpdateEvent;
use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Course;
use AdminBundle\Entity\Game\QuestionGame;

class PreUpdateListener extends AbstractEntityManagerBaseListener
{
    /**
     * @param PreUpdateEvent $event
     */
    public function onPreUpdate(PreUpdateEvent $event)
    {
        if (empty($event->getEntities())) {
            return;
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

        if ($event->hasEntity('languageInfo')) {
            $this->handleLanguageInfoJob($event->getEntity('languageInfo'));
        }

        if ($event->hasEntity('word')) {
            $this->handleWordJob($event->getEntity('word'));
        }

        if ($event->hasEntity('sentence')) {
            $this->handleSentenceJob($event->getEntity('sentence'));
        }
    }

    private function handleSentenceJob(Sentence $sentence)
    {
        $dbTranslations = $this->em->getRepository('AdminBundle:Sentence')->findBy(array(
            'sentence' => $sentence,
        ));

        foreach ($dbTranslations as $translation) {
            if (!$sentence->hasSentenceTranslation($translation)) {
                $this->em->remove($translation);
            }
        }
    }

    private function handleWordJob(Word $word)
    {
        $dbTranslations = $this->em->getRepository('AdminBundle:Translation')->findBy(array(
            'word' => $word,
        ));

        foreach ($dbTranslations as $translation) {
            if (!$word->hasTranslation($translation)) {
                $this->em->remove($translation);
            }
        }
    }

    private function handleLanguageInfoJob(LanguageInfo $languageInfo)
    {
        $dbLanguageInfoTexts = $this->em->getRepository('AdminBundle:LanguageInfoText')->findBy(array(
            'languageInfo' => $languageInfo,
        ));

        foreach ($dbLanguageInfoTexts as $text) {
            if (!$languageInfo->hasLanguageInfoText($text)) {
                $this->em->remove($text);
            }
        }

        $this->em->flush();
    }

    private function handleLessonJob(Lesson $lesson, Course $course)
    {
        foreach ($lesson->getLessonTexts() as $lessonText) {
            if (empty($lessonText->getName())) {
                $lesson->removeLessonText($lessonText);
            }
        }

        $dbLessonTexts = $this->em->getRepository('AdminBundle:LessonText')->findBy(array(
            'lesson' => $lesson,
        ));

        foreach ($dbLessonTexts as $text) {
            if (!$lesson->hasLessonText($text)) {
                $this->em->remove($text);
            }
        }

        if ($lesson->getIsInitialLesson()) {
            foreach ($course->getLessons() as $dbLesson) {
                $dbLesson->setIsInitialLesson(false);

                $this->em->persist($dbLesson);
            }

            $lesson->setIsInitialLesson(true);

            $this->em->flush();
        }
    }

    private function handleQuestionGameJob(QuestionGame $game)
    {
        foreach ($game->getAnswers() as $answer) {
            if (empty($answer->getName())) {
                $game->removeAnswer($answer);
            }
        }

        $dbAnswers = $this->em->getRepository('AdminBundle:Game\QuestionGameAnswer')->findBy(array(
            'question' => $game,
        ));

        foreach ($dbAnswers as $answer) {
            if (!$game->hasAnswer($answer)) {
                $this->em->remove($answer);
            }
        }
    }
}