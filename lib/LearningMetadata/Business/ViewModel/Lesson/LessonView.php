<?php

namespace LearningMetadata\Business\ViewModel\Lesson;

use AdminBundle\Entity\Language;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as ValidationAssert;

class LessonView implements \JsonSerializable
{
    /**
     * @var Language $language
     * @Serializer\Exclude()
     * @ValidationAssert\NotBlank(message="Name cannot be empty")
     */
    private $language;
    /**
     * @var string $type
     * @Serializer\Type("string")
     * @ValidationAssert\NotBlank(message="Type cannot be empty")
     */
    private $type;
    /**
     * @var string $name
     * @Serializer\Type("string")
     * @ValidationAssert\NotBlank(message="Name cannot be empty")
     */
    protected $name;
    /**
     * @var string $name
     * @Serializer\Type("string")
     * @ValidationAssert\NotBlank(message="Description cannot be empty")
     */
    protected $description;
    /**
     * @var int $learningOrder
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("learningOrder")
     * @ValidationAssert\NotBlank(message="Learning order cannot be empty")
     * @ValidationAssert\Type(type="integer", message="Learning order has to be an integer")
     */
    protected $learningOrder;
    /**
     * LessonView constructor.
     * @param string $name
     * @param string $type
     * @param int $learningOrder
     * @param string $description
     */
    public function __construct(
        string $name,
        string $type,
        int $learningOrder,
        string $description
    ) {
        $this->setLearningOrder($learningOrder);
        $this->setName($name);
        $this->setDescription($description);
        $this->setType($type);
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
     * @return int
     */
    public function getLearningOrder(): int
    {
        return $this->learningOrder;
    }
    /**
     * @param int $learningOrder
     * @return LessonView
     */
    public function setLearningOrder(int $learningOrder): LessonView
    {
        $this->learningOrder = $learningOrder;

        return $this;
    }
    /**
     * @param Language $language
     */
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }
    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->getType(),
            'name' => $this->getName(),
            'learningOrder' => $this->getLearningOrder(),
            'description' => $this->getDescription(),
        ];

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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}