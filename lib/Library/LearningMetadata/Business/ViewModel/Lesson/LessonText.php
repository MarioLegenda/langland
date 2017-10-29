<?php

namespace Library\LearningMetadata\Business\ViewModel\Lesson;

class LessonText implements \JsonSerializable
{
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $text
     */
    private $text;
    /**
     * LessonText constructor.
     * @param string $name
     * @param string $text
     */
    public function __construct(
        string $name,
        string $text
    ) {
        $this->name = $name;
        $this->text = $text;
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
     */
    public function setName(string $name)
    {
        $this->name = $name;
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
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'text' => $this->getText(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}