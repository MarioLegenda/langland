<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Language;
use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\Course;
use PublicApi\Infrastructure\Type\CourseType;

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
        $courseTypes = CourseType::fromValue('Beginner')->toArray();
        $courseArray = array();

        for ($i = 0; $i < $numberOfEntries; $i++) {
            $course = new Course();

            $course->setName($language->getName() . ' course ' . $i);
            $course->setWhatToLearn($this->getFaker()->sentence(30));
            $course->setLanguage($language);
            $course->setType($courseTypes[rand(0, 2)]);
            $course->setCourseOrder(rand(0, 10));
            $course->setCourseUrl(\URLify::filter($course->getName()));

            $this->em->persist($course);

            $courseArray[] = $course;
        }

        $this->em->flush();

        return $courseArray;
    }
}