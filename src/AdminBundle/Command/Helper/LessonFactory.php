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
    private $lessons = [];
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
     * @param bool $save
     * @return array
     */
    public function create(Course $course, int $numberOfEntries = null, bool $save = false)
    {
        $lessons = [];
        for ($i = 0; $i < $numberOfEntries; $i++) {
            $tips = [];
            for ($a = 0; $a < 10; $a++) {
                $tips[] = new Tip($this->getFaker()->name);
            }

            $lessonTexts = [];
            for ($b = 0; $b < 10; $b++) {
                $lessonTexts[] = new LessonText($this->getFaker()->text);
            }

            $lessonView = new LessonView(
                Uuid::uuid4(),
                $this->getFaker()->name,
                $i,
                $tips,
                $lessonTexts,
                $this->getFaker()->sentence(60)
            );

            $lesson = new Lesson(
                $lessonView->getName(),
                $lessonView->getUuid(),
                $i,
                $lessonView->toArray(),
                $course,
                $this->getFaker()->sentence(60)
            );

            $lessons[] = $lesson;

            $this->em->persist($lesson);
        }

        $this->em->flush();

        if ($save) {
            $this->lessons = array_merge($this->lessons, $lessons);
        }

        return $this->lessons;
    }
    /**
     * @return Lesson[]
     */
    public function getSavedLessons(): array
    {
        return $this->lessons;
    }
    /**
     * @void
     */
    public function clear()
    {
        $this->lessons = [];
    }
}