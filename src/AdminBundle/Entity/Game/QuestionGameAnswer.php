<?php

namespace AdminBundle\Entity\Game;

class QuestionGameAnswer
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
     * @var QuestionGame $question
     */
    private $question;
    /**
     * @var bool $isCorrect
     */
    private $isCorrect;
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
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param $name
     * @return QuestionGameAnswer
     */
    public function setName($name) : QuestionGameAnswer
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @return mixed
     */
    public function getisCorrect()
    {
        return $this->isCorrect;
    }
    /**
     * @param mixed $isCorrect
     */
    public function setIsCorrect($isCorrect)
    {
        $this->isCorrect = $isCorrect;
    }
    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }
    /**
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return QuestionGameAnswer
     */
    public function setCreatedAt($createdAt) : QuestionGameAnswer
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return QuestionGameAnswer
     */
    public function setUpdatedAt($updatedAt) : QuestionGameAnswer
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * Get updatedAt
     *
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