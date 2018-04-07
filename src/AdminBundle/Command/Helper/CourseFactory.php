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
     * @var Course[] $courses
     */
    private $courses = [];
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
     * @param bool $save
     * @return array
     */
    public function create(Language $language, bool $save = false) : array
    {
        $courseTypes = CourseType::fromValue('Beginner')->toArray();
        $courseArray = array();

        $courseOrder = 1;
        foreach ($courseTypes as $courseType) {
            $course = new Course();

            $course->setName($language->getName() . ' course ' . $courseOrder);
            $course->setWhatToLearn($this->getFaker()->sentence(30));
            $course->setLanguage($language);
            $course->setType($courseType);
            $course->setCourseOrder($courseOrder);
            $course->setCourseUrl(\URLify::filter($course->getName()));

            $this->em->persist($course);

            $courseArray[] = $course;

            ++$courseOrder;
        }

        $this->em->flush();

        if ($save) {
            $this->courses = array_merge($this->courses, $courseArray);
        }

        return $courseArray;
    }
    /**
     * @return Course[]
     */
    public function getSavedCourses(): array
    {
        return $this->courses;
    }
    /**
     * @void
     */
    public function clear()
    {
        $this->courses = [];
    }
}