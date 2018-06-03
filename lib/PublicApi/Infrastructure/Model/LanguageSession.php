<?php

namespace PublicApi\Infrastructure\Model;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LanguageSession
 * @package PublicApi\Infrastructure\Model
 *
 * @Serializer\ExclusionPolicy("none")
 */
class LanguageSession
{
    /**
     * @var int $id
     * @Type("int")
     * @Assert\Type("int")
     * @Assert\NotNull(message="id cannot be null")
     */
    private $id;
    /**
     * @var \DateTime $createdAt
     * @Type("DateTime<'Y-m-d H:m:s'>")
     * @Assert\Type("object")
     * @Assert\NotNull(message="createdAt cannot be null")
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     * @Type("DateTime<'Y-m-d H:m:s'>")
     * @Assert\Type("object")
     * @Assert\NotNull(message="updatedAt cannot be null")
     */
    private $updatedAt;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}