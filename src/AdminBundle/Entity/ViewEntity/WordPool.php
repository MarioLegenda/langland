<?php

namespace AdminBundle\Entity\ViewEntity;

class WordPool
{
    /**
     * @var string $wordIds
     */
    private $wordIds;
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
}