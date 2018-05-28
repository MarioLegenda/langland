<?php

namespace PublicApi\Infrastructure\Model\Word;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class InitialCreationWord
 * @package PublicApi\Infrastructure\Model
 *
 * @ExclusionPolicy("none")
 */
class InitialCreationWord
{
    /**
     * @var int $id
     * @Type("int")
     * @Assert\NotBlank(message="id cannot be blank")
     */
    private $id;
    /**
     * @var int $level
     * @Type("int")
     * @Assert\NotBlank(message="level cannot be blank")
     */
    private $level;
    /**
     * @var \DateTime $createdAt
     * @Type("DateTime<'Y-m-d H:m:s'>")
     * @Assert\NotBlank(message="createdAt cannot be blank")
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     * @Type("string")
     * @Accessor(setter="setUpdatedAt")
     */
    private $updatedAt;
    /**
     * InitialCreationWord constructor.
     * @param int $id
     * @param int $level
     * @param \DateTime $createdAt
     * @param \DateTime|null $updatedAt
     */
    public function __construct(
        int $id,
        int $level,
        \DateTime $createdAt,
        \DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->level = $level;
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
     * @return int
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }
    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime|string $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        if (is_string($updatedAt)) {
            $updatedAt = $this->toDateTime($updatedAt);

            if (!$updatedAt instanceof \DateTime) {
                throw new \RuntimeException('Invalid date time in %s', InitialCreationWord::class);
            }

            $this->updatedAt = $updatedAt;
        }
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
            $message = sprintf('Invalid date time in %s', InitialCreationWord::class);
            throw new \RuntimeException($message);
        }

        return $dateTime;
    }
}