<?php

namespace PublicApiBundle\Entity;

class Question
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
     * @var string $question
     */
    private $question;
    /**
     * @var array $answers
     */
    private $answers;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return Question
     */
    public function setName(string $name): Question
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }
    /**
     * @param string $question
     * @return Question
     */
    public function setQuestion(string $question): Question
    {
        $this->question = $question;

        return $this;
    }
    /**
     * @return array
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }
    /**
     * @param array $answers
     * @return Question
     */
    public function setAnswers(array $answers): Question
    {
        $this->answers = $answers;

        return $this;
    }
    /**
     * @param \DateTime $createdAt
     * @return Question
     */
    public function setCreatedAt(\DateTime $createdAt) : Question
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $updatedAt
     * @return Question
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Question
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}