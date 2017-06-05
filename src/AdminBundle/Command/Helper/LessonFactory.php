<?php

namespace AdminBundle\Command\Helper;

use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Course;
use AdminBundle\Entity\LessonText;

class LessonFactory
{
    use FakerTrait;
    /**
     * @var array $lessons
     */
    private $lessons;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * LessonFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param Course $course
     * @param int $numberOfEntries
     * @return array
     */
    public function create(Course $course, int $numberOfEntries)
    {
        for ($i = 0; $i < $numberOfEntries; $i++) {
            $lesson = new Lesson();

            if ($i === 0) {
                $lesson->setIsInitialLesson(true);
            }

            $lesson->setName('Lesson '.($i + 1));
            $lesson->setDescription($this->getFaker()->sentence(100));
            $lesson->setCourse($course);

            for ($v = 0; $v < 5; $v++) {
                $lessonText = new LessonText();
                $lessonText->setName($this->getFaker()->word);
                $lessonText->setText($this->getFaker()->sentence(20));
                $lessonText->setLesson($lesson);

                $lesson->addLessonText($lessonText);
            }

            $this->em->persist($lesson);

            $this->lessons[] = $lesson;
        }

        $this->em->flush();

        return $this->lessons;
    }
}