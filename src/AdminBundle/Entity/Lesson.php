<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Lesson
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var bool $isInitialLesson
     */
    private $isInitialLesson;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var Course $course
     */
    private $course;
    /**
     * @var ArrayCollection $lessonTexts
     */
    private $lessonTexts;

    public function __construct()
    {
        $this->lessonTexts = new ArrayCollection();
        $this->isInitialLesson = false;
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Lesson
     */
    public function setName($name) : Lesson
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Lesson
     */
    public function setCreatedAt(\DateTime $createdAt) : Lesson
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime $updatedAt
     * @return Lesson
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Lesson
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @return bool
     */
    public function getIsInitialLesson(): bool
    {
        return $this->isInitialLesson;
    }
    /**
     * @param bool $isInitialLesson
     * @return Lesson
     */
    public function setIsInitialLesson(bool $isInitialLesson) : Lesson
    {
        $this->isInitialLesson = $isInitialLesson;

        return $this;
    }
    /**
     * Set course
     *
     * @param string $course
     *
     * @return Lesson
     */
    public function setCourse($course) : Lesson
    {
        $this->course = $course;

        return $this;
    }
    /**
     * Get course
     *
     * @return string
     */
    public function getCourse()
    {
        return $this->course;
    }
    /**
     * @param LessonText $lessonText
     * @return bool
     */
    public function hasLessonText(LessonText $lessonText) : bool
    {
        return $this->lessonTexts->contains($lessonText);
    }
    /**
     * @param LessonText $lessonText
     * @return Lesson
     */
    public function addLessonText(LessonText $lessonText) : Lesson
    {
        if (!$this->hasLessonText($lessonText)) {
            $lessonText->setLesson($this);
            $this->lessonTexts->add($lessonText);
        }

        return $this;
    }
    /**
     * @param LessonText $lessonText
     * @return Lesson
     */
    public function removeLessonText(LessonText $lessonText) : Lesson
    {
        if ($this->hasLessonText($lessonText)) {
            $this->lessonTexts->removeElement($lessonText);
        }

        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getLessonTexts()
    {
        return $this->lessonTexts;
    }
    /**
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (count($this->getLessonTexts()) < 1) {
            $context->buildViolation('There has to be at least one lesson text for this lesson')
                ->atPath('lessonTexts')
                ->addViolation();
        }
    }



    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

