<?php

namespace AdminBundle\Entity\ViewEntity;

class WordPool
{
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $wordIds
     */
    private $wordIds;
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
    public function getWordIds()
    {
        return $this->wordIds;
    }
    /**
     * @param mixed $wordIds
     */
    public function setWordIds($wordIds)
    {
        $this->wordIds = $wordIds;
    }
    /**
     * @return bool
     */
    public function canBeResolved() : bool
    {
        if (empty($this->wordIds)) {
            return false;
        }

        return true;
    }
    /**
     * @return array
     */
    public function resolveIds() : array
    {
        return explode(',', $this->wordIds);
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     * @return WordPool
     */
    public function setName($name) : WordPool
    {
        $this->name = $name;

        return $this;
    }
}