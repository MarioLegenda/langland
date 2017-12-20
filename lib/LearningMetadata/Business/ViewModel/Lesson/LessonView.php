<?php

namespace LearningMetadata\Business\ViewModel\Lesson;

use JMS\Serializer\Annotation as Serializer;
use Library\Infrastructure\Notation\ArrayNotationInterface;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as ValidationAssert;

class LessonView implements \JsonSerializable
{
    /**
     * @var UuidInterface $uuid
     */
    protected $uuid;
    /**
     * @var string $name
     * @Serializer\Type("string")
     * @ValidationAssert\NotBlank(message="Name cannot be empty")
     */
    protected $name;
    /**
     * @var array $tips
     * @Serializer\Type("array")
     * @Serializer\Accessor(setter="setTips")
     */
    protected $tips = [];
    /**
     * @var LessonText[] $lessonTexts
     * @Serializer\Type("array")
     * @Serializer\Accessor(setter="setLessonTexts")
     * @Serializer\SerializedName("lessonTexts")
     * @ValidationAssert\NotBlank(message="There has to be at least one lesson text")
     */
    protected $lessonTexts = [];
    /**
     * LessonView constructor.
     * @param UuidInterface $uuid
     * @param string $name
     * @param array $tips
     * @param array $lessonTexts
     */
    public function __construct(
        UuidInterface $uuid,
        string $name,
        array $tips,
        array $lessonTexts
    ) {
        $this->uuid = $uuid;
        $this->setName($name);
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
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
    /**
     * @param UuidInterface $uuid
     */
    public function setUuid(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
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
     * @return LessonView
     */
    public function setTips($tips): LessonView
    {
        foreach ($tips as $tip) {
            $this->addTip(new Tip($tip));
        }

        return $this;
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
     * @return LessonView
     */
    public function setLessonTexts(array $lessonTexts): LessonView
    {
        foreach ($lessonTexts as $lessonText) {
            $this->addLessonText(new LessonText($lessonText));
        }

        return $this;
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
            $lessonTexts[] = (string) $text;
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