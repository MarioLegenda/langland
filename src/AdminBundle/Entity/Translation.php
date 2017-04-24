<?php

namespace AdminBundle\Entity;

class Translation
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var Word $word
     */
    private $word;
    /**
     * Translation constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
    /**
     * @param mixed $id
     * @return Translation
     */
    public function setId($id) : Translation
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return Translation
     */
    public function setName(string $name) : Translation
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param mixed $createdAt
     * @return Translation
     */
    public function setCreatedAt($createdAt) : Translation
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }
    /**
     * @param mixed $word
     * @return Translation
     */
    public function setWord($word) : Translation
    {
        $this->word = $word;

        return $this;
    }
}