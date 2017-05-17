<?php

namespace AppBundle\Listener;


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

        $learningUserRepo = $em->getRepository('AppBundle:LearningUser');

        $existingLearningUser = $learningUserRepo->findOneBy(array(
            'currentLanguage' => $language,
        ));

        if (!empty($existingLearningUser)) {
            if ($existingLearningUser->hasLanguage($language)) {
                return;
            }

            $existingLearningUser->addLanguage($language);
            $existingLearningUser->setCurrentLanguage($language);

            $em->persist($existingLearningUser);
            $em->flush();

            return;
        }

        $learningUser = LearningUser::create($user, $language);

        $courses = $em->getRepository('AdminBundle:Course')->findBy(array(
            'language' => $language,
        ));

        foreach ($courses as $course) {
            $learningUserCourse = new LearningUserCourse();
            $learningUserCourse->setLearningUser($learningUser);
            $learningUserCourse->setCourse($course);

            $learningUser->addLearningUserCourse($learningUserCourse);
        }

        $em->persist($learningUser);
        $em->flush();;
    }
}