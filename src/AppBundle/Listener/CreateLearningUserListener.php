<?php

namespace AppBundle\Listener;


use AppBundle\Entity\CourseHolder;
use AppBundle\Event\LearningUserCreateEvent;
use AppBundle\Entity\LearningUser;
use AppBundle\Entity\LearningUserCourse;

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

            $courses = $em->getRepository('AdminBundle:Course')->findBy(array(
                'language' => $language,
            ));

            $courseHolder = new CourseHolder();
            $courseHolder->setLanguage($language);

            foreach ($courses as $course) {
                $learningUserCourse = new LearningUserCourse();
                $learningUserCourse->setCourse($course);

                $courseHolder->addLearningUserCourse($learningUserCourse);
            }

            $learningUser->addCourseHolder($courseHolder);

            $learningUser->addLanguage($language);
            $learningUser->setCurrentLanguage($language);

            $em->persist($learningUser);
            $em->flush();

            return;
        }

        $learningUser = LearningUser::create($user, $language);

        $courses = $em->getRepository('AdminBundle:Course')->findBy(array(
            'language' => $language,
        ));

        $courseHolder = new CourseHolder();
        $courseHolder->setLanguage($language);

        foreach ($courses as $course) {
            $learningUserCourse = new LearningUserCourse();
            $learningUserCourse->setCourse($course);

            $courseHolder->addLearningUserCourse($learningUserCourse);
        }

        $learningUser->addCourseHolder($courseHolder);

        $learningUser->addLanguage($language);
        $learningUser->setCurrentLanguage($language);

        $em->persist($learningUser);
        $em->flush();;
    }
}