<?php

namespace AdminBundle\Entity;

class Category
{
    private $id;
    /**
     * @var string $category
     */
    private $name;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
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
     * @return Category
     */
    public function setName(string $name) : Category
    {
        $this->name = $name;

        return $this;
    }
}