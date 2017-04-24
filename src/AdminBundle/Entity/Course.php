<?php

namespace AdminBundle\Entity;

use Symfony\Component\Validator\Context\ExecutionContext;

class Course
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;

    private $language;
    /**
     * @var string
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Course
     */
    public function setName($name) : Course
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Course
     */
    public function setCreatedAt($createdAt) : Course
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * Get createdAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     * @param mixed $language
     * @return Course
     */
    public function setLanguage($language) : Course
    {
        $this->language = $language;

        return $this;
    }
}

