<?php

namespace Armor\Infrastructure\Model;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Language
 * @package Armor\Infrastructure\Model
 *
 * @ExclusionPolicy("none")
 */
class Language implements ArmorModelInterface
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
     * @var bool $showOnPage
     * @Type("bool")
     * @Assert\NotBlank(message="showOnPage cannot be blank")
     */
    private $showOnPage;
    /**
     * @var string $listDescription
     * @Type("string")
     * @Serializer\SerializedName("description")
     * @Assert\NotBlank(message="listDescription cannot be blank")
     */
    private $listDescription;
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
    public function getListDescription()
    {
        return $this->listDescription;
    }
    /**
     * @return bool
     */
    public function getShowOnPage()
    {
        return $this->showOnPage;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}