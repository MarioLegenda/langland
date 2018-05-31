<?php

namespace AdminBundle\Entity;

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
     * @var string $type
     */
    private $type;
    /**
     * @var int $learningOrder
     */
    private $learningOrder;
    /**
     * @var string $description
     */
    private $description;
    /**
     * @var string $urlifiedName
     */
    private $urlifiedName;
    /**
     * @var Language $language
     */
    private $language;
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
     * @param string $type
     * @param int $learningOrder
     * @param string $description
     * @param Language $language
     */
    public function __construct(
        string $name,
        string $type,
        int $learningOrder,
        string $description,
        Language $language
    ) {
        $this->language = $language;
        $this->learningOrder = $learningOrder;
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
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
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }
    /**
     * @param Language $language
     */
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
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
     * @void
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
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

