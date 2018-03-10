<?php

namespace PublicApiBundle\Entity;

use AdminBundle\Entity\Lesson;
use LearningSystem\Library\Repository\Contract\SystemHeadInterface;
use LearningSystemBundle\Entity\SystemNeuron;

class LearningLesson
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var SystemNeuron $systemNeuron
     */
    private $systemNeuron;
    /**
     * @var Lesson $lesson
     */
    private $lesson;
    /**
     * @var bool $hasPassed
     */
    private $hasPassed = false;
    /**
     * @param int $id
     * @return LearningLesson
     */
    public function setId(int $id): LearningLesson
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @return bool
     */
    public function hasPassed(): bool
    {
        return $this->hasPassed;
    }
    /**
     * @param bool $hasPassed
     * @return LearningLesson
     */
    public function setAsPassed(bool $hasPassed): LearningLesson
    {
        $this->hasPassed = $hasPassed;

        return $this;
    }
    /**
     * @return Lesson
     */
    public function getLesson(): Lesson
    {
        return $this->lesson;
    }
    /**
     * @param Lesson $lesson
     * @return LearningLesson
     */
    public function setLesson(Lesson $lesson): LearningLesson
    {
        $this->lesson = $lesson;

        return $this;
    }
}