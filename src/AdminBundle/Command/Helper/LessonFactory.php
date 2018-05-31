<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\Lesson;
use Doctrine\ORM\EntityManagerInterface;
use LearningMetadata\Infrastructure\Type\CourseType;
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
     * @param Language $language
     * @param int $numberOfEntries
     * @param bool $save
     * @return array
     */
    public function create(Language $language, int $numberOfEntries = null, bool $save = false)
    {
        $lessons = [];
        for ($i = 0; $i < $numberOfEntries; $i++) {

            $name = 'name-'.Uuid::uuid4()->toString();
            $type = (string) CourseType::fromValue('Beginner');

            $lesson = new Lesson(
                $name,
                $type,
                $i,
                $this->getFaker()->sentence(60),
                $language
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