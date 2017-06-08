<?php

namespace AppBundle\Listener;


use AdminBundle\Entity\Language;
use AppBundle\Entity\CourseHolder;
use AppBundle\Entity\LearningUserLesson;
use AppBundle\Event\LearningUserCreateEvent;
use AppBundle\Entity\LearningUser;
use AppBundle\Entity\LearningUserCourse;
use Doctrine\ORM\EntityManager;

class CreateLearningUserListener
{
    /**
     * @param LearningUserCreateEvent $event
     */
    public function onLearningUserCreate(LearningUserCreateEvent $event)
    {
        $em = $event->getEntityManager();
        $language = $event->getLanguage();
        $user = $event->getUser();

        $learningUser = $em->getRepository('AppBundle:LearningUser')->findOneBy(array(
            'user' => $user,
        ));

        if ($learningUser instanceof LearningUser) {
            if ($learningUser->hasLanguage($language)) {
                return;
            }

            $this->addDependencies($learningUser, $em, $language);

            $em->persist($learningUser);
            $em->flush();

            return;
        }

        $learningUser = LearningUser::create($user, $language);

        $this->addDependencies($learningUser, $em, $language);

        $em->persist($learningUser);
        $em->flush();;
    }

    private function addDependencies(LearningUser $learningUser, EntityManager $em, Language $language)
    {
        $courses = $em->getRepository('AdminBundle:Course')->findBy(array(
            'language' => $language,
        ));

        $courseHolder = new CourseHolder();
        $courseHolder->setLanguage($language);

        foreach ($courses as $course) {
            $learningUserCourse = new LearningUserCourse();
            $learningUserCourse->setCourse($course);

            $courseHolder->addLearningUserCourse($learningUserCourse);

            $lessons = $course->getLessons();

            foreach ($lessons as $lesson) {
                $learningUserLesson = new LearningUserLesson();

                if ($lesson->getIsInitialLesson() === true) {
                    $learningUserLesson->setIsEligable(true);
                }

                $learningUserLesson->setLesson($lesson);

                $learningUserCourse->addLearningUserLesson($learningUserLesson);
            }
        }

        $learningUser->addCourseHolder($courseHolder);

        $learningUser->addLanguage($language);
        $learningUser->setCurrentLanguage($language);
    }
}