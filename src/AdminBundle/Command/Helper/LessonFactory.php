<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use LearningMetadata\Business\ViewModel\Lesson\LessonText;
use LearningMetadata\Business\ViewModel\Lesson\LessonView;
use LearningMetadata\Business\ViewModel\Lesson\Tip;
use Ramsey\Uuid\Uuid;

class LessonFactory
{
    use FakerTrait;
    /**
     * @var array $lessons
     */
    private $lessons;
    /**
     * @var EntityManagerInterface $em
     */
    private $em;
    /**
     * LessonFactory constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
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
            $tips = [];
            for ($i = 0; $i < 10; $i++) {
                $tips[] = new Tip($this->getFaker()->name);
            }

            $lessonTexts = [];
            for ($i = 0; $i < 10; $i++) {
                $lessonTexts[] = new LessonText($this->getFaker()->text);
            }

            $lessonView = new LessonView(
                Uuid::uuid4(),
                $this->getFaker()->name,
                $i,
                $tips,
                $lessonTexts
            );

            $lesson = new Lesson(
                $lessonView->getName(),
                $lessonView->getUuid(),
                $i,
                $lessonView->toArray(),
                $course
            );

            $this->lessons[] = $lesson;

            $this->em->persist($lesson);
        }

        $this->em->flush();

        return $this->lessons;
    }
}