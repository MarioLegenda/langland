<?php

namespace AdminBundle\Entity;

use AdminBundle\Entity\Game\QuestionGame;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use AdminBundle\Entity\Game\WordGame;

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
     * @var ArrayCollection $wordGames
     */
    private $wordGames;
    /**
     * @var ArrayCollection $questionGames
     */
    private $questionGames;
    /**
     * @var ArrayCollection $lessonTexts
     */
    private $lessonTexts;

    public function __construct()
    {
        $this->lessonTexts = new ArrayCollection();
        $this->wordGames = new ArrayCollection();
        $this->questionGames = new ArrayCollection();

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
     * @param WordGame $game
     * @return Lesson
     */
    public function hasWordGame(WordGame $game) : Lesson
    {
        return $this->wordGames->contains($game);
    }
    /**
     * @param WordGame $game
     * @return Lesson
     */
    public function addWordGame(WordGame $game) : Lesson
    {
        if (!$this->hasWordGame($game)) {
            $game->setLesson($this);
            $this->wordGames->add($game);
        }

        return $this;
    }
    /**
     * @param WordGame $game
     * @return Lesson
     */
    public function removeWordGame(WordGame $game) : Lesson
    {
        if ($this->hasWordGame($game)) {
            $this->wordGames->removeElement($game);
        }

        return $this;
    }
    /**
     * @return mixed
     */
    public function getGames()
    {
        return $this->wordGames;
    }
    /**
     * @param mixed $games
     */
    public function setGames($games)
    {
        $this->wordGames = $games;
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
     * @param QuestionGame $game
     * @return bool
     */
    public function hasQuestionGame(QuestionGame $game) : bool
    {
        return $this->questionGames->contains($game);
    }
    /**
     * @param QuestionGame $game
     * @return Lesson
     */
    public function addQuestionGame(QuestionGame $game) : Lesson
    {
        if (!$this->hasQuestionGame($game)) {
            $game->setLesson($this);
            $this->questionGames->add($game);
        }

        return $this;
    }
    /**
     * @param QuestionGame $game
     * @return Lesson
     */
    public function removeQuestionGame(QuestionGame $game) : Lesson
    {
        if ($this->hasQuestionGame($game)) {
            $this->questionGames->removeElement($game);
        }

        return $this;
    }
    /**
     * @param $games
     * @return Lesson
     */
    public function setQuestionGames($games) : Lesson
    {
        $this->questionGames = $games;

        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getQuestionGames()
    {
        return $this->questionGames;
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
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (count($this->getLessonTexts()) < 1) {
            $context->buildViolation('There has to be at least one lesson text for this lesson')
                ->atPath('lessonTexts')
                ->addViolation();

            return;
        }

        $emptyTexts = 0;
        foreach ($this->getLessonTexts() as $lessonText) {
            if (empty($lessonText->getName()) and empty($lessonText->getText())) {
                ++$emptyTexts;
            }
        }

        if ($emptyTexts === count($this->getLessonTexts())) {
            $context->buildViolation('All lesson texts are empty')
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

