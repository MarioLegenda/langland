<?php

namespace LearningMetadata\Business\ViewModel\Lesson;

class Tip
{
    /**
     * @var string $tip
     */
    private $tip;
    /**
     * Tip constructor.
     * @param string $tip
     */
    public function __construct(string $tip)
    {
        $this->tip = $tip;
    }
    /**
     * @return string
     */
    public function getTip(): string
    {
        return $this->tip;
    }
    /**
     * @param string $tip
     */
    public function setTip(string $tip)
    {
        $this->tip = $tip;
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->tip;
    }
}