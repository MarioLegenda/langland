<?php

namespace PublicApi\Infrastructure\Model;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Course
 * @package PublicApi\Infrastructure\Model
 *
 * @ExclusionPolicy("none")
 */
class Course
{
    /**
     * @var int $id
     * @Type("int")
     * @Assert\NotBlank(message="id cannot be blank")
     */
    private $id;
    /**
     * @var string $name
     * @Type("string")
     * @Assert\NotBlank(message="name cannot be blank")
     */
    private $name;
    /**
     * @var string $whatToLearn
     * @Type("string")
     * @Assert\NotBlank(message="whatToLearn cannot be blank")
     */
    private $whatToLearn;
    /**
     * @var string $courseUrl
     * @Type("string")
     * @Assert\NotBlank(message="courseUrl cannot be blank")
     */
    private $courseUrl;
    /**
     * @var int $courseOrder
     * @Type("int")
     * @Assert\NotBlank(message="courseOrder cannot be blank")
     */
    private $courseOrder;
    /**
     * @var string $type
     * @Type("string")
     * @Assert\NotBlank(message="type cannot be blank")
     */
    private $type;
    /**
     * @var \DateTime $createdAt
     * @Type("DateTime<'Y-m-d H:m:s'>")
     * @Assert\NotBlank(message="createdAt cannot be blank")
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     * @Accessor(setter="setUpdatedAt")
     */
    private $updatedAt;
    /**
     * Course constructor.
     * @param int $id
     * @param string $name
     * @param string $whatToLearn
     * @param string $courseUrl
     * @param int $courseOrder
     * @param string $type
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     */
    public function __construct(
        int $id,
        string $name,
        string $whatToLearn,
        string $courseUrl,
        int $courseOrder,
        string $type,
        \DateTime $createdAt,
        \DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->whatToLearn = $whatToLearn;
        $this->courseUrl = $courseUrl;
        $this->courseOrder = $courseOrder;
        $this->type = $type;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function getWhatToLearn()
    {
        return $this->whatToLearn;
    }
    /**
     * @return string
     */
    public function getCourseUrl()
    {
        return $this->courseUrl;
    }
    /**
     * @return int
     */
    public function getCourseOrder()
    {
        return $this->courseOrder;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime|string $updatedAt
     * @return Course
     */
    public function setUpdatedAt($updatedAt) : Course
    {
        if (is_string($updatedAt)) {
            $updatedAt = $this->toDateTime($updatedAt);

            if (!$updatedAt instanceof \DateTime) {
                throw new \RuntimeException('Invalid date time in %s', Lesson::class);
            }

            $this->updatedAt = $updatedAt;
        }

        $this->updatedAt = $updatedAt;
    }
    /**
     * @param \DateTime|string $dateTime
     * @return \DateTime
     */
    private function toDateTime($dateTime): \DateTime
    {
        if ($dateTime instanceof \DateTime) {
            return $dateTime;
        }

        $dateTime = \DateTime::createFromFormat('Y-m-d H:m:s', $dateTime);

        if (!$dateTime instanceof \DateTime) {
            $message = sprintf('Invalid date time in %s', Lesson::class);
            throw new \RuntimeException($message);
        }

        return $dateTime;
    }
}