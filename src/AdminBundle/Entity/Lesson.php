<?php

namespace AdminBundle\Entity;

class Lesson
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var int $order
     */
    private $lessonOrder;
    /**
     * @var array $jsonLesson
     */
    private $jsonLesson;
    /**
     * @var Course $course
     */
    private $course;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    public function __construct(
        int $order,
        array $jsonLesson,
        Course $course
    ) {
        $this
            ->setLessonOrder($order)
            ->setJsonLesson($jsonLesson)
            ->setCourse($course);
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
     * @return int
     */
    public function getLessonOrder(): int
    {
        return $this->lessonOrder;
    }
    /**
     * @param int $order
     * @return Lesson
     */
    public function setLessonOrder(int $order): Lesson
    {
        $this->lessonOrder = $order;

        return $this;
    }
    /**
     * @return array
     */
    public function getJsonLesson(): array
    {
        return $this->jsonLesson;
    }
    /**
     * @param array $jsonLesson
     * @return Lesson
     */
    public function setJsonLesson(array $jsonLesson): Lesson
    {
        $this->jsonLesson = $jsonLesson;

        return $this;
    }
    /**
     * @param string $course
     * @return Lesson
     */
    public function setCourse($course) : Lesson
    {
        $this->course = $course;

        return $this;
    }
    /**
     * @return string
     */
    public function getCourse()
    {
        return $this->course;
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
     * @void
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

