<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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
     * @var UuidInterface $uuid
     */
    private $uuid;
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
     * @var ArrayCollection $basicWordGames
     */
    private $basicWordGames;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * Lesson constructor.
     * @param string $name
     * @param UuidInterface $uuid
     * @param int $order
     * @param array $jsonLesson
     * @param Course $course
     */
    public function __construct(
        string $name,
        UuidInterface $uuid,
        int $order,
        array $jsonLesson,
        Course $course
    ) {
        $this->uuid = $uuid;
        $this->lessonOrder = $order;
        $this->jsonLesson = $jsonLesson;
        $this->course = $course;
        $this->name = $name;

        $this->basicWordGames = new ArrayCollection();
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return Lesson
     */
    public function setName($name): Lesson
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return Uuid::fromString($this->uuid);
    }
    /**
     * @param string $uuid
     * @return Lesson
     */
    public function setUuid($uuid): Lesson
    {
        $this->uuid = $uuid;

        return $this;
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
     * @return ArrayCollection
     */
    public function getBasicWordGames()
    {
        return $this->basicWordGames;
    }
    /**
     * @param array $basicWordGames
     * @return Lesson
     */
    public function setBasicWordGames(array $basicWordGames): Lesson
    {
        $this->basicWordGames = $basicWordGames;
    }
    /**
     * @param BasicWordGame $basicWordGame
     * @return Lesson
     */
    public function addBasicWordGame(BasicWordGame $basicWordGame): Lesson
    {
        $this->basicWordGames->add($basicWordGame);

        return $this;
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

