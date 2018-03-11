<?php

namespace Library\Listener;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Word;
use Library\Event\MultipleEntityEvent;

class EntityProcessorEventListener extends AbstractEntityManagerBaseListener
{
    /**
     * @param MultipleEntityEvent $event
     */
    public function onProcess(MultipleEntityEvent $event)
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

        if ($event->hasEntity('course')) {
            $this->handleCourseJob($event->getEntity('course'));
        }
    }

    private function handleCourseJob(Course $course)
    {
        $course->setCourseUrl(\URLify::filter($course->getName()));
    }

    private function handleLanguageInfoJob(LanguageInfo $languageInfo)
    {
        foreach ($languageInfo->getLanguageInfoTexts() as $languageInfoText) {
            if (empty($languageInfoText->getName())) {
                $languageInfo->removeLanguageInfoText($languageInfoText);
            }
        }

        $dbLanguageInfoTexts = $this->em->getRepository('AdminBundle:LanguageInfoText')->findBy(array(
            'languageInfo' => $languageInfo,
        ));

        if (!empty($dbLanguageInfoTexts)) {
            foreach ($dbLanguageInfoTexts as $text) {
                if (!$languageInfo->hasLanguageInfoText($text)) {
                    $this->em->remove($text);
                }
            }

            $this->em->flush();
        }
    }

    private function handleWordJob(Word $word)
    {
        foreach ($word->getTranslations() as $translation) {
            if (is_null($translation->getName())) {
                $word->removeTranslation($translation);
            }
        }

        $dbTranslations = $this->em->getRepository('AdminBundle:Translation')->findBy(array(
            'word' => $word,
        ));

        if (!empty($dbTranslations)) {
            foreach ($dbTranslations as $translation) {
                if (!$word->hasTranslation($translation)) {
                    $this->em->remove($translation);
                }
            }
        }

        if ($word->getLesson() !== null) {
            $lesson = $this->em->getRepository('AdminBundle:Lesson')->find($word->getLesson());
            $word->setLesson($lesson);
        }
    }

    private function handleLessonJob(Lesson $lesson, Course $course)
    {
        $lesson->setCourse($course);

        $lesson->setLessonUrl(\URLify::filter($lesson->getName()));

        foreach ($lesson->getLessonTexts() as $lessonText) {
            if (empty($lessonText->getName())) {
                $lesson->removeLessonText($lessonText);
            }
        }
    }
}