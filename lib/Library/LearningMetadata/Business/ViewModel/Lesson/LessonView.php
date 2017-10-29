<?php

namespace Library\LearningMetadata\Business\ViewModel\Lesson;

use JMS\Serializer\Annotation as Serializer;
use Library\Infrastructure\Notation\ArrayNotationInterface;
use Webmozart\Assert\Assert;

class LessonView implements \JsonSerializable
{
    /**
     * @var string $internalName
     * @Serializer\Type("string")
     */
    protected $internalName;
    /**
     * @var string $name
     * @Serializer\Type("string")
     */
    protected $name;
    /**
     * @var Tip[] $tips
     * @Serializer\Type("array")
     * @Serializer\Accessor(setter="setTips")
     */
    protected $tips = [];
    /**
     * @var LessonText[] $lessonTexts
     * @Serializer\Type("array")
     * @Serializer\Accessor(setter="setLessonTexts")
     */
    protected $lessonTexts = [];

    public function __construct(
        string $internalName,
        string $name,
        array $tips,
        array $lessonTexts
    ) {
        $this->setName($name);
        $this->setInternalName($internalName);
        $this->setTips($tips);
        $this->setLessonTexts($lessonTexts);
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
        foreach ($tips as $tip) {
            if ($tip instanceof Tip) {
                $this->addTip($tip);

                continue;
            }

            Assert::string($tip, 'Tip should be a string');

            $this->addTip(new Tip($tip));
        }
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
        foreach ($lessonTexts as $text) {
            if ($text instanceof LessonText) {
                $this->addLessonText($text);

                continue;
            }

            Assert::keyExists($text, 'name', 'Lesson text should have a key \'name\'');
            Assert::keyExists($text, 'text', 'Lesson text should have a key \'name\'');
            Assert::string($text['name'], 'Lesson name should be a string');
            Assert::string($text['text'], 'Lesson text should be a string');

            $this->addLessonText(new LessonText(
                $text['name'],
                $text['text']
            ));
        }
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
}