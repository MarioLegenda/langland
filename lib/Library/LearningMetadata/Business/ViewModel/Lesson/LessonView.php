<?php

namespace Library\LearningMetadata\Business\ViewModel\Lesson;

use Library\Infrastructure\Notation\ArrayNotationInterface;

class LessonView implements \JsonSerializable
{
    /**
     * @var string $internalName
     */
    protected $internalName;
    /**
     * @var string $name
     */
    protected $name;
    /**
     * @var Tip[] $tips
     */
    protected $tips = [];
    /**
     * @var LessonText[] $lessonTexts
     */
    protected $lessonTexts = [];

    public function __construct(
        string $internalName,
        string $name
    ) {
        $this->name = $name;
        $this->internalName = $internalName;
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
    public function getInternalName(): string
    {
        return $this->internalName;
    }
    /**
     * @param string $internalName
     */
    public function setInternalName(string $internalName)
    {
        $this->internalName = $internalName;
    }
    /**
     * @param Tip $tip
     * @return LessonView
     */
    public function addTip(Tip $tip): LessonView
    {
        $this->tips[] = $tip;

        return $this;
    }
    /**
     * @return Tip[]
     */
    public function getTips(): array
    {
        return $this->tips;
    }
    /**
     * @param Tip[] $tips
     */
    public function setTips(array $tips)
    {
        $this->tips = $tips;
    }
    /**
     * @return LessonText[]
     */
    public function getLessonTexts(): array
    {
        return $this->lessonTexts;
    }
    /**
     * @param LessonText[] $lessonTexts
     */
    public function setLessonTexts(array $lessonTexts)
    {
        $this->lessonTexts = $lessonTexts;
    }
    /**
     * @param LessonText $lessonText
     * @return LessonView
     */
    public function addLessonText(LessonText $lessonText): LessonView
    {
        $this->lessonTexts[] = $lessonText;

        return $this;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [
            'internalName' => $this->getInternalName(),
            'name' => $this->getName(),
            'tips' => [],
            'lessonTexts' => [],
        ];

        $tips = [];
        foreach ($this->getTips() as $tip) {
            $tips[] = (string) $tip;
        }

        $array['tips'] = $tips;

        $lessonTexts = [];
        /** @var ArrayNotationInterface $text */
        foreach ($this->getLessonTexts() as $text) {
            $lessonTexts[] = $text->toArray();
        }

        $array['lessonTexts'] = $lessonTexts;

        return $array;
    }
    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }
    /**
     * @param array $lessonView
     * @return LessonView
     */
    public static function createFromArray(array $lessonView): LessonView
    {
        $view = new LessonView(
            $lessonView['internalName'],
            $lessonView['name']
        );

        foreach ($lessonView['tips'] as $tip) {
            $view->addTip(new Tip($tip));
        }

        foreach ($lessonView['lessonTexts'] as $lessonText) {
            $view->addLessonText(new LessonText(
                $lessonText['name'],
                $lessonText['text']
            ));
        }

        return $view;
    }
}