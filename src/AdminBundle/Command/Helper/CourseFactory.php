<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Language;
use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\Course;

class CourseFactory
{
    use FakerTrait;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * CourseFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param Language $language
     * @param int $numberOfEntries
     * @return array
     */
    public function create(Language $language, int $numberOfEntries) : array
    {
        $courseArray = array();

        for ($i = 0; $i < $numberOfEntries; $i++) {
            $course = new Course();

            if ($i === 0) {
                $course->setInitialCourse(true);
            }

            $course->setName($this->getFaker()->word);
            $course->setWhatToLearn($this->getFaker()->sentence(30));
            $course->setLanguage($language);

            $this->em->persist($course);

            $courseArray[] = $course;
        }

        $this->em->flush();

        return $courseArray;
    }
}