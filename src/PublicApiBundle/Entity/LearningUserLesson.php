<?php

namespace PublicApiBundle\Entity;

class LearningUserLesson
{
    /**
     * @var bool $hasPassed
     */
    private $hasPassed = false;
    /**
     * @return bool
     */
    public function hasPassed(): bool
    {
        return $this->hasPassed;
    }
    /**
     * @param bool $hasPassed
     * @return LearningUserLesson
     */
    public function setAsPassed(bool $hasPassed): LearningUserLesson
    {
        $this->hasPassed = $hasPassed;

        return $this;
    }
}