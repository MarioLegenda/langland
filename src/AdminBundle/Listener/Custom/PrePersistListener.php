<?php

namespace AdminBundle\Listener\Custom;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Lesson;
use AdminBundle\Event\MultipleEntityEvent;
use Doctrine\ORM\EntityManager;

class PrePersistListener
{
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * PrePersistListener constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param MultipleEntityEvent $event
     */
    public function onPersist(MultipleEntityEvent $event)
    {
        if (empty($event->getEntities())) {
            return;
        }

        if (array_key_exists('lesson', $event->getEntities())) {
            $this->handleLessonJob(
                $event->getEntities()['lesson'],
                $event->getEntities()['course']
            );
        }
    }

    private function handleLessonJob(Lesson $lesson, Course $course)
    {
        $lesson->setCourse($course);

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

            $this->em->flush();
        }
    }
}