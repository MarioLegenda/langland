<?php

namespace LearningMetadata\Business\ViewModel\Lesson;

class LessonText
{
    /**
     * @var string $text
     */
    private $text;
    /**
     * LessonText constructor.
     * @param string $text
     */
    public function __construct(
        string $text
    ) {
        $this->text = $text;
    }
    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->text;
    }
}