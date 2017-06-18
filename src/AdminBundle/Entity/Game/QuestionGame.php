<?php

namespace AdminBundle\Entity\Game;

use AdminBundle\Entity\Lesson;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class QuestionGame
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
     * @var string $description
     */
    private $description;
    /**
     * @var string $url
     */
    private $url;
    /**
	*  @var $maxTime
	*/
	private $maxTime;
    /**
     * @var $minTime
     */
	private $minTime;
    /**
     * @var bool $hasTimeLimit
     */
	private $hasTimeLimit;
    /**
     * @var Lesson $lesson
     */
    private $lesson;
    /**
     * @var ArrayCollection $answers
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

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->hasTimeLimit = false;
		$this->maxTime = null;
		$this->minTime = 5;
    }

    public function getId()
    {
        return $this->id;
    }
    /**
     * @param $name
     * @return QuestionGame
     */
    public function setName($name) : QuestionGame
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
     * @param $description
     * @return QuestionGame
     */
    public function setDescription($description) : QuestionGame
    {
        $this->description = $description;

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
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * @param mixed $url
     * @return QuestionGame
     */
    public function setUrl($url) : QuestionGame
    {
        $this->url = $url;

        return $this;
    }
    /**
     * @return int
     */
    public function getMaxTime()
    {
        return $this->maxTime;
    }
    /**
     * @param int $maxTime
     * @return QuestionGame
     */
    public function setMaxTime($maxTime) : QuestionGame
    {
        $this->maxTime = $maxTime;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getMinTime()
    {
        return $this->minTime;
    }
    /**
     * @param mixed $minTime
     * @return QuestionGame
     */
    public function setMinTime($minTime) : QuestionGame
    {
        $this->minTime = $minTime;

        return $this;
    }
    /**
     * @return bool
     */
    public function getHasTimeLimit(): bool
    {
        return $this->hasTimeLimit;
    }
    /**
     * @param bool $hasTimeLimit
     * @return QuestionGame
     */
    public function setHasTimeLimit(bool $hasTimeLimit) : QuestionGame
    {
        $this->hasTimeLimit = $hasTimeLimit;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getLesson()
    {
        return $this->lesson;
    }
    /**
     * @param mixed $lesson
     * @return QuestionGame
     */
    public function setLesson($lesson) : QuestionGame
    {
        $this->lesson = $lesson;

        return $this;
    }
    /**
     * @param QuestionGameAnswer $answer
     * @return bool
     */
    public function hasAnswer(QuestionGameAnswer $answer) : bool
    {
        return $this->answers->contains($answer);
    }
    /**
     * @param QuestionGameAnswer $answer
     * @return QuestionGame
     */
    public function addAnswer(QuestionGameAnswer $answer) : QuestionGame
    {
        if (!$this->hasAnswer($answer)) {
            $answer->setQuestion($this);
            $this->answers->add($answer);
        }

        return $this;
    }
    /**
     * @param QuestionGameAnswer $answer
     * @return QuestionGame
     */
    public function removeAnswer(QuestionGameAnswer $answer) : QuestionGame
    {
        if ($this->hasAnswer($answer)) {
            $this->answers->removeElement($answer);
        }

        return $this;
    }
    /**
     * @param $answers
     * @return QuestionGame
     */
    public function setAnswers($answers) : QuestionGame
    {
        $this->answers = $answers;

        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return QuestionGame
     */
    public function setCreatedAt($createdAt) : QuestionGame
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
     * @return QuestionGame
     */
    public function setUpdatedAt($updatedAt) : QuestionGame
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
    /**
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (count($this->getAnswers()) === 0) {
            $context->buildViolation('* There has to be at least one answer associated to this question')
                ->atPath('answers')
                ->addViolation();
        }

        foreach ($this->getAnswers() as $answer) {
            if ($answer->getIsCorrect() === true) {
                return;
            }
        }

        $context->buildViolation('* You haven\'t labeled any answer as correct. At least one answer has to be the correct one')
            ->atPath('answers')
            ->addViolation();
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}
