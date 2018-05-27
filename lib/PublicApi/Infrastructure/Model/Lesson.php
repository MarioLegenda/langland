<?php

namespace PublicApi\Infrastructure\Model;

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
     * @var int $learningOrder
     */
    private $learningOrder;
    /**
     * @var string $description
     */
    private $description;
    /**
     * @var array $jsonLesson
     */
    private $jsonLesson;
    /**
     * @var string $urlifiedName
     */
    private $urlifiedName;
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
    /**
     * Lesson constructor.
     * @param int $id
     * @param string $name
     * @param UuidInterface $uuid
     * @param int $learningOrder
     * @param array $jsonLesson
     * @param Course $course
     * @param string $description
     */
    public function __construct(
        int $id,
        string $name,
        UuidInterface $uuid,
        int $learningOrder,
        array $jsonLesson,
        Course $course,
        string $description
    ) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->learningOrder = $learningOrder;
        $this->jsonLesson = $jsonLesson;
        $this->course = $course;
        $this->name = $name;
        $this->description = $description;

        $this->createUrlifiedFromName($name);
    }
    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }
    /**
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
     * @return int
     */
    public function getLearningOrder(): int
    {
        return $this->learningOrder;
    }
    /**
     * @param int $learningOrder
     * @return Lesson
     */
    public function setLearningOrder(int $learningOrder): Lesson
    {
        $this->learningOrder = $learningOrder;

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
     * @return array
     */
    public function getJsonLesson(): array
    {
        return $this->jsonLesson;
    }
    /**
     * @param array|string $jsonLesson
     * @return Lesson
     */
    public function setJsonLesson($jsonLesson): Lesson
    {
        if (is_string($jsonLesson)) {
            $this->jsonLesson = json_decode($jsonLesson, true);

            return $this;
        }

        $this->jsonLesson = $jsonLesson;

        return $this;
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @param string $description
     * @return Lesson
     */
    public function setDescription($description): Lesson
    {
        $this->description = $description;

        return $this;
    }
    /**
     * @return string
     */
    public function getUrlifiedName(): string
    {
        if (!is_string($this->urlifiedName)) {
            $this->createUrlifiedFromName($this->getName());
        }

        return $this->urlifiedName;
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
     * @param \DateTime|string $createdAt
     *
     * @return Lesson
     */
    public function setCreatedAt($createdAt) : Lesson
    {
        $createdAt = $this->toDateTime($createdAt);

        if (!$createdAt instanceof \DateTime) {
            throw new \RuntimeException('Invalid date time in %s', Lesson::class);
        }

        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime|string $updatedAt
     * @return Lesson
     */
    public function setUpdatedAt($updatedAt) : Lesson
    {
        $updatedAt = $this->toDateTime($updatedAt);

        if (!$updatedAt instanceof \DateTime) {
            throw new \RuntimeException('Invalid date time in %s', Lesson::class);
        }

        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @param \DateTime|string $dateTime
     * @return \DateTime
     */
    private function toDateTime($dateTime): \DateTime
    {
        if ($dateTime instanceof \DateTime) {
            return $dateTime;
        }

        $dateTime = \DateTime::createFromFormat('Y-m-d H:m:s', $dateTime);

        if (!$dateTime instanceof \DateTime) {
            $message = sprintf('Invalid date time in %s', Lesson::class);
            throw new \RuntimeException($message);
        }

        return $dateTime;
    }
    /**
     * @param string $name
     */
    private function createUrlifiedFromName(string $name)
    {
        $this->urlifiedName = \URLify::filter($name);
    }
}